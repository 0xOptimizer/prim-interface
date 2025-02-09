<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    function index() {
        $data = [
            'title' => 'Login'
        ];

        return view('login', $data);
    }
}
