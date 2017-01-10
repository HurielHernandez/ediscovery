<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Encryption\DecryptException;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use TCG\Voyager\Voyager;
use App\UserCase;
use App\UserFile;
use App\Cases;
use App\Files;
use File;
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

    	return view('admin.cases.files.newUpload', compact('case'));
    }

    public function newUpload(Request $request)
    {	
    	$this->request = $request;

	    return Plupload::receive('file', function ($file)
	    {
	    	$case = explode('/', explode('cases/', $this->request->headers->get('referer'))[1])[0];

	    	$this->storeEncrypt($file, $case);

	        return 'ready';
	    });
    }

    protected function storeEncrypt($file, $case ) 
    {
    	Voyager::can('add_files');

    	$user = Auth::user();

		$case = Cases::findOrFail($case);
		
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

		//get file information
		$filename = $file->getClientOriginalName();
		$filename = explode('.', $filename)[0];
		$extension = $file->getClientOriginalExtension();
		$encryptedName = sha1($filename . time());
		$folder = $case->id;


		//encrypt File
		Storage::put('/cases/'.$folder.'/'.$encryptedName, null);
		$destination = storage_path().'/app/cases/'.$folder.'/'.$encryptedName;
		app('encrypter')->encryptStream($file->path(), $destination);

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
    }

	public function download($case, $file)
	{
		 Voyager::can('download_files');
		 
	 	$user = Auth::user();
	    $file = Files::findOrFail($file);
	    $headers = array();
		$name = $file->original_name;
		$path = storage_path().'/app/cases/'.$case.'/'.$file->name;
	    $finfo = finfo_open(FILEINFO_MIME_TYPE);
	
	    $pathParts = pathinfo($path);
	    // Prepare the headers
	    $headers = array_merge(array(
	        'Content-Description' => 'File Transfer',
	        'Content-Type' => finfo_file($finfo, $path),
	        'Content-Transfer-Encoding' => 'binary',
	        'Expires' => 0,
	        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
	        'Pragma' => 'public',
	        'Content-Length' => File::size($path),
	        'Content-Disposition' => 'attachment; filename=' . $name . '.'. $file->mime
	            ), $headers);
	    finfo_close($finfo);

		$response = new Response('', 200, $headers);

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

	    session_write_close();
	    ob_end_clean();
	    $response->sendHeaders();
	    
	    app('encrypter')->decryptStream($path, null);

	    //Finish off, like Laravel would
	    Event::fire('laravel.done', array($response));
	    $response->foundation->finish();

	    exit;

	    return back()->with([
                'message'    => "File $file->original_name downloaded",
	            'alert-type' => 'success',
    	    ]);
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
