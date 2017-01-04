<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use TCG\Voyager\Voyager;
use PragmaRX\Tracker\Vendor\Laravel\Facade as Tracker;
use App\Cases;
use App\UserCase;

class CaseController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

    public function index()
    {
        Voyager::can('browse_cases');

        $user = Auth::user();

    	$cases = Cases::all();

        Tracker::trackEvent(['event' => 'User.'.$user->id.'.Browse.Cases']);

    	return view('cases.index', compact('cases'));
    }

    public function show($id)
    {
        Voyager::can('read_cases');

        $user = Auth::user();

    	$case = Cases::findOrFail($id);

        $access = UserCase::hasPermission($user->id, $case->id);

    	return view('cases.show', compact('case', 'access'));
    }

}
