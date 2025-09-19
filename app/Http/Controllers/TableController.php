<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Bootstrap Tables page
     */
    public function tblBootstrap()
    {
        return view('table.tbl-bootstrap');
    }

    /**
     * Data Tables page
     */
    public function tblDtSimple()
    {
        return view('table.tbl-dt-simple');
    }
}
