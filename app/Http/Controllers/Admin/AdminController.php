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
        return view('layouts.admin-cp.app');
    }
}
