<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'bar_number', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates =['access_on'];


    public function cases()
    {
        
        return $this->belongsToMany('App\Cases', 'user_case', 'case_id', 'user_id')
                    ->withPivot('access_on', 'revoke_on');
    }

    public function files()
    {
        
        return $this->belongsToMany('App\Files', 'user_file', 'file_id', 'user_id')
                    ->withPivot('access_count');
    }

    public function getIsAdminAttribute()
    {
        return( $this->role_id == 1);    
    }
}
