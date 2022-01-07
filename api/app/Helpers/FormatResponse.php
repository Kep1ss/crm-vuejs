<?php

namespace App\Helpers;

class FormatResponse{
	/**
     * Handle Custom Error
     *     
     * @return \Illuminate\Http\Response
     */   
	public static function failed($error){		
		if($error->getCode() != 422){
			$errorDataJson = [
				"message" => $error->getMessage(),
				"line" => $error->getLine(),
				"file" => $error->getFile()
			];

			$errorDataString = "\n".
				"\t"."message: ".$error->getMessage()."\n".
				"\t"."line: ".$error->getLine()."\n".
				"\t"."file: ".$error->getFile()."\n";

			\Log::channel("coex")->info($errorDataString);

			$response["message"] = env("APP_DEBUG",true) ? $errorDataJson : "Terjadi Kesalahan";	
		}else{
			$response["message"] = $error->getMessage();
		}

		return response()->json($response,500);
	}	
}