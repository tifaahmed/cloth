<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ColorController extends Controller
{
    public function index(Request $request)
    {
        $allcategories = Category::where('vendor_id', Auth::user()->id)->where('is_deleted', 2)->orderby('reorder_id')->get();
        return view('admin.category.category', compact("allcategories"));
    }
}
