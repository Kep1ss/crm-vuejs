<?php

namespace App\Http\Controllers\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\FormatResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\{
    SettingRequest,
    SettingLogoRequest
};

class SettingController extends Controller
{
    /**
     * Display a listing of the resource 
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function index(){
        return response()->json(
            Setting::select("name","value")
                ->whereIn("name",["email","company_name","phone","address","logo","header_color","dapodik_url","dapodik_school_id"])
                ->get()
        );
    }

    /**
     *  Update all data setting resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequest $request){
        try{
            \DB::beginTransaction();

            foreach($request->validated() as $key => $item){
                Setting::where("name",$key)
                    ->update([
                        "value" => $item
                    ]);
            }

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'table' => 'settings'                  
                ])
                ->log('Updated Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     *  Update logo setting resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(SettingLogoRequest $request){
       try{
            \DB::beginTransaction();

            $setting = Setting::where("name","logo")->first();

            $fileName = "default.jpeg";

            if($setting->value_original && file_exists(public_path('/images/logos/'.$setting->value_original))){
                unlink(public_path('/images/logos/'.$setting->value_original));
            }

            \Image::make(request()->file("logo"))->save(public_path("/images/logos/".$fileName));

            $setting->update([
                "value" => $fileName
            ]);

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'table' => 'settings'                  
                ])
                ->log('Updated Logo');

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
