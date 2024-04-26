<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ScopeAuthVendor; // auth_vendor
use App\Scopes\ScopePriceFilter;
use App\Scopes\ScopeTimeFilter; // time_filter
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Item extends Model
{
    use HasFactory,
        ScopeAuthVendor,
        ScopeTimeFilter,
        ScopePriceFilter,
        HasTranslations;

    protected $table = 'items';
    protected $fillable = [
        'vendor_id',
        'reorder_id',
        'cat_id',
        'item_name',
        'description',
        'item_price',
        'item_original_price',
        'image',
        'tax',
        'slug',
        'is_available',
        'has_variants',

        'start_time',
        'end_time',
    ];
    public $searchable_exact = [
        'id',
        'cat_id',
        'vendor_id',
        'is_available'
    ];
    public $searchable = [
        'item_name',
        'slug',
    ];
    public $scopes = [
        'price_range',
    ];

    public $translatable = ['item_name', 'description'];

    // Accessors
    // title Title
    public function getTitleAttribute()
    {
        $category_name =  $this->category_info ? $this->category_info->name : null;
        return $category_name . ' / ' . $this->item_name;
    }
    // start_time_format StartTimeFormat
    public function getStartTimeFormatAttribute()
    {
        return  $this->start_time ?  date('g:i A', strtotime($this->start_time)) : null;
    }
    public function getEndTimeFormatAttribute()
    {  // end_time_format EndTimeFormat
        return  $this->end_time ?  date('g:i A', strtotime($this->end_time)) : null;
    }

    public function getTitleTranslatedAttribute()
    {  // title_translated TitleTranslated
        return   $this->item_name;
    }
    public function getDescriptionTranslatedAttribute()
    {  // description_translated DescriptionTranslated
        return   $this->description;
    }


    // hasMany
    public function extras()
    {
        return $this->hasMany('App\Models\Extra', 'item_id', 'id')->select('id', 'name', 'price', 'item_id');
    }
    public function variation()
    {
        return $this->hasMany('App\Models\Variants', 'item_id', 'id')->select('id', 'item_id', 'name', 'price', 'original_price');
    }
    // hasOne
    public function category_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
    public function item_image()
    {
        return $this->hasOne('App\Models\ItemImages', 'item_id', 'id')->select('item_images.id', 'item_images.image AS image_name', 'item_images.item_id', \DB::raw("CONCAT('" . url(env('ASSETSPATHURL') . 'item/') . "/', item_images.image) AS image_url"));
    }

    // belongTo
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id')->select('id', 'name');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'reorder_id');
    }

    // belongsToMany
    public function coupons()
    {
        return $this->belongsToMany(Coupons::class, CouponItem::class, 'item_id', 'coupon_id')
            ->using(CouponItem::class);
    }
}
