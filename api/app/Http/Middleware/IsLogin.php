<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        try{ 
            $token = PersonalAccessToken::findToken($request->get('token'));

            auth()->loginUsingId($token->tokenable_id);

            return $next($request);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Unauthorized"
            ],401);
        }
    }
}
