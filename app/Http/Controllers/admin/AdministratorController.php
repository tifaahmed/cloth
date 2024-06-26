<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

// Support
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\User;

class AdministratorController extends Controller
{
    public function index(){
        $administrators = User::where('id','!=',1)->whereIn('type',[1])->with('roles')->get();
        return view('admin.administrators.index', compact("administrators"));
    }
    public function create(){
        return view('admin.administrators.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'email'  => 'unique:users,email',
            'mobile' => 'unique:users,mobile',
        ], [
            'email.unique' => trans('messages.unique_email_required'),
            'mobile.unique' => trans('messages.unique_mobile_required'),
        ]);

        $user = User::create([
            'name'          => $request->name,
            'slug'          => rand(111111,999999),
            'email'         =>  $request->email,
            'mobile'        => $request->mobile,
            'image'         => 'default.png',
            'password'      => Hash::make($request->password),
            'login_type'    =>  "normal",
            'type'          =>  1,
            'allow_without_subscription' => 1,
            'is_verified' => 1,
            'is_available' => 1,
        ]);
        $user->syncRoles('admin');

        return redirect('admin/administrators')->with('success', trans('messages.success'));
    }
    public function edit(User $user){
        return view('admin.administrators.edit', compact("user"));
    }
    public function update(User $user,Request $request)
    {
        $request->validate([
            'email'  => 'unique:users,email,'.$user->id,
            'mobile' => 'unique:users,mobile,'.$user->id,
        ], [
            'email.unique' => trans('messages.unique_email_required'),
            'mobile.unique' => trans('messages.unique_mobile_required'),
        ]);

        $user = $user->update([
            'name' => $request->name,
            'email' =>  $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect('admin/administrators')->with('success', trans('messages.success'));
    }
}