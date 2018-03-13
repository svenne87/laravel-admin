<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show the index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $baseUrl = array('url' => 'sss'); //   URL::to('/');
        return view('layouts.admin-cp.app', compact('baseUrl'));
    }
}
