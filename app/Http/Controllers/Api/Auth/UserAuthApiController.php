<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginApiRequest;
use App\Http\Requests\Api\Auth\RegisterApiRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserAuthApiController extends Controller
{
    public function register(RegisterApiRequest $request)
    {
        try {
            $request['slug']            = Str::slug($request['slug']);
            $request['password']        = Hash::make($request['password']);
            $request['remember_token']  = Str::random(10);

            // Check if login_type exists, if not, set it to "normal"
            if (!isset($request['login_type'])) {
                $request['login_type'] = 'normal';
            }

            $user  = User::create($request->all());
            $token = auth()->user()->getToken();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => new UserResource($user),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()], 422);
        }
    }

    public function login(LoginApiRequest $request)
    {
        $user   = User::where('email', $request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            if (Auth()->attempt(['email' => $user->email, 'password' => $request->password])) {
                $token = $user->createToken($user->email)->plainTextToken;
                return new JsonResponse([
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'user' => new UserResource($user),
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }
    }

    public function forget_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        } else {
            return [
                'status' => 'Failed'
            ];
        }
    }

    public function reset_password(Request $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password'          => Hash::make($request->password),
                    'remember_token'    => Str::random(60),
                ])->save();
                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return new JsonResponse([
                'message' => 'Password reset successfully'
            ]);
        }
        return new JsonResponse([
            'message' => __($status)
        ], 500);
    }

    public function logout(Request $request)
    {
       auth()->user()->token()->revoke();
        return new JsonResponse([
            'status' => 'success',
            'message' => 'User Logged out',
        ]);
    }
    public function profile(Request $request)
    {
       auth()->user();
        return new JsonResponse([
            'status' => 'success',
            'data' => new UserResource(auth()->user()),
        ]);
    }
}
