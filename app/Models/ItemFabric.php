<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemFabric extends Model
{
    use HasFactory;
    protected $table='item_fabric';
    protected $fillable=['item_id','fabric_id'];
}
