<?php
namespace App\Traits;

use App\Models\User;

trait RoleControllerAdminTrait{
    /**
     * Role admins
     */
    public $role_admins = [        
        User::ROLE_ADMIN_NASIONAL,
        User::ROLE_ADMIN_AREA,
        User::ROLE_ADMIN_KAPER    
    ];

    /**
     * Get current user id for role admins
     */
    public function getCurrentUserId(){
        return in_array(auth()->user()->role,$this->role_admins) 
            ? auth()->user()->parent_id 
            : auth()->user()->id;
    }
}