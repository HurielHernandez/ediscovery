<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserCase;
use App\UserFile;

class AdminUserController extends Controller
{
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin.user']);
    }
    
    public function delete(Request $request, $id)
    {
    	$user = User::findOrFail($id);

    	$cases = UserCase::where('user_id', $user->id)->delete();

    	$files = UserFile::where('user_id', $user->id)->delete();


    	$user->delete();

    	return back()->with([
    					'message' => 'User Successfully deleted',
    					'alert-type' => 'success']);

    }
}
