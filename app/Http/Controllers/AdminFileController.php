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

class AdminFileController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'admin.user']);
	}

    public function index($id)
    {
    	Voyager::can('browse_files');

    	$case =  Cases::findOrFail($id);

    	$files = Files::all()->where('case_id', $case->id);

    	return view('admin.cases.files.index', compact('case', 'files'));
    }

    public function upload($id)
    {
    	Voyager::can('add_files');

    	$user = Auth::user();

    	$case = Cases::findOrFail($id);

    	return view('admin.cases.files.upload', compact('case'));
    }

   	public function store(Request $request, $id)
	{
		//dd($request->hasFile('files'));
		Voyager::can('add_files');

		$user = Auth::user();

		$case = Cases::findOrFail($id);
		
		//find users already assigned
		$users = UserCase::all()->where('case_id', $case->id);

		$usersWithPermission = new Collection();
		foreach($users as $userC)
		{
			if(UserCase::hasPermission($userC->user_id, $case->id))
			{
				$usersWithPermission->push($userC);
			}
		}

		if ($request->hasFile('files')) {
			$file = $request->file('files');

			foreach($file as $files){
				//get file information
				$filename = $files->getClientOriginalName();
				$filename = explode('.', $filename)[0];
				$extension = $files->getClientOriginalExtension();
				$encryptedName = sha1($filename . time());
				$folder = $case->id;

				//save to database
				$fileToSave = new Files;
				$fileToSave->original_name = $filename;
				$fileToSave->mime = $extension;
				$fileToSave->name = $encryptedName;
				$fileToSave->uploaded_by = $user->id;
				$fileToSave->case_id = $case->id;

				$fileToSave->save();

				//Grant Acces to Users with previous Access
				foreach($usersWithPermission as $user)
				{
					$fileCount = new UserFile;

	    			$fileCount->file_id = $fileToSave->id;
	    			$fileCount->user_id = $user->user_id;
	    			$fileCount->access_count = 0;

	    			$fileCount->save();
	    		}

				//encrypt File
				$encryptedFile = $files;
				$encryptedFile = encrypt(file_get_contents($encryptedFile));

				//move encrypted File
	            Storage::put('/cases/'.$folder.'/'.$encryptedName, $encryptedFile);

				//return Uploaded Links
				$destinationPath =  '/cases/' .$folder. '/';
				$filest = array();
				$filest['name'] = $filename;
				$filest['size'] = filesize($files);
				$filest['url'] = $destinationPath.$encryptedName;
				$filest['thumbnailUrl'] = $destinationPath.$encryptedName;
				$filest['deleteUrl'] = '/admin/cases/'.$folder.'/files/'.$fileToSave->id;
				$filesa['files'][]=$filest;
			}

		return $filesa;
		}
	}

	public function download($case, $file)
	{
		Voyager::can('download_files');

		$user = Auth::user();

		$file = Files::findOrFail($file);

		try{
			$encryptedFile = Storage::get('/cases/'.$case.'/'.$file->name);
		} catch(FileNotFoundException $e)
		{
			return back()->with([
	                'message'    => "File $file->original_name Not Found",
    	            'alert-type' => 'error',
        	    ]);
		}
	
		//increase download count
		if( empty(UserFile::where('user_id', $user->id)->where('file_id', $file->id)->first()) )
		{
			$fileCount = new UserFile;

	    	$fileCount->file_id = $file->id;
			$fileCount->user_id = $user->id;
	    	$fileCount->access_count = 0;

	    	$fileCount->save();
		}
	
		UserFile::increaseDownloadCount($user->id, $file->id);

		//return download
		return response()->make(decrypt($encryptedFile), 200, array(
	    	'Content-Type' => (new finfo(FILEINFO_MIME))->buffer(decrypt($encryptedFile)),
	    	'Content-Disposition' => 'attachment; filename="' .$file->original_name .'.' .$file->mime. '"'
		));
	}

	public function destroy(Request $request, $case, $file)
	{
		Voyager::can('delete_files');

		$user = Auth::user();

		try{
			$file = Files::findOrFail($file);
			
		} catch(ModelNotFoundException $e)
		{
			return back()->with([
				'message' => 'File Not Found',
				'status' => 'error']);
		}

		Storage::delete('/cases/'.$case.'/'.$file->name);

		Files::destroy($file->id);

        return redirect()
        	->action('AdminFileController@index', ['id' => $case])
        	->with([
	            'message'    => 'Successfully Deleted File',
	            'alert-type' => 'success',
        	]);
	}

	public function edit($id, $file)
	{
		Voyager::can('edit_files');

		$user = Auth::user();
		$file = Files::findOrFail($file);
		$case = Cases::findOrFail($id);

		return view('admin.cases.files.edit', compact('case', 'file'));
	}

	public function update(Request $request, $id, $file)
	{
		Voyager::can('edit_files');

		$user = Auth::user();
		$file = Files::findOrFail($file);

		$file->original_name = $request->original_name;
		$file->mime = $request->mime;
		$file->save();

		return redirect()
        	->action('AdminFileController@index', ['id' => $id])
        	->with([
	            'message'    => 'Successfully Updated File',
	            'alert-type' => 'success',
        	]);

	}
}
