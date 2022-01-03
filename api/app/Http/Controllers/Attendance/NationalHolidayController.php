<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NationalHoliday;
use App\Exports\NationalHolidayExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    CheckAllRequest,
    NationalHolidayRequest
};

class NationalHolidayController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = NationalHoliday::query();

        $data->select("id","name","off_date");

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

        if($request->filled("off_date_start") && $request->filled("off_date_end")){
            $data->whereBetween("off_date",[$request->off_date_start,$request->off_date_end]);
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
    public function store(NationalHolidayRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $nationalHoliday = NationalHoliday::create($request->validated());

            activity()
                ->performedOn($nationalHoliday)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $nationalHoliday->name,
                    'id' => $nationalHoliday->id,
                    'table' => 'national_holidays'
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
    public function update(NationalHolidayRequest $request,NationalHoliday $nationalHoliday)
    {
        try{    
            \DB::beginTransaction();

            $nationalHoliday->update($request->validated());

            activity()
                ->performedOn($nationalHoliday)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $nationalHoliday->name,
                    'id' => $nationalHoliday->id,
                    'table' => 'national_holidays'
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
    public function destroy(NationalHoliday $nationalHoliday)
    {
        try{    
            \DB::beginTransaction();

            $nationalHoliday->delete();
            
            activity()
                ->performedOn($nationalHoliday)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $nationalHoliday->name,
                    'id' => $nationalHoliday->id,
                    'table' => 'national_holidays'
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
                 
            $data = NationalHoliday::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'national_holidays'
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
            
            NationalHoliday::whereIn("id",$request->checkboxs)
                ->delete();      
            
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'national_holidays'                  
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

            NationalHoliday::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'national_holidays'                  
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
            ? 'national-holiday.pdf' 
            : 'national-holiday.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new NationalHolidayExport($this->indexFilter()),$filetype,$extension);        
    }
}
