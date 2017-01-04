<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use TCG\Voyager\Voyager;
use App\Cases;
use App\UserFile;
use App\UserCase;
use App\Files;
use Storage;
class AdminCaseController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'admin.user']);
	}

    public function index()
    {
        Voyager::can('browse_cases');

        $user = Auth::user();

    	$cases = Cases::all();

    	return view('admin.cases.index', compact('cases'));
    }

    public function create()
    {
        Voyager::can('add_cases');

    	$user = Auth::user();

    	$dataType = new Cases;

    	return view('admin.cases.create', compact('dataType'));
    }

    public function show($id)
    {
        Voyager::can('read_cases');

        $user = Auth::user();

    	$case = Cases::findOrFail($id);

    	return view('admin.cases.show', compact('case'));
    }

    public function post(Request $request)
    {
        Voyager::can('add_cases');

    	$user = Auth::user();
    	$case = new Cases;
    	$case->case_number = $request->case_number;
    	$case->last_name = $request->last_name;
    	$case->first_name = $request->first_name;
    	$case->jn = $request->jn;
    	$case->sid = $request->sid;
    	$case->offense_description = $request->offense_description;
    	$case->created_by = $user->id;
    	$case->save();

    	return redirect()
    		->action('AdminCaseController@show', ['id' => $case->id])
    		->with([
				'message' => 'Successfully Created Case',
				'alert-type' => 'success'
				]);
    }

    public function edit($id)
    {
        Voyager::can('edit_cases');

        $user = Auth::user();

        $case = Cases::findOrFail($id);

        return view('admin.cases.edit', compact('case'));
    }


    public function update(Request $request)
    {

        Voyager::can('edit_cases');

        $user = Auth::user();

        $case = Cases::findOrFail($request->id);

        $case->case_number = $request->case_number;
        $case->last_name = $request->last_name;
        $case->first_name = $request->first_name;
        $case->jn = $request->jn;
        $case->sid = $request->sid;
        $case->offense_description = $request->offense_description;
        $case->created_by = $user->id;
        $case->save();

        return redirect()
            ->action('AdminCaseController@show', ['id' => $case->id])
            ->with([
                'message' => 'Successfully Update Case',
                'alert-type' => 'success'
                ]);

    }

    public function destroy($id)
    {
        Voyager::can('delete_cases');

    	$user = Auth::user();

        $case = Cases::findOrFail($id);

        $files = Files::where('case_id', $case->id)->get();

        foreach($files as $file)
        {
            UserFile::where('file_id', $file->id)->delete();

            $file->delete();
        }

        UserCase::where('case_id', $case->id)->delete();

        Storage::deleteDirectory('/cases/'.$case->id);

    	Cases::destroy($case->id);

    	 return back()->with([
            'message'    => 'Successfully Deleted Case',
            'alert-type' => 'success',
        ]);
    }
}
