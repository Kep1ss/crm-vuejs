<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profil\ProfilController;

use App\Http\Controllers\Setting\{
	UserController,
	ActivityController,
	SettingController
};

use App\Http\Controllers\Attendance\{
	PermitTypeController,
	PermitEmployeController,
	NationalHolidayController,
	FingerDeviceController
};

use App\Http\Controllers\MasterData\{
	EmployeController,
	DivisionController,
	PositionController,
	PayrollParameterController
};

use App\Http\Controllers\SalaryConfiguration\{
	EmployePayrollParameterController,
	PayrollParameterFormulaController,
	OvertimeCategoryController,
	OvertimeFormulaController,	
	IndexFormulaController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$version = "v1";

Route::group(["prefix" => $version],function() use ($version) {
    Route::get('/status', function () use ($version) {
		return response([
			'message' => 'active (running)',
			'version' => $version
		]);
	})->name("status");


    Route::group(["namespace" => "Auth","as" => "auth."],function(){
		Route::post('/signin', [AuthController::class,"signin"])->name("signin");
		Route::post("/forgot-password",[AuthController::class,"forgotPassword"])->name("forgot-password");
		Route::post("/reset-password",[AuthController::class,"resetPassword"])->name("reset-password");

		Route::group(["middleware" => "jwt-refresh"],function(){
			Route::post("/refresh",[AuthController::class,'refresh'])->name("refresh-token");
		});

		Route::group(["middleware" => "jwt"],function(){
			Route::post("/logout",[AuthController::class,'logout'])->name("logout");
			Route::get("/me",[AuthController::class,'me'])->name("me");
		});
	});

    Route::group(["middleware" => "jwt"],function(){
		// PROFIL 
		Route::put("/profil",[ProfilController::class,"update"])->name("profil.update");
		Route::put("/profil/password",[ProfilController::class,"password"])->name("profil.password");		

		/* MODULE ABSENSI */
		Route::group(["as" => "attendance."],function(){
			Route::post("/finger-device/restore-all",[FingerDeviceController::class,"restoreAll"])->name("finger.device.restore-all");
			Route::delete("/finger-device/destroy-all",[FingerDeviceController::class,"destroyAll"])->name("finger.device.destroy-all");
			Route::post("/finger-device/restore/{id}",[FingerDeviceController::class,"restore"])->name("finger.device.restore");
			Route::get('/finger-device/export/{type}', [FingerDeviceController::class,"export"])->name("finger.device.export");
			Route::get('/finger-device/print',[FingerDeviceController::class,"print"])->name("finger.device.print");
			Route::apiResource("finger-device",FingerDeviceController::class);

			Route::post("/national-holiday/restore-all",[NationalHolidayController::class,"restoreAll"])->name("national.holiday.restore-all");
			Route::delete("/national-holiday/destroy-all",[NationalHolidayController::class,"destroyAll"])->name("national.holiday.destroy-all");
			Route::post("/national-holiday/restore/{id}",[NationalHolidayController::class,"restore"])->name("national.holiday.restore");
			Route::get('/national-holiday/export/{type}', [NationalHolidayController::class,"export"])->name("national.holiday.export");
			Route::get('/national-holiday/print',[NationalHolidayController::class,"print"])->name("national.holiday.print");
			Route::apiResource("national-holiday",NationalHolidayController::class);

			Route::post("/permit-employe/restore-all",[PermitEmployeController::class,"restoreAll"])->name("permit.employe.restore-all");
			Route::delete("/permit-employe/destroy-all",[PermitEmployeController::class,"destroyAll"])->name("permit.employe.destroy-all");
			Route::post("/permit-employe/restore/{id}",[PermitEmployeController::class,"restore"])->name("permit.employe.restore");
			Route::get('/permit-employe/export/{type}', [PermitEmployeController::class,"export"])->name("permit.employe.export");
			Route::get('/permit-employe/print',[PermitEmployeController::class,"print"])->name("permit.employe.print");
			Route::apiResource("permit-employe",PermitEmployeController::class);
		
			Route::post("/permit-type/restore-all",[PermitTypeController::class,"restoreAll"])->name("permit.type.restore-all");
			Route::delete("/permit-type/destroy-all",[PermitTypeController::class,"destroyAll"])->name("permit.type.destroy-all");
			Route::post("/permit-type/restore/{id}",[PermitTypeController::class,"restore"])->name("permit.type.restore");
			Route::get('/permit-type/export/{type}', [PermitTypeController::class,"export"])->name("permit.type.export");
			Route::get('/permit-type/print',[PermitTypeController::class,"print"])->name("permit.type.print");
			Route::apiResource("permit-type",PermitTypeController::class);
		});

		/* MODULE SETTING */
		Route::group(["as" => "setting."],function(){        
			Route::post("/user/restore-all",[UserController::class,"restoreAll"])->name("user.restore-all");
			Route::delete("/user/destroy-all",[UserController::class,"destroyAll"])->name("user.destroy-all");
			Route::post("/user/restore/{id}",[UserController::class,"restore"])->name("user.restore");
			Route::get('/user/export/{type}', [UserController::class,"export"])->name("user.export");
			Route::get('/user/print',[UserController::class,"print"])->name("user.print");
			Route::apiResource("user",UserController::class);

			Route::get("/activity",[ActivityController::class,"index"])->name("activity.index");
			Route::get('/activity/export/{type}', [ActivityController::class,"export"])->name("activity.export");
			Route::get('/activity/print',[ActivityController::class,"print"])->name("activity.print");

			Route::get("/setting",[SettingController::class,"index"])->name("setting.index");
        	Route::put("/setting",[SettingController::class,"update"])->name("setting.update");
        	Route::put("/setting/logo",[SettingController::class,"updateLogo"])->name("setting.logo");
		});

		/* MODULE KONFIGURASI GAJI */
		Route::group(["as" => "salary.configuration."],function(){
			Route::post("/index-formula/restore-all",[IndexFormulaController::class,"restoreAll"])->name("index-formula.restore-all");
			Route::delete("/index-formula/destroy-all",[IndexFormulaController::class,"destroyAll"])->name("index-formula.destroy-all");
			Route::post("/index-formula/restore/{id}",[IndexFormulaController::class,"restore"])->name("index-formula.restore");
			Route::get('/index-formula/export/{type}', [IndexFormulaController::class,"export"])->name("index-formula.export");
			Route::get('/index-formula/print',[IndexFormulaController::class,"print"])->name("index-formula.print");
			Route::apiResource("index-formula",IndexFormulaController::class);

			Route::post("/overtime-category/restore-all",[OvertimeCategoryController::class,"restoreAll"])->name("overtime-category.restore-all");
			Route::delete("/overtime-category/destroy-all",[OvertimeCategoryController::class,"destroyAll"])->name("overtime-category.destroy-all");
			Route::post("/overtime-category/restore/{id}",[OvertimeCategoryController::class,"restore"])->name("overtime-category.restore");
			Route::get('/overtime-category/export/{type}', [OvertimeCategoryController::class,"export"])->name("overtime-category.export");
			Route::get('/overtime-category/print',[OvertimeCategoryController::class,"print"])->name("overtime-category.print");
			Route::apiResource("overtime-category",OvertimeCategoryController::class);

			Route::post("/overtime-formula/restore-all",[OvertimeFormulaController::class,"restoreAll"])->name("overtime-formula.restore-all");
			Route::delete("/overtime-formula/destroy-all",[OvertimeFormulaController::class,"destroyAll"])->name("overtime-formula.destroy-all");
			Route::post("/overtime-formula/restore/{id}",[OvertimeFormulaController::class,"restore"])->name("overtime-formula.restore");
			Route::get('/overtime-formula/export/{type}', [OvertimeFormulaController::class,"export"])->name("overtime-formula.export");
			Route::get('/overtime-formula/print',[OvertimeFormulaController::class,"print"])->name("overtime-formula.print");
			Route::apiResource("overtime-formula",OvertimeFormulaController::class);
		
			Route::post("/payroll-parameter-formula/restore-all",[PayrollParameterFormulaController::class,"restoreAll"])->name("payroll-parameter-formula.restore-all");
			Route::delete("/payroll-parameter-formula/destroy-all",[PayrollParameterFormulaController::class,"destroyAll"])->name("payroll-parameter-formula.destroy-all");
			Route::post("/payroll-parameter-formula/restore/{id}",[PayrollParameterFormulaController::class,"restore"])->name("payroll-parameter-formula.restore");
			Route::get('/payroll-parameter-formula/export/{type}', [PayrollParameterFormulaController::class,"export"])->name("payroll-parameter-formula.export");
			Route::get('/payroll-parameter-formula/print',[PayrollParameterFormulaController::class,"print"])->name("payroll-parameter-formula.print");
			Route::apiResource("payroll-parameter-formula",PayrollParameterFormulaController::class);

			Route::group(["as" => "employe.","prefix" => "employe"],function(){
				Route::post("/payroll-parameter/restore-all",[EmployePayrollParameterController::class,"restoreAll"])->name("payroll-parameter.restore-all");
				Route::delete("/payroll-parameter/destroy-all",[EmployePayrollParameterController::class,"destroyAll"])->name("payroll-parameter.destroy-all");
				Route::post("/payroll-parameter/restore/{id}",[EmployePayrollParameterController::class,"restore"])->name("payroll-parameter.restore");
				Route::get('/payroll-parameter/export/{type}', [EmployePayrollParameterController::class,"export"])->name("payroll-parameter.export");
				Route::get('/payroll-parameter/print',[EmployePayrollParameterController::class,"print"])->name("payroll-parameter.print");
				Route::apiResource("payroll-parameter",EmployePayrollParameterController::class);
			});
		});
		/* KONFIGURASI GAJI */

		/* MODULE MASTER DATA */		
		Route::group(["as" => "master.data."],function(){
			Route::post("/payroll-parameter/restore-all",[PayrollParameterController::class,"restoreAll"])->name("payroll-parameter.restore-all");
			Route::delete("/payroll-parameter/destroy-all",[PayrollParameterController::class,"destroyAll"])->name("payroll-parameter.destroy-all");
			Route::post("/payroll-parameter/restore/{id}",[PayrollParameterController::class,"restore"])->name("payroll-parameter.restore");
			Route::get('/payroll-parameter/export/{type}', [PayrollParameterController::class,"export"])->name("payroll-parameter.export");
			Route::get('/payroll-parameter/print',[PayrollParameterController::class,"print"])->name("payroll-parameter.print");
			Route::apiResource("payroll-parameter",PayrollParameterController::class);

			Route::get("/employe/getcites",[EmployeController::class,"getCites"])->name("employe.getCites");
			Route::get("/employe/getcode",[EmployeController::class,"getCode"])->name("employe.getcode");
			Route::post("/employe/restore-all",[EmployeController::class,"restoreAll"])->name("employe.restore-all");
			Route::delete("/employe/destroy-all",[EmployeController::class,"destroyAll"])->name("employe.destroy-all");
			Route::post("/employe/restore/{id}",[EmployeController::class,"restore"])->name("employe.restore");
			Route::get('/employe/export/{type}', [EmployeController::class,"export"])->name("employe.export");
			Route::get('/employe/print',[EmployeController::class,"print"])->name("employe.print");
			Route::apiResource("employe",EmployeController::class);

			Route::post("/position/restore-all",[PositionController::class,"restoreAll"])->name("position.restore-all");
			Route::delete("/position/destroy-all",[PositionController::class,"destroyAll"])->name("position.destroy-all");
			Route::post("/position/restore/{id}",[PositionController::class,"restore"])->name("position.restore");
			Route::get('/position/export/{type}', [PositionController::class,"export"])->name("position.export");
			Route::get('/position/print',[PositionController::class,"print"])->name("position.print");
			Route::apiResource("position",PositionController::class);
			
			Route::post("/division/restore-all",[DivisionController::class,"restoreAll"])->name("division.restore-all");
			Route::delete("/division/destroy-all",[DivisionController::class,"destroyAll"])->name("division.destroy-all");
			Route::post("/division/restore/{id}",[DivisionController::class,"restore"])->name("division.restore");
			Route::get('/division/export/{type}', [DivisionController::class,"export"])->name("division.export");
			Route::get('/division/print',[DivisionController::class,"print"])->name("division.print");
			Route::apiResource("division",DivisionController::class);
		});
		/* MASTER DATA */
		
		Route::group(["prefix" => "test"],function(){
			Route::get("/",function(){
				return "Hello";
			})->withoutMiddleware(["jwt"]);
		});
    });
});
