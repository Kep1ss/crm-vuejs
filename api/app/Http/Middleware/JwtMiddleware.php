<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                $response['message'] = 'Token is blacklist';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response['message'] = 'Token is expired';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response['message'] = 'Token is invalid';
            }else{
                $response["message"] = 'Token not found';
            }

            return response()->json($response,401);
        }

        $token = request()->header("Authorization");
        $isNotAccessOtherLogin = request()->header('isNotAccessOtherLogin') ?? null;
        $isAccessOtherLogin = md5(env("JWT_SECRET","true"));
        // $isAccessOtherLogin = md5(env("JWT_SECRET","true").".".$token);

        if($isNotAccessOtherLogin != $isAccessOtherLogin){
            if(
                in_array(request()->route()->getName(),
                ["auth.logout","user.index","user.update","user.destory","user.store"])
            ){
                return response()->json([
                    "error" => "Unauthorized"
                ],401);
            }
        }
        
        return $next($request);
    }
}
