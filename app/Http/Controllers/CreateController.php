<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function create_user() {

        return view('academic.createUser');
    }
}
