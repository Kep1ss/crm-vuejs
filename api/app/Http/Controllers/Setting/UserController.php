<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\UserExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    UserRequest,
    CheckAllRequest 
};

class UserController extends Controller
{
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = User::query();

        $data->select("id","username","fullname","email");

        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }        

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("username","like","%".$request->search."%")
                    ->orWhere("fullname","like","%".$request->search."%")
                    ->orWhere("email","like","%".$request->search."%");
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
    public function store(UserRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $user = User::create($request->validated() + [
                "password" => \Hash::make($request->password),
                "username" => str_slug($request->username)
            ]);

            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'username' => $user->username,
                    'id' => $user->id,
                    'table' => 'users'
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
    public function update(UserRequest $request,User $user)
    {
        try{    
            \DB::beginTransaction();
            
            $payload = $request->validated();
            $payload["username"] = str_slug($payload["username"]);
            
            if($request->filled("password")){   
                $payload["password"] = \Hash::make($request->password);
            }

            $user->update($payload);

            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'username' => $user->username,
                    'id' => $user->id,
                    'table' => 'users'
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
    public function destroy(User $user)
    {
        try{    
            \DB::beginTransaction();

            throw_if(
                $user->id == auth()->user()->id,
                new \Exception("Anda tidak dapat menghapus diri anda sendiri",422)
            );            

            $user->delete();

            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'username' => $user->username,
                    'id' => $user->id,
                    'table' => 'users'
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
                 
            $data = User::withTrashed()->findOrFail($id);

            $data->restore();
            
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'username' => $user->username,
                    'id' => $user->id,
                    'table' => 'users'
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

            throw_if(
                in_array(auth()->user()->id,$request->checkboxs),
                new \Exception("Anda tidak dapat menghapus diri anda sendiri",422)
            );

            User::whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'users'                  
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

            User::withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'users'                  
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
            ? 'user.pdf' 
            : 'user.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new UserExport($this->indexFilter()),$filetype,$extension);        
    }

    /*
        How to call
        division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
    */        
    public function print(){
        $pdf = \PDF::loadview('exports/users',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }
}
