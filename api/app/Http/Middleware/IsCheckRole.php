<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class IsCheckRole
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
        $routeName = explode(".",$request->route()->getName());        
            
        /* MODULE SETTING */
        if(in_array("setting",$routeName)){
            if(in_array("announcement",$routeName)){
                if(!in_array(auth()->user()->role,[
                    User::ROLE_MANAGER_NASIONAL,
                    User::ROLE_MANAGER_AREA,
                    User::ROLE_ADMIN_NASIONAL,
                    User::ROLE_ADMIN_AREA,
                    
                    User::ROLE_SUPERADMIN
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }            
            }
        }

        /* MODULE MASTER DATA */
        if(in_array("master",$routeName)){
            if(in_array("account",$routeName)){
                if(in_array(auth()->user()->role,[
                    User::ROLE_SALES,
                    User::ROLE_TELE_MARKETING,
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("province",$routeName) && !in_array("index",$routeName)){
                if(!in_array(auth()->user()->role,[
                    User::ROLE_KOTELE,
                    User::ROLE_MANAGER_NASIONAL,
                    User::ROLE_ADMIN_NASIONAL,
        
                    User::ROLE_SUPERADMIN
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("district",$routeName) && !in_array("index",$routeName)){
                if(in_array(auth()->user()->role,[
                    User::ROLE_SALES,
                    User::ROLE_MANAGER_NASIONAL,
                    User::ROLE_ADMIN_NASIONAL,
                    User::ROLE_KOTELE,
                    User::ROLE_TELE_MARKETING
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("city",$routeName) && !in_array("index",$routeName)){
                if(in_array(auth()->user()->role,[
                    User::ROLE_SALES,
                    User::ROLE_MANAGER_NASIONAL,
                    User::ROLE_ADMIN_NASIONAL,
                    User::ROLE_KOTELE,
                    User::ROLE_TELE_MARKETING
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }
        }

        /* MODULE ACTIVITY */
        if(in_array("actvity",$routeName)){
            if(in_array("set-area-sales",$routeName)){
                if(!in_array(auth()->user()->role,[
                    User::ROLE_SPV,
                    
                    User::ROLE_SUPERADMIN,
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }        
            }

            if(in_array("set-target-customer",$routeName)){
                if(!in_array(auth()->user()->role,[
                    User::ROLE_SPV,
        
                    User::ROLE_SUPERADMIN,
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("set-target-eksemplar",$routeName)){
                if(in_array(auth()->user()->role,[
                    User::ROLE_SALES,
                    User::ROLE_KOTELE,
                    User::ROLE_TELE_MARKETING
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("set-target-telemarketing",$routeName)){
                if(!in_array(auth()->user()->role,[
                    User::ROLE_KOTELE,
                    
                    User::ROLE_SUPERADMIN,
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("input-visit",$routeName)){
                if(in_array(auth()->user()->role,[
                    User::ROLE_MANAGER_NASIONAL,
                    User::ROLE_ADMIN_NASIONAL,
                    User::ROLE_KOTELE,
                    User::ROLE_TELE_MARKETING
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }

            if(in_array("input-activity-telemarketing",$routeName)){
                if(!in_array(auth()->user()->role,[        
                    User::ROLE_TELE_MARKETING,
                    User::ROLE_SUPERADMIN 
                ])){
                    return response()->json([
                        "message" => "Unauthorized"
                    ],401);
                }
            }
        }

        return $next($request);
    }
}
