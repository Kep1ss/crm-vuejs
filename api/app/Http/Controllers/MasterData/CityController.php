<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Helpers\FormatResponse;
use App\Http\Requests\CityRequest;
use App\Traits\ConstructControllerSuperAdminTrait;

class CityController extends Controller
{
    use ConstructControllerSuperAdminTrait;

     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = City::query();

        $data->select("id","name","is_city","province_id");

        $data->with(["province" => function($q){
            $q->select("id","name");
        }]);

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        
            
        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("name","like","%".$request->search."%");                
            });

            if(!$request->filled("province_id")){
                $data->orWhereHas("province",function($q) use ($request){        
                    $q->where("name","like","%".$request->search."%");                   
                });
            }
        }   
        
        if($request->filled("is_city")){
            $data->where("is_city",intval($request->is_city));
        }

        if($request->filled("province_id")){
            $data->where("province_id",$request->province_id)
                ->whereNotNull("code");
        }

        $data = $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

        if(!$request->filled("all")){
            $data = $data->paginate($request->per_page ?? 10);
        }else{
            $data = $data->get();
        }                   
    
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->indexFilter());                
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        try{    
            \DB::beginTransaction();
            
            $city = City::create($request->validated());

            activity()
                ->performedOn($city)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $city->name,
                    'id' => $city->id,
                    'table' => 'cities'
                ])
                ->log('Created Data');

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, City $city)
    {
        try{    
            \DB::beginTransaction();
                        
            $city->update($request->validated());

            activity()
                ->performedOn($city)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $city->name,
                    'id' => $city->id,
                    'table' => 'cities'
                ])
                ->log('Upadated Data');

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
