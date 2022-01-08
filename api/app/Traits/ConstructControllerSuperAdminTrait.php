<?php
namespace App\Traits;

trait ConstructControllerSuperAdminTrait{
    /**
     * Superadmin only allow to readonly
    */
    public function __construct() {
        $this->middleware('is-not-super-admin')->except('index');
    }    
}