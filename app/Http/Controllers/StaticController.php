<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function privacyPolicy()
    {
        return $this->apiResponse(200, 'Privacy Policy', ['content' => 'Privacy Policy content here.']);
    }

    public function terms()
    {
        return $this->apiResponse(200, 'Terms and Conditions', ['content' => 'Terms and Conditions content here.']);
    }
}
