<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Helpers\FormatResponse;
use App\Models\User;
use App\Http\Requests\{
    AccountRequest,
    CheckAllRequest
};
use Illuminate\Support\Str;
class SalesController extends Controller
{
    //
    public function indexFilter(){
        $request = request();

        $data = Sales::query();

        $data = $data->with(["parent" => function($q){
            $q->select("id","username","role");
        }]);

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
        if (auth()->user()->role != User::ROLE_MANAGER_NASIONAL){
            if (auth()->user()->role == User::ROLE_MANAGER_AREA){
                $data->where("parent_manager_area",auth()->user()->id);
            }else if(auth()->user()->role == User::ROLE_KAPER){
                $data->where("parent_kaper",auth()->user()->id);
            }else if(auth()->user()->role == User::ROLE_SPV){
                $data->where("parent_spv",auth()->user()->id);
            }
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
    public function store(AccountRequest $request)
    {
        try{
            \DB::beginTransaction();

            $payload = [
                "password" => \Hash::make($request->password),
                "username" => Str::slug($request->username,'-'),
                "parent_id" => auth()->user()->id,
            ] + $request->validated();

            $user = User::create($payload);

            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $user->username,
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(AccountRequest $request,User $sales)
    {
        try{
            \DB::beginTransaction();

            $payload = $request->validated();
            $payload["username"] = Str::slug($payload["username"],'-');

            if($request->filled("password")){
                $payload["password"] = \Hash::make($request->password);
            }else{
                unset($payload["password"]);
            }

            $sales->update($payload);

            activity()
                ->performedOn($sales)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $sales->username,
                    'id' => $sales->id,
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
}
