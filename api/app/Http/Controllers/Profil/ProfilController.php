<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{
    ProfilRequest,
    ProfilPasswordRequest
};
use App\Helpers\FormatResponse;
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    public function update(ProfilRequest $request){
    	try{
    		\DB::beginTransaction();

    		auth()->user()->update($request->validated() + [
                "username" => Str::slug($request->username)
            ]);

    		\DB::commit();
    		return response()->json([
    			"status" => true,
    			"user" => auth()->user()
    		]);
    	}catch(\Exception $e){
    		\DB::rollback();
            return FormatResponse::failed($e);
    	}
    }

    public function password(ProfilPasswordRequest $request){
        try{
            \DB::beginTransaction();

            throw_if(
                !\Hash::check($request->old_password,auth()->user()->password),
                new \Exception("Password lama salah",422)
            );

            auth()->user()->update(["password" => \Hash::make($request->password)]);

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
