<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollParameter;
use App\Exports\PayrollParameterExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    PayrollParameterRequest,
    CheckAllRequest
};

class PayrollParameterController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = PayrollParameter::query();

        $data->select("id","name","parameter_type");

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        
        
        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("name","like","%".$request->search."%")
                ->orWhere("parameter_type","like","%".$request->search."%");
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
    public function index(Request $request)
    {
        return response()->json($this->indexFilter());             
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollParameterRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter = PayrollParameter::create($request->validated());

            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $payrollParameter->name,
                    'id' => $payrollParameter->id,
                    'table' => 'payroll_parameters'
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
    public function update(PayrollParameterRequest $request, PayrollParameter $payrollParameter)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter->update($request->validated());

            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $payrollParameter->name,
                    'id' => $payrollParameter->id,
                    'table' => 'payroll_parameters'
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
    public function destroy(PayrollParameter $payrollParameter)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter->delete();
            
            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $payrollParameter->name,
                    'id' => $payrollParameter->id,
                    'table' => 'payroll_parameters'
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
                 
            $data = PayrollParameter::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'payroll_parameters'
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
            
            PayrollParameter::whereIn("id",$request->checkboxs)
                ->delete();      
            
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'payroll_parameters'                  
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

            PayrollParameter::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'payroll_parameters'                  
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
            ? 'payroll-parameter.pdf' 
            : 'payroll-parameter.xlsx';

        $extension =  $type == "pdf" 
            ?  \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new PayrollParameterExport($this->indexFilter()),$filetype,$extension);        
    }

    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/payroll-parameters',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
