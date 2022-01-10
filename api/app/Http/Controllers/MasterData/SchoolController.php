<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    School,
    Setting,
    District
};
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    SchoolRequest,
    SchoolGetRequest
};
use App\Traits\ConstructControllerSuperAdminTrait;
use Illuminate\Support\Facades\Http;

class SchoolController extends Controller
{
    use ConstructControllerSuperAdminTrait;

     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = School::query();

        $data->select("id","name","district_id","level","member","is_private","address","phone_headmaster","phone_teacher","phone_treasurer");

        $data->with(["district" => function($q){
            $q->select("id","name","city_id")
             ->with(["city" => function($qc){
                 $qc->select("id","name","is_city","province_id")
                 ->with(["province" => function($qp){
                     $qp->select("id","name");
                 }]);
             }]);
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
                $q->orWhere("name","like","%".$request->search."%");                
            });

            $data->orWhereHas("district",function($q) use ($request){
                $q->where("name","like","%".$request->search."%");
            });                
        }    

        if($request->filled("level")){
            $data->where("level",$request->level);
        }

        if($request->filled("is_private")){
            $data->where("is_private",intval($request->is_private));
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
    public function store(SchoolRequest $request)
    {
        try{    
            \DB::beginTransaction();
            
            $school = School::create($request->validated());

            activity()
                ->performedOn($school)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $school->name,
                    'id' => $school->id,
                    'table' => 'schools'
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
    public function update(SchoolRequest $request, School $school)
    {
        try{    
            \DB::beginTransaction();
                        
            $school->update($request->validated());

            activity()
                ->performedOn($school)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $school->name,
                    'id' => $school->id,
                    'table' => 'schools'
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
     * Get Data School From Dapodik
     */
    public function getSchool(SchoolGetRequest $request){
        try{            
            $dapodik_url = Setting::query()
                ->select("value")
                ->where("name","dapodik_url")
                ->first()
                ->value;

            $dapodik_school_id =  Setting::query()
                ->select("value")
                ->where("name","dapodik_school_id")
                ->first()
                ->value;

            $district_code = District::query()
                ->select("code")
                ->where("id",$request->district_id)
                ->first()
                ->code;
            
            $queryDapodik = [
                "kode_wilayah" => $district_code,
                "bentuk_pendidikan_id" => strtolower($request->level),
                "semester_id" => $request->year . $request->semester,
                "id_level_wilayah" => $dapodik_school_id 
            ];
        
            $compelete_url = $dapodik_url . "?" . http_build_query($queryDapodik);           
            
            $response = Http::get($compelete_url);

            throw_if(
                !$response->ok(),
                new \Exception("Terjadi Kesalahan Saat Mengambil Data",422)
            );

            return $response->json();
        }catch(\Exception $e){
            return FormatResponse::failed($e);
        }
    }
}
