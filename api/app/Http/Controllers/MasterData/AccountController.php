<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Exports\AccountExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    AccountRequest,
    CheckAllRequest
};
use Illuminate\Support\Str;

use App\Traits\{
    RoleControllerAdminTrait,
    ConstructControllerSuperAdminTrait
};

class AccountController extends Controller
{
    use RoleControllerAdminTrait,ConstructControllerSuperAdminTrait;

    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = User::query();

        $data->select("id","username","fullname","email","role","parent_id","district_id","province_id","city_id","deleted_at");

        $data->with(["province" => function($q){
            $q->select("id","name");
        }]);

        $data->with(["city" => function($q){
            $q->select("id","name");
        }]);

        $data->with(["district" => function($q){
            $q->select("id","name");
        }]);

     $data->with(["parent" => function($q){
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

        if(auth()->user()->role !== User::ROLE_SUPERADMIN){
            if(in_array(auth()->user()->role,$this->role_admins)){
                $data->where("parent_id",auth()->user()->parent_id)
                    ->whereNotIn("role",$this->role_admins);
            }else{
                $data->where("parent_id",auth()->user()->id);
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

            $user = User::create([
                "password" => \Hash::make($request->password),
                "username" => Str::slug($request->username,'-'),
                "parent_id" => $this->getCurrentUserId()
            ] + $request->validated());

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request,User $account)
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

            $account->update($payload);

            activity()
                ->performedOn($account)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $account->username,
                    'id' => $account->id,
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
