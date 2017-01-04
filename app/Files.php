<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Files extends Model
{
    protected $table = 'files';

    public function user()
    {
    	return $this->belongsToMany('App\User', 'user_file', 'file_id', 'user_id')
    				->withPivot('access_count');
    }

    public function getFormattedAccessOnAttribute()
    {
    	return $this->access_on;
    }


    public function count($id = 0)
    {
    	$users = $this->user;

    	if($id === 0)
    		$id = Auth::user()->id;

    	if(!empty($users->find($id)))
    		return 2 - $users->find($id)->pivot->access_count;
    	else
    		return 0;

    }

}
