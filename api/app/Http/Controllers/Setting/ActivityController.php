<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ActivityExport;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
      /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Activity::query();       

        $data = $data->with([
            "causer" => function($q){
                $q->select("id","username");
            }
        ]);
            
        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("description","like","%".$request->search."%");                
            });            

            $data->orWhereHas("causer",function($q) use ($request) {
                $q->where("username","like","%".$request->search."%");
            });
        }

        if($request->filled("start_date") && $request->filled("end_date")){
            $data->whereBetween("created_at",[$request->start_date,$request->end_date]);
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

    // public function export($type){
    //     $filetype = $type == 'pdf' 
    //         ? 'activity.pdf' 
    //         : 'activity.xlsx';

    //     $extension =  $type == "pdf" 
    //         ? \Maatwebsite\Excel\Excel::DOMPDF 
    //         : \Maatwebsite\Excel\Excel::XLSX;
        
    //     return \Excel::download(new ActivityExport($this->indexFilter()),$filetype,$extension);  
    // }

     /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    // public function print(){
    //     $pdf = \PDF::loadview('exports/activites',[
    //           "data" => !request()->filled("all") 
    //             ? $this->indexFilter()->getCollection() 
    //             : $this->indexFilter()
    //     ]);

    //     return  $pdf->stream();
    // }
}
