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
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(LoginRequest $request)
    {
        if (! $token = auth()->claims([
                'iss' => Null
            ])->attempt($request->validated())){
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        if(auth()->user()->deleted_at){
            auth()->logout();
            return response()->json([
                "message" => "Akun anda telah terhapus",
            ],500);
        }

        return $this->respondWithToken($token);
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
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try{
            return $this->respondWithToken(auth()->claims([
                'iss' => Null
            ])->refresh());
        }catch(\Exception $e){
            \Log::channel("coex")->info($e->getMessage());

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                $response['message'] = 'Token is blacklist';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response['message'] = 'Token is expired but when refresh failed';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response['message'] = 'Token is invalid';
            }else{
                $response['message'] = 'Token Not Found';
            }

            return response()->json($response,401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'isNotAccessOtherLogin' => md5(env("JWT_SECRET","true")),    
            // 'isNotAccessOtherLogin' => md5(env("JWT_SECRET","true")."."."Bearer ".$token),    
            'expires_in' => auth()->factory()->getTTL() * 60
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
