<?php

namespace App\Http\Controllers\SalaryConfiguration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndexFormula;
use App\Http\Requests\{
    IndexFormulaRequest,
    CheckAllRequest
};
use App\Exports\IndexFormulaExport;
use App\Helpers\FormatResponse;

class IndexFormulaController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = IndexFormula::query();

        $data->select("id","name","value");
                 
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
                    ->orWhere("value","like","%".$request->search."%");
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
    public function store(IndexFormulaRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $indexFormula = IndexFormula::create($request->validated());

            activity()
                ->performedOn($indexFormula)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $indexFormula->name,
                    'id' => $indexFormula->id,
                    'table' => 'index_formulas'
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
    public function update(IndexFormulaRequest $request,IndexFormula $indexFormula)
    {
        try{    
            \DB::beginTransaction();

            $indexFormula->update($request->validated());

            activity()
            ->performedOn($indexFormula)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $indexFormula->name,
                'id' => $indexFormula->id,
                'table' => 'index_formulas'
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
    public function destroy(IndexFormula $indexFormula)
    {
        try{    
            \DB::beginTransaction();

            $indexFormula->delete();

            activity()
            ->performedOn($indexFormula)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $indexFormula->name,
                'id' => $indexFormula->id,
                'table' => 'index_formulas'
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
                 
            $data = IndexFormula::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $data->name,
                'id' => $data->id,
                'table' => 'index_formulas'
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
            
            IndexFormula::whereIn("id",$request->checkboxs)
                ->delete();                

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'index_formulas'                  
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

            IndexFormula::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,
                    'table' => 'index_formulas'                  
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
            ? 'index-formula.pdf' 
            : 'index-formula.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new IndexFormulaExport($this->indexFilter()),$filetype,$extension);        
    }
    
    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/index-formulas',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
