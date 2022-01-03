<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    LoginRequest,
    ForgotPasswordRequest,
    ResetPasswordRequest
};

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? "email" : "username";

        if (!auth()->attempt([
            $field => $request->email,
            "password" => $request->password
        ])) {
            return response()->json([
                "message" => "Unauthorized"
            ],401);
        }

        return response()->json([
            'access_token' => auth()->user()->createToken('access_token')->plainTextToken
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request){
        try{
            \DB::beginTransaction();

            $user = User::where("email",$request->email)->firstOrFail();

            $user->update([
                "remember_token" => \Str::random(100)
            ]);

            \Notification::send($user,new \App\Notifications\ResetPassword($user));

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function resetPassword(ResetPasswordRequest $request){
        try{
            \DB::beginTransaction();

            $user = User::where("remember_token",$request->token)
                ->where("email",$request->email)
                ->firstOrFail();

            $user->update([
                "password" => \Hash::make($request->password),
                "remember_token" => Null
            ]);

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }
}
