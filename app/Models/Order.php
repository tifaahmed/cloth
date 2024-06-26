<?php

namespace App\Models;

use App\Enums\OrderNotificationStatusEnums;
use App\Enums\OrderStatusEnums;
use App\Enums\OrderTypeEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Scopes
use App\Scopes\ScopeAuthVendor; // auth_vendor
// Enums
use App\Enums\PaymentTypeEnums;
use App\Helpers\helper;

class Order extends Model
{
    use HasFactory, ScopeAuthVendor;

    protected $table = 'orders';

    protected $fillable = [
        'vendor_id', //int
        'user_id', //int
        'order_number', //varchar
        'payment_type', //int
        'payment_id', //int
        'sub_total', //varchar
        'tax', //varchar
        'grand_total', //varchar
        'order_type', //int ex(1 = Delivery (Dine_in - POS) , 2 = Pickup (TakeAway -POS) 3 = Dine In (Front)) Default(1)
        'table_id', //int

        'address', //varchar
        'pincode', //varchar
        'building', //varchar
        'landmark', //varchar

        'block',
        'street',
        'house_num',

        'delivery_area', //varchar
        'delivery_charge', //varchar

        // order discount
        'discount_amount', //varchar
        'couponcode', //varchar

        // customer details
        'order_notes', //text
        'customer_name', //text
        'customer_email', //text
        'mobile', //text


        'delivery_date', //varchar
        'delivery_time', //varchar
        'order_from', //varchar

        'status', //int ex(1 = pending , 2 = processing , 3 = deliverd , 4 = cancelled)
        'is_notification', //int

        'latitude', //varchar
        'longitude', //varchar

        'branch_id'
    ];
    // public $appends = [
    //     'created_at_date_format', 'delivery_date_format', 'payment_type_name'
    // ];

    public $searchable = [
        'id',
        'status',
        'order_type',
    ];

    public $scopes = [];

    // relations
    public function vendorinfo()
    {
        return $this->hasOne('App\Models\User', 'id', 'vendor_id')->select('id', 'name');
    }
    public function tableqr()
    {
        return $this->hasOne('App\Models\TableQR', 'id', 'table_id');
    }
    public function paymentInfo()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }


    // get Attribute
    public function getCreatedAtDateFormatAttribute()
    { // created_at_date_format CreatedAtDateFormat
        return helper::date_format($this->created_at);
    }
    public function getDeliveryDateFormatAttribute()
    { // delivery_date_format DeliveryDateFormat
        return helper::date_format($this->delivery_date);
    }
    public function getPaymentTypeNameAttribute()
    {   //payment_type_name PaymentTypeName
        return PaymentTypeEnums::getConstantByName($this->payment_type);
    }
    public function getStatusNameAttribute()
    {   //status_name StatusName
        return OrderStatusEnums::getConstantByName($this->status);
    }
    public function getNotificationStatusAttribute()
    {   //notification_status NotificationStatus
        return OrderNotificationStatusEnums::getConstantByName($this->is_notification);
    }
    public function getTypeNameAttribute()
    {   //type_name TypeName
        return OrderTypeEnums::getConstantByName($this->order_type);
    }
}
