<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Apex Charts page
     */
    public function chartApex()
    {
        return view('chart.chart-apex');
    }

    /**
     * Vector Maps page
     */
    public function mapVector()
    {
        return view('chart.map-vector');
    }
}
