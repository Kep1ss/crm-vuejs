<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FingerDevice;
use App\Exports\FingerDeviceExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    CheckAllRequest,
    FingerDeviceRequest
};


class FingerDeviceController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = FingerDevice::query();

        $data->select("id","name","address");

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
                    ->orWhere("address","like","%".$request->search."%");
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
    public function store(FingerDeviceRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $fingerDevice = FingerDevice::create($request->validated());

            activity()
                ->performedOn($fingerDevice)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $fingerDevice->name,
                    'id' => $fingerDevice->id,
                    'table' => 'finger_devices'
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
    public function update(FingerDeviceRequest $request,FingerDevice $fingerDevice)
    {
        try{    
            \DB::beginTransaction();

            $fingerDevice->update($request->validated());

            activity()
                ->performedOn($fingerDevice)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $fingerDevice->name,
                    'id' => $fingerDevice->id,
                    'table' => 'finger_devices'
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
    public function destroy(FingerDevice $fingerDevice)
    {
        try{    
            \DB::beginTransaction();

            $fingerDevice->delete();
            
            activity()
                ->performedOn($fingerDevice)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $fingerDevice->name,
                    'id' => $fingerDevice->id,
                    'table' => 'finger_devices'
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
                 
            $data = FingerDevice::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'finger_devices'
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
            
            FingerDevice::whereIn("id",$request->checkboxs)
                ->delete();      
            
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'finger_devices'                  
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

            FingerDevice::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'finger_devices'                  
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
            ? 'finger-device.pdf' 
            : 'finger_device.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new FingerDeviceExport($this->indexFilter()),$filetype,$extension);        
    }
}
