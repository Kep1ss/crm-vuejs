<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitEmploye;
use App\Exports\PermitEmployeExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    CheckAllRequest,
    PermitEmployeRequest
};

class PermitEmployeController extends Controller
{
     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = PermitEmploye::query();

        $data->with([
                "employe" => function($q){
                    $q->select("id","name");
                },
                "permit_type" => function($q){
                    $q->select("id","name");
                }
            ]);

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        
        
        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("description","like","%".$request->search."%");
            });

            $data->orWhereHas(function($q) use ($request){
                $q->orWhere("name","like","%".$request->search."%");
            });

            $data->orWhereHas(function($q)  use ($request){
                $q->orWhere("name","like","%".$request->search."%");
            });
        }

        if($request->filled("permit_date_start") && $request->filled("permit_date_end")){
            $data->where("permit_date_start",">=",$request->permit_date_start)
                ->where("permit_date_end","<=",$request->permit_date_end);
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
    public function store(PermitEmployeRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $permitEmploye = PermitEmploye::create($request->validated());

            activity()
                ->performedOn($permitEmploye)
                ->causedBy(auth()->user())
                ->withProperties([                    
                    'id' => $permitEmploye->id,
                    'table' => 'permit_employes'
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
    public function update(PermitEmployeRequest $request,PermitEmploye $permitEmploye)
    {
        try{    
            \DB::beginTransaction();

            $permitEmploye->update($request->validated());

            activity()
                ->performedOn($permitEmploye)
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $permitEmploye->id,
                    'table' => 'permit_employes'
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
    public function destroy(PermitEmploye $permitEmploye)
    {
        try{    
            \DB::beginTransaction();

            $permitEmploye->delete();
            
            activity()
                ->performedOn($permitEmploye)
                ->causedBy(auth()->user())
                ->withProperties([                
                    'id' => $permitEmploye->id,
                    'table' => 'permit_employes'
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
                 
            $data = PermitEmploye::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $data->id,
                    'table' => 'permit_employes'
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
            
            PermitEmploye::whereIn("id",$request->checkboxs)
                ->delete();      
            
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'permit_employes'                  
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

            PermitEmploye::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'permit_employes'                  
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
            ? 'permit-employe.pdf' 
            : 'permit-employe.xlsx';

        $extension =  $type == "pdf" 
            ?  \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new PermitEmployeExport($this->indexFilter()),$filetype,$extension);        
    }
}
