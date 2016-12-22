<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function Show($id)
    {
    	return "<h1>$id</h1>";
    }
}
