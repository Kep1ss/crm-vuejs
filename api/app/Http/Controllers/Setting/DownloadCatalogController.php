<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadCatalog;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    DownloadCatalogRequest,
    CheckAllRequest 
};
use Illuminate\Support\Str;

class DownloadCatalogController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = DownloadCatalog::query();

        $data->select("id","title","link","deleted_at");

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("title","like","%".$request->search."%")
                    ->orWhere("link","like","%".$request->search."%");
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
    public function store(DownloadCatalogRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $downloadCatalog = DownloadCatalog::create($request->validated());        

            activity()
                ->performedOn($downloadCatalog)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $downloadCatalog->title,
                    'id' => $downloadCatalog->id,
                    'table' => 'download_catalogs'
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
    public function update(DownloadCatalogRequest $request,DownloadCatalog $downloadCatalog)
    {
        try{    
            \DB::beginTransaction();
            
            $downloadCatalog->update($request->validated());

            activity()
                ->performedOn($downloadCatalog)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $downloadCatalog->title,
                    'id' => $downloadCatalog->id,
                    'table' => 'download_catalogs'
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
    public function destroy(DownloadCatalog $downloadCatalog)
    {
        try{    
            \DB::beginTransaction();
                    
            $downloadCatalog->delete();

            activity()
                ->performedOn($downloadCatalog)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $downloadCatalog->title,
                    'id' => $downloadCatalog->id,
                    'table' => 'download_catalogs'
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

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id){
        try{
            \DB::beginTransaction(); 
                  
            $downloadCatalog = DownloadCatalog::withTrashed()->findOrFail($id);            
                        
            $downloadCatalog->restore();
            
            activity()
                ->performedOn($downloadCatalog)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $downloadCatalog->title,
                    'id' => $downloadCatalog->id,
                    'table' => 'download_catalogs'
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

    /**
     * Remove all listing of the resource 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();
        
            DownloadCatalog::whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'download_catalogs'                  
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

    /**
     * Restore all listing of the resource 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restoreAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();            

            DownloadCatalog::withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'download_catalogs'                  
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
