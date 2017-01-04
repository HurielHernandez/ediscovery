<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $table = 'cases';

    protected $dates =['access_on'];

    public function user()
    {
    	return $this->belongsToMany('App\User', 'user_case', 'case_id', 'user_id')
    				->withPivot('access_on', 'revoke_on');
    }
}
