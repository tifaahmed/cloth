<?php

namespace App\Http\Controllers\Api\Item;

use Illuminate\Http\Request; // at the top of the class

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Item\StoreItemRequest;
use App\Http\Requests\Api\Item\UpdateItemRequest;
use App\Http\Resources\Item\ItemResource;
use App\Models\Extra;
use App\Models\Item;
use App\Models\ItemImages;
use App\Models\Variants;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repository\ItemRepositoryInterface as ModelInterface;
use Illuminate\Http\Response;

class ItemController extends Controller
{

    public function __construct(private ModelInterface $modelInterface){}

    public function count()
    {
        // Define an array mapping status codes to count attributes
        $itemStatuses = [
            'items_count'           => DB::raw('COUNT(*) AS items_count'),
            'is_available_count'    => DB::raw('SUM(CASE WHEN is_available = "1" THEN 1 ELSE 0 END) AS is_available_count'),
            'not_available_count'   => DB::raw('SUM(CASE WHEN is_available = "2" THEN 1 ELSE 0 END) AS not_available_count'),
            'has_variants_count'    => DB::raw('SUM(CASE WHEN has_variants = "1" THEN 1 ELSE 0 END) AS has_variants_count'),
        ];

        // Perform the query using the defined status counts
        $itemCounts = Item::selectRaw(implode(', ', $itemStatuses))
            ->where('vendor_id',auth()->id())
            ->first();

        // Return the order counts as an associative array
        return array_map(function ($count) {
            return $count ?? 0; // Replace null values with 0 to ensure consistent output
        }, $itemCounts->toArray());
    }

    public function all(Request $request)
    {
        try {

            if ($request->filter) {
                $filter = [];
                foreach ($request->filter as $key => $value) {
                    $filter[$key]= $value;
                }
                $filter['vendor_id']= auth()->user()->vendor_id;
                $request->request->add(['filter' => $filter]);
            }else{
                $request->request->add(
                    [
                        'filter' =>[
                            'vendor_id' => auth()->user()->vendor_id,
                        ]
                    ]
                );
            }
            $modal = $this->modelInterface->all();
            return ItemResource::collection($modal);
        } catch (\Exception $e) {
            return $this->MakeResponseErrors([
                $e->getMessage()],
                'Errors',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function index(Request $request)
    {
        if ($request->filter) {
            $filter = [];
            foreach ($request->filter as $key => $value) {
                $filter[$key]= $value;
            }
            $filter['vendor_id']= auth()->user()->vendor_id;
            $request->request->add(['filter' => $filter]);
        }else{
            $request->request->add(
                [
                    'filter' =>[
                        'vendor_id' => auth()->user()->vendor_id,
                    ]
                ]
            );
        }
        $items = $this->modelInterface->collection($request->per_page ?? 10);
        return ItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        DB::beginTransaction();
        try {
            $slug           = Str::slug($request->item_name_en . ' ', '-') . '-' . Str::random(5);
            $price          = $request->item_price;
            $original_price = $request->item_original_price;

            if ($request->has_variants == 1) {
                foreach ($request->variations as $key => $no) {
                    $price          = $request->variations[$key]['price'];
                    $original_price = $request->variations[$key]['variation_original_price'];
                    break;
                }
            }

            $product                = new Item();
            $product->vendor_id     = Auth::user()->id ?? 1;
            $product->cat_id        = $request->cat_id;
            $product->item_name     = $request->item_name;
            $product->slug                  = $slug;
            $product->item_price            = $price;
            $product->item_original_price   = $original_price;
            $product->has_variants          = $request->has_variants;
            $product->tax                   = $request->tax;
            $product->description           = $request->description;
            $product->start_time            = $request->start_time;
            $product->end_time              = $request->end_time;

            $product->save();

            if ($request->has_variants == 1) {
                foreach ($request->variations as $key => $no) {
                    $variation                  = new Variants();
                    $variation->item_id         = $product->id;
                    $variation->name            = $request->variations[$key]['name'];
                    $variation->price           = $request->variations[$key]['price'];
                    $variation->original_price  = $request->variations[$key]['variation_original_price'] ?? 0.00;
                    $variation->save();
                }
            }

            foreach ($request->extras as $key => $no) {
                $extras             = new Extra();
                $extras->item_id    = $product->id;
                $extras->name       = $request->extras[$key]['name'];
                $extras->price      = $request->extras[$key]['price'];
                $extras->save();
            }

            if ($request->hasFile('item_image')) {
                $img = $request->file('item_image');
                $itemImage      = new ItemImages();
                $image          = 'item-' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(env('ASSETSPATHURL') . '/item', $image);
                $itemImage->item_id = $product->id;
                $itemImage->image = $image;
                $itemImage->save();
            }

            DB::commit();

            return new JsonResource([
                'message'   => 'Item Created Successfully!',
                'items'     => new ItemResource($product),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return new JsonResource([
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Find the product by slug
        $product = Item::where('id', $id)->first();

        $price          = $request->price;
        $original_price = $request->original_price;


        if ($request->has_variants == 1) {
            $variation_id = $request->variation_id;
            foreach ($request->variations as $key => $no) {
                if ($variation_id[$key] == "") {
                    $price          = $request->variation_price[$key];
                    $original_price = $request->variation_original_price[$key];
                    break;
                } else if ($variation_id[$key] != "") {
                    $price          = $request->variation_price[$key];
                    $original_price = $request->variation_original_price[$key];
                    break;
                }
            }
        }
        $product                        = Item::where('slug', $request->slug)->first();
        $product->cat_id                = $request->cat_id;
        $product->item_name             = $request->item_name;
        $product->item_price            = $price;
        $product->item_original_price   = $original_price;
        $product->slug                  = $product->slug;
        $product->has_variants          = $request->has_variants;
        $product->tax                   = $request->tax;
        $product->description           = $request->description;
        $product->start_time            = $request->start_time;
        $product->end_time              = $request->end_time;


        $product->update();
        if ($request->has_variants == 2) {
            Variants::where('item_id', $product->id)->delete();
        }
        if ($request->has_variants == 1) {
            foreach ($request->variations as $key => $no) {
                $variation_id = $request->variations[$key]['id'];
                if (@$variation_id[$key] == "") {
                    $variation = new Variants();
                    $variation->item_id = $product->id;
                    $variation->name = $product->item_name;
                    $variation->price = $request->variations[$key]['price'];
                    $variation->original_price = $request->variations[$key]['variation_original_price'];
                    $variation->save();
                } else if (@$variation_id[$key] != "") {
                    Variants::where('id', $variation_id)->update([
                        'price'             => $request->variations[$key]['price'],
                        'name'              => $request->variations[$key]['name'],
                        'original_price'    => $request->variations[$key]['variation_original_price']
                    ]);
                }
            }
        }

        // has extr
        if ($request->has_extras == 1) {
            foreach ($request->extras as $key => $no) {
                $extra_id       = $request->extras[$key]['id'] ?? null;
                if ($extra_id) {
                    $extra_exists   = Extra::where('id', $extra_id)->exists();
                    if ($extra_exists) {
                        $extra_exists->update(
                            [
                                'name' => $request->extras[$key]['name'],
                                'price' => $request->extras[$key]['price']
                            ]
                        );
                    }
                } else {
                    $extras = new Extra();
                    $extras->item_id = $product->id;
                    $extras->name = $request->extras[$key]['name'];
                    $extras->price = $request->extras[$key]['price'];
                    $extras->save();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return new JsonResource([
                'message'   => 'Item Not Found',
            ]);
        }

        Extra::where('item_id', $item->id)->delete();
        Variants::where('item_id', $item->id)->delete();
        $item->delete();

        return new JsonResource([
            'message'   => 'Item Deleted Successfully!',
        ]);
    }
}
