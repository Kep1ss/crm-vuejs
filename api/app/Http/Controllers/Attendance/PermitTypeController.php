<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitType;
use App\Exports\PermitTypeExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    CheckAllRequest,
    PermitTypeRequest
};

class PermitTypeController extends Controller
{
     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = PermitType::query();

        $data->select("id","name")
            ->with(["permit_formulas" => function($q){                
                $q->select("id","payroll_parameter_id","permit_type_id","percent","nominal")
                ->with(["payroll_parameter" => function($query){
                    $query->select("id","name","parameter_type");
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
    public function store(PermitTypeRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $permitType = PermitType::create($request->only("name"));
        
            foreach($request->validated()["permit_formulas"] as $item){
                $permitType->permit_formulas()->create($item);
            }

            activity()
                ->performedOn($permitType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $permitType->name,
                    'id' => $permitType->id,
                    'table' => 'permit_types'
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
    public function update(PermitTypeRequest $request,PermitType $permitType)
    {
        try{    
            \DB::beginTransaction();

            $permitType->permit_formulas()->delete();

            $permitType->update($request->only("name"));

            foreach($request->validated()["permit_formulas"] as $item){
                $permitType->permit_formulas()->create($item);
            }

            activity()
                ->performedOn($permitType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $permitType->name,
                    'id' => $permitType->id,
                    'table' => 'permit_types'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermitType $permitType)
    {
        try{    
            \DB::beginTransaction();

            $permitType->delete();
            
            activity()
                ->performedOn($permitType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $permitType->name,
                    'id' => $permitType->id,
                    'table' => 'permit_types'
                ])
                ->log('Deleted Data');      

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function restore($id){
        try{
            \DB::beginTransaction(); 
                 
            $data = PermitType::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'permit_types'
                ])
                ->log('Restore Data');      

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function destroyAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();
            
            PermitType::whereIn("id",$request->checkboxs)
                ->delete();      
            
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'permit_types'                  
                ])
                ->log('Deleted All Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function restoreAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();            

            PermitType::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'permit_types'                  
                ])
                ->log('Restore All Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }   
    }

    public function export($type){
        $filetype = $type == 'pdf' 
            ? 'permit-type.pdf' 
            : 'permit-type.xlsx';

        $extension =  $type == "pdf" 
            ?  \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new PermitTypeExport($this->indexFilter()),$filetype,$extension);        
    }
}
