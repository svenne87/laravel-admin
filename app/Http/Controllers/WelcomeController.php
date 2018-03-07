<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.app');
    }

    /**
     * Show 404 Not Found.
     *
     * @return \Illuminate\Http\Response
     */
    public function notFound()
    {
        return response()
            ->view('errors.404', array(), 404);
    }    
}
