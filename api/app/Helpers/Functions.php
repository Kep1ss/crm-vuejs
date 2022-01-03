<?php
use App\Models\Setting;

function func_umk(){
    return Setting::select("value")->where("name","umk")->first()->value;
}