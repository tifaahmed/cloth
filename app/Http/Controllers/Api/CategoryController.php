<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

// lInterfaces
use App\Repository\CategoryRepositoryInterface as ModelInterface;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct(private ModelInterface $modelInterface)
    {
    }

    public function count()
    {
        // Define an array mapping status codes to count attributes
        $categoryStatuses = [
            'categories_count'      => DB::raw('COUNT(*) AS categories_count'),
            'is_available_count'    => DB::raw('SUM(CASE WHEN is_available = "1" THEN 1 ELSE 0 END) AS is_available_count'),
            'not_available_count'   => DB::raw('SUM(CASE WHEN is_available = "2" THEN 1 ELSE 0 END) AS not_available_count'),
        ];

        // Perform the query using the defined status counts
        $categoryCounts = Category::selectRaw(implode(', ', $categoryStatuses))
            ->first();

        // Return the order counts as an associative array
        return array_map(function ($count) {
            return $count ?? 0; // Replace null values with 0 to ensure consistent output
        }, $categoryCounts->toArray());
    }

    public function all(Request $request)
    {

        if ($request->filter) {
            $filter = [];
            foreach ($request->filter as $key => $value) {
                $filter[$key] = $value;
            }
            $filter['vendor_id'] = auth()->user()->vendor_id;
            $request->request->add(['filter' => $filter]);
        } else {
            $request->request->add(
                [
                    'filter' => [
                        'vendor_id' => auth()->user()->vendor_id,
                    ]
                ]
            );
        }
        try {
            $modal =  $this->modelInterface->all();
            return CategoryResource::collection($modal);
        } catch (\Exception $e) {
            return $this->MakeResponseErrors(
                [$e->getMessage()],
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
                $filter[$key] = $value;
            }
            $filter['vendor_id'] = auth()->user()->vendor_id;
            $request->request->add(['filter' => $filter]);
        } else {
            $request->request->add(
                [
                    'filter' => [
                        'vendor_id' => auth()->user()->vendor_id,
                    ]
                ]
            );
        }
        $categories = $this->modelInterface->collection($request->per_page ?? 10);


        // $categories     = Category::query()
        //     ->IsAvailable()
        //     ->IsDeleted()
        //     ->latest()
        //     ->paginate($pageSize);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();

        if ($request->hasFile('image')) {
            $image = 'category-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/category/'), $image);
            $category->image = $image;
        }

        $category->vendor_id = auth()->id();
        $category->is_available = $request->is_available;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name['en'] . ' ', '-') . '-' . Str::random(5);;
        $category->save();

        return new JsonResponse([
            'status'        => 'created',
            'categories'    => new CategoryResource($category)
        ]);
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
    public function update(UpdateCategoryRequest $request, $slug)
    {
        $category   = Category::where('slug', $slug)->first();
        if (!$category) return response()->json(['error' => 'Category not found.'], 404);

        if ($category->vendor_id != auth()->id()) return response()->json(['error' => 'Category not found.'], 404);

        if ($request->hasFile('image')) {
            if (file_exists(storage_path('app/public/admin-assets/images/category/' . $category->image)))
                unlink(storage_path('app/public/admin-assets/images/category/' . $category->image));
            $image              = 'category-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/category/'), $image);
            $category->image    = $image;
        }
        $category->slug         = Str::slug($request->name['en'] . ' ', '-') . '-' . Str::random(5);
        $category->update($request->all());
        $category->save();
        return new JsonResponse([
            'status'        => 'updated',
            'category'      => new CategoryResource($category)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!empty($category)) {
            Item::where('cat_id', $category->id)->update(['is_available' => 2]);
            $category->is_deleted = 1;
            $category->save();
            return new JsonResponse([
                'status'        => 'deleted',
                'message'       => 'Category deleted successfully',
            ]);
        } else {
            return new JsonResponse([
                'status'        => 'error',
                'message'       => 'Something went wrong',
            ]);
        }
    }
}
