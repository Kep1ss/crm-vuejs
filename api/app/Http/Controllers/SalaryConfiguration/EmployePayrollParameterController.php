<?php

namespace App\Http\Controllers\SalaryConfiguration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployePayrollParameter;
use App\Exports\EmployePayrollParameterExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    EmployePayrollParameterRequest,
    CheckAllRequest
};

class EmployePayrollParameterController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = EmployePayrollParameter::query();

        $data->select("id","payroll_parameter_id","employe_id","payroll_method","workday","percentage","amount");

        $data->with([
            "employe" => function($q){
                $q->select("id","name");
            },
            "payroll_parameter" => function($q){
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
                $q->orWhere("payroll_method","like","%".$request->search."%")
                    ->orWhere("percentage","like","%".$request->search."%")
                    ->orWhere("amount","like","%".$request->search."%")
                    ->orWhere("workday","like","%".$request->search."%");
            });

            $data->orWhereHas("employe",function($q) use ($request){
                $q->where("name","like","%".$request->search."%");
            });

            $data->orWhereHas("payroll_parameter",function($q) use ($request){
                $q->where("name","like","%".$request->search."%");
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
    public function store(EmployePayrollParameterRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter = EmployePayrollParameter::create($request->validated());

            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'payroll_method' => $payrollParameter->payroll_method,
                    'id' => $payrollParameter->id,
                    'table' => 'employe_payroll_parameters'
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
    public function update(EmployePayrollParameterRequest $request,EmployePayrollParameter $payrollParameter)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter->update($request->validated());

            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'payroll_method' => $payrollParameter->payroll_method,
                    'id' => $payrollParameter->id,
                    'table' => 'employe_payroll_parameters'
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
    public function destroy(EmployePayrollParameter $payrollParameter)
    {
        try{    
            \DB::beginTransaction();

            $payrollParameter->delete();

            activity()
                ->performedOn($payrollParameter)
                ->causedBy(auth()->user())
                ->withProperties([
                    'payroll_method' => $payrollParameter->payroll_method,
                    'id' => $payrollParameter->id,
                    'table' => 'employe_payroll_parameters'
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
                 
            $data = EmployePayrollParameter::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'payroll_method' => $data->payroll_method,
                    'id' => $data->id,
                    'table' => 'employe_payroll_parameters'
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
            
            EmployePayrollParameter::whereIn("id",$request->checkboxs)
                ->delete();                

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'employe_payroll_parameters'                  
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

            EmployePayrollParameter::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
            ->causedBy(auth()->user())
            ->withProperties([            
                'id' => $request->checkboxs,  
                'table' => 'employe_payroll_parameters'                  
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
            ? 'employe-payroll-parameter.pdf' 
            : 'employe-payroll-parameter.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new EmployePayrollParameterExport($this->indexFilter()),$filetype,$extension);        
    }

    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/employe-payroll-parameters',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
