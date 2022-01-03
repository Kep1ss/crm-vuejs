<?php

namespace App\Http\Controllers\SalaryConfiguration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeFormula;
use App\Http\Requests\{
    OvertimeFormulaRequest,
    CheckAllRequest
};
use App\Exports\OvertimeFormulaExport;
use App\Helpers\FormatResponse;

class OvertimeFormulaController extends Controller
{
     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = OvertimeFormula::query();

        $data->select("id","formula","overtime_category_id","index_formula_id")
            ->with([
                "overtime_category" => function($q){
                    $q->select("id","name");
                },
                "index_formula" => function($q){
                    $q->select("id","name","value");
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
                $q->orWhere("formula","like","%".$request->search."%");
            });            

            $data->orWhereHas("overtime_category",function($q) use ($request) {
                $q->where("name","like","%".$request->search."%");
            });

            $data->orWhereHas("index_formula",function($q) use ($request){
                $q->where("name","like","%".$request->search."%");
            });
        }

        $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

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
    public function store(OvertimeFormulaRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $overtimeFormula = OvertimeFormula::create($request->validated());

            activity()
                ->performedOn($overtimeFormula)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $overtimeFormula->id,
                    'table' => 'overtime_formulas'
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
    public function update(OvertimeFormulaRequest $request,OvertimeFormula $overtimeFormula)
    {
        try{    
            \DB::beginTransaction();

            $overtimeFormula->update($request->validated());

            activity()
            ->performedOn($overtimeFormula)
            ->causedBy(auth()->user())
            ->withProperties([
                'id' => $overtimeFormula->id,
                'table' => 'overtime_formulas'
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
    public function destroy(OvertimeFormula $overtimeFormula)
    {
        try{    
            \DB::beginTransaction();

            $overtimeFormula->delete();

            activity()
            ->performedOn($overtimeFormula)
            ->causedBy(auth()->user())
            ->withProperties([
                'id' => $overtimeFormula->id,
                'table' => 'overtime_formulas'
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
                 
            $data = OvertimeFormula::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->withProperties([
                'id' => $data->id,
                'table' => 'overtime_formulas'
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
            
            OvertimeFormula::whereIn("id",$request->checkboxs)
                ->delete();                

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'overtime_formulas'                  
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

            OvertimeFormula::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,
                    'table' => 'overtime_formulas'                  
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
            ? 'overtime-formula.pdf' 
            : 'overtime-formula.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new OvertimeFormulaExport($this->indexFilter()),$filetype,$extension);        
    }
    
    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/overtime-formulas',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
