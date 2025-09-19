<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{
    /**
     * Form Elements page
     */
    public function formElements()
    {
        return view('forms.form-elements');
    }

    /**
     * Form Validation page
     */
    public function formValidation()
    {
        return view('forms.form-validation');
    }

    /**
     * Form Wizard page
     */
    public function formWizard()
    {
        return view('forms.form-wizard');
    }
}
