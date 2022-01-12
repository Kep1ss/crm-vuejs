<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Helpers\FormatResponse;
use App\Http\Requests\ProvinceRequest;

class ProvinceController extends Controller
{

     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Province::query();

        $data->select("id","name");

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
        }    
        
        if($request->filled("is_get_school")){
            $data->whereNotNull("code");
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
    public function store(ProvinceRequest $request)
    {
        try{    
            \DB::beginTransaction();
            
            $province = Province::create($request->validated());

            activity()
                ->performedOn($province)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $province->name,
                    'id' => $province->id,
                    'table' => 'provinces'
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
    public function update(ProvinceRequest $request, Province $province)
    {
        try{    
            \DB::beginTransaction();
                        
            $province->update($request->validated());

            activity()
                ->performedOn($province)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $province->name,
                    'id' => $province->id,
                    'table' => 'provinces'
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
