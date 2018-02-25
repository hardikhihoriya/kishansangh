<?php

namespace App\Helpers;
use App\User;
use Config;

Class Common {
    
    /**
     * To get total registred users count
     * @return integer [User count]
     */
    public static function getTotalRegistrationCount() {
        return User::where('deleted', '<>', Config::get('constant.DELETED_FLAG'))->count();
    }
    
    public static function getTotalCount($role) {
        if($role === null) {
            return 0;
        }
        return $role->users()->where('deleted', '<>', Config::get('constant.DELETED_FLAG'))->count();
    }
    
    public static function getUserWithoutGivenRole($role) {
        if($role === null) {
            return [];
        }
        $customerUser = $role->users()->where('deleted', '<>', Config::get('constant.DELETED_FLAG'))->pluck('users.id')->toArray();
        $user = User::all([
            'first_name',
            'middle_name',
            'last_name',
            'id'
        ]);
        foreach ($user as $key => $_user) {
            if(in_array($_user->id, $customerUser)) {
                unset($user[$key]);
            }
        }
        return $user;
    }
    
    /**
     * To add http if not exist in url
     * @param string $url
     * @return string
     */
    public static function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public static function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }
    
    public static function in_array($needle, $haystack, $strict = false) {
        foreach ($haystack as $key => $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array($needle, $item, $strict))) {
                return $key;
            }
        }
        return false;
    }
    
}
