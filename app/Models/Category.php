<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'categories';

    protected $fillable = [
        'vendor_id',
        'is_available',
        'reorder_id',
        'name'
    ];

    public $translatable = ['name'];

    public $searchable = [
        'id',
        'name',
        'vendor_id',
        'is_available',
    ];

    public $scopes = [
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function getNameTranslatedAttribute()
    {  // name_translated NameTranslated
        return   $this->name;
    }

    // hasMany
    public function items():HasMany
    {
        return $this->hasMany(Item::class, 'cat_id');
    }

    // belongsTo
    public function vendor():BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function reorder():BelongsTo
    {
        return $this->belongsTo(Order::class, 'reorder_id');
    }

    // IsAvailable
    public function scopeIsAvailable($query){
        return $query->where('is_available', 1);
    }
    // IsDeleted
    public function scopeIsDeleted($query){
        return $query->where('is_deleted', 2);
    }
    // AuthVendor
    public function scopeAuthVendor($query){
        return $query->where('vendor_id', Auth::id());
    }
}
