<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserCase extends Model
{
    protected $table = 'user_case';

    protected $dates = [
        'access_on',
        'revoke_on'
    ];


    public static function hasPermission($user, $case)
    {
    	$userCase = UserCase::all()->where('user_id', $user)->where('case_id', $case)->first();

    	$date = Carbon::now();

    	if(empty($userCase))
    		return false;

    	return $userCase->access_on < $date && $userCase->revoke_on > $date ;

    }

}
