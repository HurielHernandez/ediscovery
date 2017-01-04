<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    protected $table = 'user_file';

    protected $guarded = [];


    public static function increaseDownloadCount($user, $file)
    {
    	$userFile = UserFile::where('user_id', $user)
    						->where('file_id', $file)
    						->first();
    
    	UserFile::where('user_id', $user)
    			->where('file_id', $file)
    			->update(['access_count' => ($userFile->access_count + 1)]);
 
    }
}
