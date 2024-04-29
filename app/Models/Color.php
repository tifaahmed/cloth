<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Color extends Model
{
    use HasFactory, HasTranslations;
    protected $table='colors';

    protected $fillable=['name','code'];
    public $translatable = ['name'];

}
