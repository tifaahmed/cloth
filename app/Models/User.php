<?php

namespace App\Models;

use App\Enums\PaymentTypeEnums;
use App\Enums\VendorTypeEnums;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'mobile',
        'image',
        'password',
        'google_id',
        'facebook_id',
        'login_type',
        'type', //1=Admin,2=vendor,3=driver,4=User/Customer
        'description',
        'token',
        'city_id',
        'area_id',
        'plan_id',
        'purchase_amount',
        'purchase_date',
        'available_on_landing',
        'payment_id',
        'payment_type',
        'free_plan',
        'is_delivery',
        'allow_without_subscription',
        'is_verified',
        'is_available',
        'remember_token',
        'license_type',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeUserType($query,$value)
    {   // UserType
        return $query->where('type',$value);
    }

    public function getTypeNameAttribute()
    {   //type_name TypeName
        return VendorTypeEnums::getConstantByName($this->order_type);
    }

    public function getPaymentTypeNameAttribute()
    {   //payment_type_name PaymentTypeName
        return PaymentTypeEnums::getConstantByName($this->payment_type);
    }
    //vendor_id VendorId
    public function getVendorIdAttribute()
    {   
        return $this->user_id ? $this->user->id : $this->id ;
    }

    // relations
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    public function deliveryAreas()
    {
        return $this->hasMany(DeliveryArea::class, 'vendor_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'city_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function setting()
    {
        return $this->hasOne(Settings::class, 'vendor_id');
    }
    public function workHours()
    {
        return $this->hasMany(Timing::class, 'vendor_id');
    }

    // get token
    public function getToken( )  {
        return $token = $this->createToken($this->email);
    }
    // public function sendPasswordResetNotification($token)
    // {
    //     $url = asset('api/auth/reset-password?token='.$token);
    //     $data = [] ;
    //     $data += ['url' => $url];
    //     $data += ['pin_code' => $this->pin_code];

    //     $this->notify(new ResetPasswordNotification($data));
    // }
    // public function sendActiveEmailNotification()
    // {
    //     $data = ['pin_code' => $this->pin_code];
    //     $this->notify(new ActiveEmailNotification($data));
    // }

}
