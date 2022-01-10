<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Helpers\FormatResponse;
use App\Http\Requests\DistrictRequest;
use App\Traits\{
    ConstructControllerSuperAdminTrait
};

class DistrictController extends Controller
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

        $data = District::query();

        $data->select("id","name","city_id");

        $data->with(["city" => function($q){
            $q->select("id","name","is_city","province_id") 
                ->with(["province" => function($qp){
                    $qp->select("id","name");
                }]);
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

            if(!$request->filled("city_id")){
                $data->orWhereHas("city",function($q) use ($request){
                    $q->where("name","like","%".$request->search."%");
                });            

                $data->orWhereHas("city.province",function($q) use ($request){
                    $q->where("name","like","%".$request->search."%");
                });
            }
        }    

        if($request->filled("city_id")){
            $data->where("city_id",$request->city_id)
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
    public function store(DistrictRequest $request)
    {
        try{    
            \DB::beginTransaction();
            
            $district = District::create($request->validated());

            activity()
                ->performedOn($district)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $district->name,
                    'id' => $district->id,
                    'table' => 'districts'
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
    public function update(DistrictRequest $request, District $district)
    {
        try{    
            \DB::beginTransaction();
                        
            $district->update($request->validated());

            activity()
                ->performedOn($district)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $district->name,
                    'id' => $district->id,
                    'table' => 'districts'
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
