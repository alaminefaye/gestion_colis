<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElementsController extends Controller
{
    /**
     * Typography page
     */
    public function bcTypography()
    {
        return view('elements.bc-typography');
    }

    /**
     * Colors page
     */
    public function bcColor()
    {
        return view('elements.bc-color');
    }

    /**
     * Icons page
     */
    public function iconTabler()
    {
        return view('elements.icon-tabler');
    }

    /**
     * Buttons page
     */
    public function bcButton()
    {
        return view('elements.bc-button');
    }

    /**
     * Cards page
     */
    public function bcCard()
    {
        return view('elements.bc-card');
    }

    /**
     * Badges page
     */
    public function bcBadges()
    {
        return view('elements.bc-badges');
    }

    /**
     * Alerts page
     */
    public function bcAlert()
    {
        return view('elements.bc-alert');
    }
}
