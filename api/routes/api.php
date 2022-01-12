<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profil\ProfilController;
use App\Http\Controllers\Setting\{
	UserController,
	ActivityController,
	SettingController,
	DownloadCatalogController,
	AnnouncementController
};
use App\Http\Controllers\MasterData\{
	AccountController,
	ProvinceController,
	CityController,
	DistrictController,
	SchoolController
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
	// STATUS
    Route::get('/status', function () use ($version) {
		return response([
			'message' => 'active (running)',
			'version' => $version
		]);
	})->name("status");

	// GET SETTING
	Route::get("/get-setting",[SettingController::class,"index"])->name("get-setting");

	// MODULE AUTH
    Route::group(["namespace" => "Auth","as" => "auth."],function(){
		Route::post('/login', [AuthController::class,"login"])->name("login");
		Route::post("/forgot-password",[AuthController::class,"forgotPassword"])->name("forgot-password");
		Route::post("/reset-password",[AuthController::class,"resetPassword"])->name("reset-password");

		Route::group(["middleware" => "auth:sanctum"],function(){
			Route::post("/logout",[AuthController::class,'logout'])->name("logout");
			Route::get("/me",[AuthController::class,'me'])->name("me");
		});
	});


	// 	/* MODULE SETTING PRINT AND EXPORT */
	// Route::group(["middleware" => "is-login"],function(){
	// 	Route::group(["as" => "setting.","middleware" => "is-super-admin"],function(){
	// 		Route::get('/user/export/{type}', [UserController::class,"export"])->name("user.export");
	// 		Route::get('/user/print',[UserController::class,"print"])->name("user.print");
	// 	});
	// });

    Route::group(["middleware" => ["auth:sanctum"]],function(){
		// DASHBOARD

		// PROFIL
		Route::put("/profil",[ProfilController::class,"update"])->name("profil.update");
		Route::put("/profil/password",[ProfilController::class,"password"])->name("profil.password");

		/* MODULE SETTING */
		Route::group(["as" => "setting."],function(){
			Route::group(["middleware" => "is-not-super-admin"],function(){
				Route::post("/announcement/restore-all",[AnnouncementController::class,"restoreAll"])->name("announcement.restore-all");
				Route::delete("/announcement/destroy-all",[AnnouncementController::class,"destroyAll"])->name("announcement.destroy-all");
				Route::post("/announcement/restore/{id}",[AnnouncementController::class,"restore"])->name("announcement.restore");
				Route::apiResource("announcement",AnnouncementController::class);
			});

			Route::group(["middleware" => "is-super-admin"],function(){
				Route::apiResource("user",UserController::class)->only(["index","store","update"]);

				Route::post("/download-catalog/restore-all",[DownloadCatalogController::class,"restoreAll"])->name("download-catalog.restore-all");
				Route::delete("/download-catalog/destroy-all",[DownloadCatalogController::class,"destroyAll"])->name("download-catalog.destroy-all");
				Route::post("/download-catalog/restore/{id}",[DownloadCatalogController::class,"restore"])->name("download-catalog.restore");
				Route::apiResource("download-catalog",DownloadCatalogController::class);

				Route::get("/activity",[ActivityController::class,"index"])->name("activity.index");

				Route::get("/setting",[SettingController::class,"index"])->name("index");
        		Route::put("/setting",[SettingController::class,"update"])->name("update");
        		Route::put("/setting/logo",[SettingController::class,"updateLogo"])->name("logo");
			});
		});

		/* MODULE MASTER DATA */
		Route::group(["as" => "master.data.","middleware" => "is-not-super-admin"],function(){
			Route::apiResource("account",AccountController::class);

			Route::apiResource("province",ProvinceController::class)->only("index","store","update");

			Route::apiResource("city",CityController::class)->only("index","store","update");

			Route::apiResource("district",DistrictController::class)->only("index","store","update");
            Route::apiResource("manager-area",AccountController::class);
			Route::post("/school/get/dapodik",[SchoolController::class,"getSchool"])->name("school.get-dapodik");
			Route::post("/school/save/dapodik",[SchoolController::class,"saveSchool"])->name("school.save-dapodik");
			Route::apiResource("school",SchoolController::class)->only("index","store","update");
		});

		/* MODULE ACTIVITY */
		Route::group(["as" => "activity.","middleware" => "is-not-super-admin"],function(){

		});

		/* MODULE ANALYSIS DATA */
		Route::group(["as" => "analysis.data","middleware" => "is-not-super-admin"],function(){

		});
    });
});
