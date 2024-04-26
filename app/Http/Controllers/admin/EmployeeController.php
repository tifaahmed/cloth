<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\JobTypeEnums;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::whereNotIn('type',[1,2] )->where('user_id',auth()->id())->latest()->get();
        return view('admin.employees.index', compact('employees'));
    }
    public function create(Request $request)
    {
        $JobTypeEnums  = JobTypeEnums::all();
        return view('admin.employees.create', compact('JobTypeEnums'));
    }
    public function edit(User $employee)
    {
        $JobTypeEnums  = JobTypeEnums::all();
        return view('admin.employees.edit', compact('employee','JobTypeEnums'));
    }
    public function update($id,Request $request)
    {
        $user = User::where('id', $id)->first();
         
        $user->syncRoles( [JobTypeEnums::getRoleById($request->Job_type_id)] );

        $user->user_id      = auth()->id();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->mobile       = $request->mobile;
        $user->login_type   = "email";
        $user->type         = $request->Job_type; ;
        if ($request->password) {
            $user->password = Hash::make($request->password) ;
        }
        if ($request->has('profile')) {
            if (Auth::user()->image != "default.png" && file_exists(storage_path('app/public/admin-assets/images/profile/' . Auth::user()->image))) {
                unlink(storage_path('app/public/admin-assets/images/profile/' . Auth::user()->image));
            }
            $edit_image = $request->file('profile');
            $profileImage = 'profile-' . uniqid() . "." . $edit_image->getClientOriginalExtension();
            $edit_image->move(storage_path('app/public/admin-assets/images/profile/'), $profileImage);
            $user->image = $profileImage;
        }
        $user->update();

        return redirect( route('employees.index') )->with('success', trans('messages.success'));
    }

    public function store(Request $request)
    {
        $profileImage = null;
        if ($request->has('profile')) {
            $image = $request->file('profile');
            $profileImage = 'profile-' . uniqid() . "." . $image->getClientOriginalExtension();
            $image->move(storage_path('app/public/admin-assets/images/profile/'), $profileImage);
        }
        $user = User::create([
            'email'     =>  $request->email,
            'name'      =>  $request->name,
            'mobile'    =>  $request->mobile,
            'image'     =>  $profileImage,
            'login_type'=>  "email",
            'password'  =>  Hash::make($request->password),
            'type'      =>  $request->Job_type_id,
            'user_id'   =>  auth()->id()
        ]);
        $user->syncRoles(JobTypeEnums::getRoleById($request->Job_type_id) );
        return redirect( route('employees.index') )->with('success', trans('messages.success'));
    }
    public function destroy(User $employee)
    {
        $employee  = $employee->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }

}
