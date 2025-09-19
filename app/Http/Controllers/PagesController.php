<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Login page
     */
    public function login()
    {
        return view('pages.login');
    }

    /**
     * Register page
     */
    public function register()
    {
        return view('pages.register');
    }

    /**
     * Sample page
     */
    public function samplePage()
    {
        return view('other.sample-page');
    }
}
