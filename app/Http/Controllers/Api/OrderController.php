<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\UpdateOrderRequest;
use App\Http\Requests\Api\Order\UpdateOrderStatusRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Coupons;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Illuminate\Support\Facades\URL;
use App\Repository\OrderRepositoryInterface as ModelInterface;

class OrderController extends Controller
{
    public function __construct(private ModelInterface $modelInterface)
    {
    }


    public function count()
    {
        // Define an array mapping status codes to count attributes
        $statusCounts = [
            'orders_count'      => DB::raw('COUNT(*) AS orders_count'),
            'pending_count'     => DB::raw('SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) AS pending_count'),
            'processing_count'  => DB::raw('SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) AS processing_count'),
            'completed_count'   => DB::raw('SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) AS completed_count'),
            'cancelled_count'   => DB::raw('SUM(CASE WHEN status = "4" THEN 1 ELSE 0 END) AS cancelled_count'),
            'confirmed_count'   => DB::raw('SUM(CASE WHEN status = "5" THEN 1 ELSE 0 END) AS confirmed_count'),
            'done_count'        => DB::raw('SUM(CASE WHEN status = "6" THEN 1 ELSE 0 END) AS done_count'),
            'in_delivery_count' => DB::raw('SUM(CASE WHEN status = "7" THEN 1 ELSE 0 END) AS in_delivery_count'),
            'rejected_count'    => DB::raw('SUM(CASE WHEN status = "8" THEN 1 ELSE 0 END) AS rejected_count'),
        ];

        // Perform the query using the defined status counts
        $orderCounts = Order::selectRaw(implode(', ', $statusCounts))
            ->where('vendor_id', auth()->id())
            ->first();

        // Return the order counts as an associative array
        return array_map(function ($count) {
            return $count ?? 0; // Replace null values with 0 to ensure consistent output
        }, $orderCounts->toArray());
    }

    public function index(Request $request)
    {
        $orders = $this->modelInterface->collection($request->per_page ?? 10);
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $host = $_SERVER['HTTP_HOST'];
        if ($host  ==  env('WEBSITE_HOST')) {
            $vendorInfo = User::where('id', $request->vendor_id)->first();
            $vData      = $request->vendor_id;
        } else {
            // if the current host doesn't contain the website domain (meaning, custom domain)
            $vendorInfo = Settings::where('custom_domain', $host)->first();
            $vData      = $vendorInfo->vendor_id;
        }

        $payment_id = "";
        $user_id    = "";
        $session_id = "";
        if (Auth::user() && Auth::user()->type == 3) {
            $user_id    = Auth::user()->id;
        } else {
            $session_id = session()->getId();
            $user_id    = null;
        }

        $payment_id     = $request->payment_id;
        if ($request->payment_type == "stripe") {
            $getStripe  = Payment::select('environment', 'secret_key', 'currency')
                ->where('payment_name', 'Stripe')
                ->where('vendor_id', $vData)
                ->first();

            $sKey       = $getStripe->secret_key;
            Stripe::setApiKey($sKey);

            $customer   = Customer::create(
                array(
                    'email'     => $request->customer_email,
                    'source'    =>  $request->stripeToken,
                    'name'      => $request->customer_name,
                )
            );

            $charge = Charge::create(
                array(
                    'customer'      => $customer->id,
                    'amount'        => $request->grand_total * 100,
                    'currency'      => $getStripe->currency,
                    'description'   => 'Store-Mart',
                )
            );

            if ($request->payment_id == "") {
                $payment_id = $charge['id'];
            } else {
                $payment_id = $request->payment_id;
            }
        }

        $orderresponse = helper::createorder(
            $request->vendor_id,
            $user_id,
            $session_id,
            $request->payment_type,
            $payment_id,

            $request->customer_email,
            $request->customer_name,
            $request->customer_mobile,
            $request->notes,

            $request->stripeToken,
            $request->grand_total,
            $request->delivery_charge,

            $request->address,
            $request->building,
            $request->landmark,
            $request->block,
            $request->street,
            $request->house_num,
            $request->latitude,
            $request->longitude,
            $request->branch_id,
            $request->postal_code,

            $request->discount_amount,
            $request->sub_total,
            $request->tax,

            $request->delivery_time,
            $request->delivery_date,
            $request->delivery_area,

            $request->couponcode,
            $request->order_type,
            $request->table
        );
        if (isset($orderresponse['status']) && $orderresponse['status'] == -1) {
            $url = URL::to(@$vendorInfo->slug . "/cart");
            return response()->json(['status' => 0, 'message' => trans('messages.cart_empty'), "url" =>  $url]);
        }

        if ($request->couponcode != null) {
            $promocode = Coupons::where('code', $request->couponcode)->where('vendor_id', $vData)->first();
            $promocode->limit = $promocode->limit - 1;
            $promocode->save();
        }

        $url = URL::to(@$vendorInfo->slug . "/success/" . $orderresponse);

        return response()->json(['status' => 1, 'message' => trans('messages.order_placed'), "order_number" => $orderresponse, "url" =>  $url], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        if (!$order || $order->vendor_id != auth()->id())
            return new JsonResponse([
                'message' => 'Order not found',
            ]);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::find($id);
        $order->update(['status' => $request->status]);
        return new JsonResponse([
            'success' => 'Order updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
