<?php

namespace App\Http\Controllers\SalaryConfiguration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeCategory;
use App\Http\Requests\{
    OvertimeCategoryRequest,
    CheckAllRequest
};
use App\Exports\OvertimeCategoryExport;
use App\Helpers\FormatResponse;

class OvertimeCategoryController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = OvertimeCategory::query();

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
    public function store(OvertimeCategoryRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $overtimeCategory = OvertimeCategory::create($request->validated());

            activity()
                ->performedOn($overtimeCategory)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $overtimeCategory->name,
                    'id' => $overtimeCategory->id,
                    'table' => 'overtime_categories'
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
    public function update(OvertimeCategoryRequest $request,OvertimeCategory $overtimeCategory)
    {
        try{    
            \DB::beginTransaction();

            $overtimeCategory->update($request->validated());

            activity()
            ->performedOn($overtimeCategory)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $overtimeCategory->name,
                'id' => $overtimeCategory->id,
                'table' => 'overtime_categories'
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
    public function destroy(OvertimeCategory $overtimeCategory)
    {
        try{    
            \DB::beginTransaction();

            $overtimeCategory->delete();

            activity()
            ->performedOn($overtimeCategory)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $overtimeCategory->name,
                'id' => $overtimeCategory->id,
                'table' => 'overtime_categories'
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
                 
            $data = OvertimeCategory::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->withProperties([
                'name' => $data->name,
                'id' => $data->id,
                'table' => 'overtime_categories'
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
            
            OvertimeCategory::whereIn("id",$request->checkboxs)
                ->delete();                

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'overtime_categories'                  
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

            OvertimeCategory::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,
                    'table' => 'overtime_categories'                  
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
            ? 'overtime-category.pdf' 
            : 'overtime-category.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new OvertimeCategoryExport($this->indexFilter()),$filetype,$extension);        
    }
    
    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/overtime-categories',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
