<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemColor extends Model
{
    use HasFactory;
    protected $table='item_color';
    protected $fillable=['item_id','color_id'];
}
