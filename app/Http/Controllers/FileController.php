<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use TCG\Voyager\Voyager;
use App\UserCase;
use App\UserFile;
use App\Cases;
use App\Files;
use Storage;
use finfo;

class FileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index($id)
    {
    	Voyager::can('browse_files');

    	$user = Auth::user();

    	$case =  Cases::findOrFail($id);

    	$files = Files::all()->where('case_id', $case->id);

    	if(!UserCase::hasPermission($user->id, $id))
    		return back()->with(['message' => 'No Access or Access expired', 'alert-type' => 'error']);

    	return view('cases.files.index', compact('case', 'files'));
    }

	public function download($case, $file)
	{
		Voyager::can('download_files');

		$user = Auth::user();

		$file = Files::findOrFail($file);

		//check case permission
		if( !UserCase::hasPermission($user->id, $case) )
			return back()->with([
	                'message'    => "Not Authorized",
    	            'alert-type' => 'error',
        	    ]);;

		//find physical file
		try{
			$encryptedFile = Storage::get('/cases/'.$case.'/'.$file->name);
		} catch(FileNotFoundException $e)
		{
			return back()->with([
	                'message'    => "File $file->original_name Not Found",
    	            'alert-type' => 'error',
        	    ]);
		}
	
		//check file downloads remaining
		if($file->count() <= 0)
			return back()->with([
	                'message'    => "Download limit reached",
    	            'alert-type' => 'error',
        	    ]);

		//increase download count
		UserFile::increaseDownloadCount($user->id, $file->id);

		//return download
		return response()->make(decrypt($encryptedFile), 200, array(
	    	'Content-Type' => (new finfo(FILEINFO_MIME))->buffer(decrypt($encryptedFile)),
	    	'Content-Disposition' => 'attachment; filename="' .$file->original_name .'.' .$file->mime. '"'
		));
	}

}
