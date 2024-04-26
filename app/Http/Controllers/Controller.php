<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Traits\ResponsesTrait;

use Illuminate\Routing\Controller as BaseController;

// Traits
    use App\Http\Controllers\Traits\OrderDiscount;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,
    OrderDiscount , ResponsesTrait
    ;
}
