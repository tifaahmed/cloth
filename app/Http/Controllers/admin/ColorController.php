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
        $colors = Color::get();
        return view('admin.color.index', compact("colors"));
    }
    public function create(Request $request)
    {
        return view('admin.color.create');
    }
    public function store(Request $request)
    {
        Color::create($request->all());
        return redirect('admin/colors')->with('success', trans('messages.success'));
    }
    public function edit(Color $color)
    {
        return view('admin.color.edit', compact("color"));
    }
    public function update(Color $color, Request $request)
    {
        $color->update($request->all());
        return redirect('admin/colors')->with('success', trans('messages.success'));
    }
}
