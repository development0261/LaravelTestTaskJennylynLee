<?php 

use App\User;

/**
     * check authenticate user by uuid
     * @param $uuid
     * @return array
     */

function getUserdata($uuid)
{ 
        return User::where("uuid",$uuid)->where("login_status",1)->where("active",1)->first();
}