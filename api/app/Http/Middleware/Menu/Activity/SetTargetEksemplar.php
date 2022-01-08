<?php

namespace App\Http\Middleware\Menu\Activity;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class SetTargetEksemplar
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
        if(in_array(auth()->user()->role,[
            User::ROLE_SALES,
            User::ROLE_KOTELE,
            User::ROLE_TELEMARKETING
        ])){
            return response()->json([
                "message" => "Unauthorized"
            ],401);
        }

        return $next($request);
    }
}
