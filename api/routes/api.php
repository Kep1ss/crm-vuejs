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

	Route::get("/get-setting",[SettingController::class,"index"])->name("get-setting");

    Route::group(["namespace" => "Auth","as" => "auth."],function(){
		Route::post('/login', [AuthController::class,"login"])->name("login");
		Route::post("/forgot-password",[AuthController::class,"forgotPassword"])->name("forgot-password");
		Route::post("/reset-password",[AuthController::class,"resetPassword"])->name("reset-password");

		Route::group(["middleware" => "auth:sanctum"],function(){
			Route::post("/logout",[AuthController::class,'logout'])->name("logout");
			Route::get("/me",[AuthController::class,'me'])->name("me");
		});
	});

    Route::group(["middleware" => "auth:sanctum"],function(){
		// PROFIL 
		Route::put("/profil",[ProfilController::class,"update"])->name("profil.update");
		Route::put("/profil/password",[ProfilController::class,"password"])->name("profil.password");		

		/* MODULE SETTING */
		Route::group(["as" => "setting.","middleware" => "is-super-admin"],function(){        
			Route::post("/user/restore-all",[UserController::class,"restoreAll"])->name("user.restore-all");
			Route::delete("/user/destroy-all",[UserController::class,"destroyAll"])->name("user.destroy-all");
			Route::post("/user/restore/{id}",[UserController::class,"restore"])->name("user.restore");
			Route::get('/user/export/{type}', [UserController::class,"export"])->name("user.export");
			Route::get('/user/print',[UserController::class,"print"])->name("user.print");
			Route::apiResource("user",UserController::class);

			Route::get("/activity",[ActivityController::class,"index"])->name("activity.index");
			Route::get('/activity/export/{type}', [ActivityController::class,"export"])->name("activity.export");
			Route::get('/activity/print',[ActivityController::class,"print"])->name("activity.print");

			Route::get("/setting",[SettingController::class,"index"])->name("index");
        	Route::put("/setting",[SettingController::class,"update"])->name("update");
        	Route::put("/setting/logo",[SettingController::class,"updateLogo"])->name("logo");
		});
    });
});
