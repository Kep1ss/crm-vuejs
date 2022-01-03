<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employe;
use App\Exports\EmployeExport;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    EmployeRequest,
    CheckAllRequest
};

class EmployeController extends Controller
{
     /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Employe::query();

        $data->with([
            "division" => function($q){
                $q->select("id","name");
            },
           "position" => function($q){
               $q->select("id","name");
           }
        ]);

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
                    ->orWhere("code","like","%".$request->search."%")
                    ->orWhere("city","like","%".$request->search."%")
                    ->orWhere("graduate","like","%".$request->search."%")
                    ->orWhere("citizen_id","like","%".$request->search."%")
                    ->orWhere("npwp","like","%".$request->search."%");
            });

            $data->orWhereHas("division",function($q) use ($request) {
                $q->where("name","like","%".$request->search."%");
            });

            $data->orWhereHas("position",function($q) use ($request) {
                $q->where("name","like","%".$request->search."%");
            });
        }

        /**
         * Conditional Statement : Filter Paramter to Employe
         * author : Joko Purnomo
         * function : get Data employe with filter parameter by request
         *
         * @return \Illuminate\Http\Response
         */

        if($request->filled("filter")){
            $filterFiled = json_decode($request->filter);
            $data->where(function($q) use ($filterFiled) {
                if($filterFiled->gender != ''){
                    $q->Where("gender","=",$filterFiled->gender);
                }
                if($filterFiled->city != ''){
                    $q->Where("city","=",$filterFiled->city);
                }
                if($filterFiled->graduate != ''){
                    $q->Where("graduate","=",$filterFiled->graduate);
                }
            });

            if($filterFiled->division != ''){
                $data->WhereHas("division",function($q) use ($filterFiled) {
                $q->where("name","=",$filterFiled->division);
             });
            }

            if($filterFiled->position != ''){
                $data->WhereHas("position",function($q) use ($filterFiled) {
                $q->where("name","=",$filterFiled->position);
               });
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
        * Conditional Statement : Select Distinct/Grupby
        * author : Joko Purnomo
        * function : get List Data Cites Employe
        *
        * @return \Illuminate\Http\Response
    */
    function getCites(){
        return Employe::select("city")->groupBy("city")->get();
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
    public function store(EmployeRequest $request)
    {
        try{
            \DB::beginTransaction();

            $employe = Employe::create($request->validated());

            activity()
                ->performedOn($employe)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $employe->name,
                    'id' => $employe->id,
                    'table' => 'employes'
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
    public function update(EmployeRequest $request,Employe $employe )
    {
        try{
            \DB::beginTransaction();

            $employe->update($request->validated());

            activity()
                ->performedOn($employe)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $employe->name,
                    'id' => $employe->id,
                    'table' => 'employes'
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
    public function destroy(Employe $employe)
    {
        try{
            \DB::beginTransaction();

            $employe->delete();

            activity()
                ->performedOn($employe)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $employe->name,
                    'id' => $employe->id,
                    'table' => 'employes'
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
     * Restore the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id){
        try{
            \DB::beginTransaction();

            $data = Employe::withTrashed()->findOrFail($id);

            $data->restore();

            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'employes'
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
     * Destroy All the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();

            Employe::whereIn("id",$request->checkboxs)
                ->delete();

            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $request->checkboxs,
                    'table' => 'employes'
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
     * Restore All the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restoreAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();

            Employe::withTrashed()->whereIn("id",$request->checkboxs)
                ->restore();

            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'id' => $request->checkboxs,
                    'table' => 'employes'
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

     /**
     * Export the specified resource in storage.
     *
     * @param  "pdf"|"excel"  $type
     * @return \Illuminate\Http\Response
     */
    public function export($type){
        $filetype = $type == 'pdf'
            ? 'employe.pdf'
            : 'employe.xlsx';

        $extension =  $type == "pdf"
            ?  \Maatwebsite\Excel\Excel::DOMPDF
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new EmployeExport($this->indexFilter()),$filetype,$extension);
    }

    /**
     * Print the specified resource in storage.
     *
     * @return \PDF
     * How to call :
     * => division/print?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
     */
    public function print(){
        $pdf = \PDF::loadview('exports/employes',[
              "data" => !request()->filled("all")
                ? $this->indexFilter()->getCollection()
                : $this->indexFilter()
        ]);

        return  $pdf->stream();
    }

     /**
     * author : Joko Purnomo
     * Generate Code For Employes.
     *
     * @return \code
     * How to call :
     * => employes/getcode
     */

    public function getCode(){
        try{

            $code = \DB::select('call sp_auto(?,?,?)',array("employes","code",""));
                return response()->json([
                    "code" => $code[0]->kode
                ]);

        }catch(\Exception $e){
            return FormatResponse::failed($e);
        }
    }
}
