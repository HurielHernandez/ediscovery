<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use TCG\Voyager\Voyager;
use Carbon\Carbon;
use App\UserCase;
use App\UserFile;
use App\Cases;
use App\Files;
use App\User;

class AdminUserCaseController extends Controller
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

    public function index(Request $request, $id)
    {
        Voyager::can('browse_case_users');

    	$user = Auth::user();

    	$case = Cases::findOrFail($id);

    	$users = $case->user;

    	return view('admin.cases.users.index', compact('case', 'users'));
    }

    public function add(Request $request, $id)
    {
        Voyager::can('add_case_users');

    	$user = Auth::user();

    	$case = Cases::findOrFail($id);

    	$assignedUsers = UserCase::where('case_id', $case->id)->get();

        $users = User::all();

        $assignedUsers = $assignedUsers->keyBy('user_id');

        $filtered = collect();
        foreach($users as $user)
        {
            if(!$assignedUsers->has($user->id) )
                $filtered->push($user);
        }

        $users = $filtered;
      
    	return view('admin.cases.users.add', compact('case', 'users'));
    }

    public function edit(Request $request, $id, $userId)
    {
        Voyager::can('edit_case_users');

        $user = Auth::user();

        $case = Cases::findOrFail($id);

        $users = $case->user->where('id', $userId);

        //dd($users);

        $files = Files::all()->where('case_id', $case->id);

        if(empty($users))
           dd("1");

        if(empty($files))
              dd("2");

        return view('admin.cases.users.edit', compact('case', 'users', 'files'));
    }

    public function update(Request $request, $id, $userId)
    {
        Voyager::can('edit_case_users');

        $user = Auth::user();

        $case = Cases::findOrFail($id);

        $userToUpdate = User::findOrFail($userId);

        $access_on = Carbon::parse($request->access_on);

        $revoke_on = Carbon::parse($request->revoke_on);

        UserCase::where('case_id', $case->id)
                ->where('user_id', $userToUpdate->id)
                ->update(['access_on' => $access_on,
                          'revoke_on' => $revoke_on
                        ]);
    
        return redirect()
            ->action('AdminUserCaseController@index', ['id' => $case ])
            ->with([
                'message'    => 'Successfully updated user access',
                'alert-type' => 'success',
            ]);

    }

    public function revoke(Request $request, $id, $userId)
    {
        Voyager::can('edit_case_users');
        
        $user = Auth::user();

        $case = Cases::findOrFail($id);

        $userToUpdate = User::findOrFail($userId);

        $access_on = Carbon::now();

        $revoke_on = Carbon::now();

        UserCase::where('case_id', $case->id)
                ->where('user_id', $userToUpdate->id)
                ->update(['access_on' => $access_on,
                          'revoke_on' => $revoke_on
                        ]);
    
        return redirect()
            ->action('AdminUserCaseController@index', ['id' => $case ])
            ->with([
                'message'    => 'Successfully updated user access',
                'alert-type' => 'success',
            ]);

    }

    public function grant(Request $request, $id, $user)
    {
        Voyager::can('add_case_users');

    	$Authuser = Auth::user();

    	$case = Cases::findOrFail($id);

    	$files = Files::all()->where('case_id', $id);

    	$userToAdd = User::findOrFail($user);

    	foreach($files as $file)
    	{

	    	$fileCount = new UserFile;

	    	$fileCount->file_id = $file->id;
	    	$fileCount->user_id = $userToAdd->id;
	    	$fileCount->access_count = 0;

	    	$fileCount->save();

    	}

    	$access = new UserCase;

    	$access->case_id = $case->id;
    	$access->user_id = $userToAdd->id;
    	$access->access_on = Carbon::now();
    	$access->revoke_on = Carbon::now()->addDays(30);
    	$access->access_by = $Authuser->id;

    	$access->save();

    	return back()->with([
            'message'=>'Access Granted',
            'alert-type'=> 'success']);

    }

    public function increase(Request $request, $id, $userId, $fileId)
    {
        Voyager::can('edit_case_users');

        $Authuser = Auth::user();

        $case = Cases::findOrFail($id);

        $file = Files::findOrFail($fileId);

        $userToUpdate = User::findOrFail($userId);

        $file_access = UserFile::all()->where('user_id', $userToUpdate->id)
                                ->where('file_id', $file->id)->first();

        if(!$file_access)
        {
            $fileCount = new UserFile;

            $fileCount->file_id = $file->id;
            $fileCount->user_id = $userToUpdate->id;
            $fileCount->access_count = 0;

            $fileCount->save();

            return back()->with(['message' => 'File access increased',
                                 'alert-type' => 'success']);     
        }

        $count = $file_access->access_count - 1;

        UserFile::where('user_id', $userId)
                ->where('file_id', $fileId)
                ->update(['access_count' => $count]);

        return back()->with(['message' => 'File access increased',
                             'alert-type' => 'success']);

    }


    public function decrease(Request $request, $id, $userId, $fileId)
    {
        Voyager::can('edit_case_users');

        $Authuser = Auth::user();

        $case = Cases::findOrFail($id);

        $file = Files::findOrFail($fileId);

        $userToUpdate = User::findOrFail($userId);

        $file_access = UserFile::all()->where('user_id', $userToUpdate->id)
                                ->where('file_id', $file->id)->first();

        if(!$file_access)
        {
            return back()->with(['message' => 'File access cannot be decreased',
                                 'alert-type' => 'error']);     
        }

        $count = $file_access->access_count + 1;

        if($count <= 2)
        {
            UserFile::where('user_id', $userId)
                ->where('file_id', $fileId)
                ->update(['access_count' => $count]);

            return back()->with(['message' => 'File access decrease',
                             'alert-type' => 'success']);
        }

        return back()->with(['message' => 'File access cannot be decreased',
                                 'alert-type' => 'error']);  

    }
}
