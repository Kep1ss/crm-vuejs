<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    AnnouncementRequest,
    CheckAllRequest 
};
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Announcement::query();
        
        $data->select("id","content","user_id","deleted_at")
            ->with(["user" => function($q){
                $q->select("id","username");
            }]);

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        

        if(auth()->user()->role !== 0){
            $data->where("user_id",auth()->user()->id);
        }

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("content","like","%".$request->search."%");
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
    public function store(AnnouncementRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $accouncement = Announcement::create($request->validated() + [
                "user_id" => auth()->user()->id
            ]);        

            activity()
                ->performedOn($accouncement)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $accouncement->id,
                    'table' => 'announcements'
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
    public function update(AnnouncementRequest $request,Announcement $announcement)
    {
        try{    
            \DB::beginTransaction();
            
            throw_if(
                $announcement->user_id != auth()->user()->id,
                new \Exception("Anda tidak punya hak akses",422)
            );

            $announcement->update($request->validated());

            activity()
                ->performedOn($announcement)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $announcement->id,
                    'table' => 'announcements'
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
    public function destroy(Announcement $accouncement)
    {
        try{    
            \DB::beginTransaction();

            throw_if(
                $announcement->user_id != auth()->user()->id,
                new \Exception("Anda tidak punya hak akses",422)
            );
                    
            $announcement->delete();

            activity()
                ->performedOn($announcement)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $announcement->id,
                    'table' => 'announcements'
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
                  
            $announcement = Announcement::withTrashed()
                ->where("id",$id)
                ->where("user_id",auth()->user()->id)
                ->firstOrFail();            
                        
            $announcement->restore();
            
            activity()
                ->performedOn($announcement)
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $announcement->id,
                    'table' => 'announcements'
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
        
            auth()->user()
                ->announcements()
                ->whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'announcements'                  
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

            auth()->user()
                ->announcements()
                ->withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'announcements'                  
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
}
