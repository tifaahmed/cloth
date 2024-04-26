<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartRequest;

// Resources
use App\Http\Resources\Collections\Cart\CartCollection;
use App\Http\Resources\Cart\CartResource;

use App\Models\Cart;
use App\Models\Item;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response ;

use Illuminate\Support\Facades\Session;

// lInterfaces
use App\Repository\CartRepositoryInterface as ModelInterface;

class CartController extends Controller
{
    private $sessionId;
    public function __construct(private ModelInterface $modelInterface)
    {
        $this->sessionId = Session::getId();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        try {
            $request->request->add(
                [
                    'filter' =>[
                        'auth_user' => auth()->id(),
                    ]
                ]
            );
            $modal =    $this->modelInterface->all()    ;
            return new CartCollection($modal);
        } catch (\Exception $e) {
            return $this -> MakeResponseErrors(
                [$e->getMessage()  ] ,
                'Errors',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $request->request->add(
                [
                    'filter' =>[
                        'auth_user' => auth()->id(),
                    ]
                ]
            );
            $modal =    $this->modelInterface->collection($request->per_page ?? 10)    ;
            return new CartCollection($modal);
        } catch (\Exception $e) {
            return $this -> MakeResponseErrors(
                [$e->getMessage()  ] ,
                'Errors',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {


        $existingCartItem = Cart::where(
            [
                'item_id'=> $request->item_id,
                'user_id' => auth()->id()
            ])
            ->first();

        if ($existingCartItem) {
            Cart::increaseItemQuantity($this->sessionId, $item->id, auth()->id());

        } else {
            Cart::create([
                'session_id'        => $this->sessionId,
                'vendor_id'         => auth()->id(),
                'item_id'           => $item->id,
                'item_name'         => $item->item_name,
                'item_image'        => helper::image_path($item->item_image->image_name),
                'item_price'        => $item->item_price,

                'extras_id'         => $request->extras_id,
                'extras_name'       => $request->extras_name,
                'extras_price'      => $request->extras_price,

                'price'             => $item->item_price,
                'qty'               => 1,

                'variants_id'       => $request->variants_id,
                'variants_name'     => $request->variants_name,
                'variants_price'    => $request->variants_price,
            ]);
        }

        // Return the updated cart details
        return $this->getCartResponse($this->sessionId, auth()->id());
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    public function increaseItemQuantity(Request $request)
    {
        $item       = $this->checkIfItemExists($request->item_id);

        // Call the increaseItemQuantity method to increase item quantity
        Cart::increaseItemQuantity($item->id, auth()->id(), $this->sessionId);


        // Return the updated cart details
        return $this->getCartResponse($this->sessionId, auth()->id());
    }

    public function decreaseItemQuantity(Request $request)
    {
        $item       = $this->checkIfItemExists($request->item_id);

        // Call the decreaseItemQuantity method to decrease item quantity
        Cart::decreaseItemQuantity($item->id, auth()->id(), $this->sessionId);

        // Return the updated cart details
        return $this->getCartResponse($this->sessionId, auth()->id());
    }


    public function destroy(Request $request,$id)
    {
        $item = $this->checkIfItemExists($id);

        // Call the removeItem method to delete the cart item
        Cart::removeItem($this->sessionId, $item->id, auth()->id());

        // Return the updated cart details
        return $this->getCartResponse($this->sessionId, auth()->id());
    }

    private function getCartResponse($sessionId, $vendorId)
    {
        $cartItems = Cart::where('session_id', $sessionId)
            ->where('vendor_id', $vendorId)
            ->get();

        $totalPrice = $cartItems->sum('price');

        return response()->json([
            'cart_items' => CartResource::collection($cartItems),
            'total_price' => $totalPrice,
        ]);
    }

    public function checkIfItemExists($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return new JsonResponse([
                'message' => 'Item Not Found',
            ]);
        }
        return $item;
    }
}
