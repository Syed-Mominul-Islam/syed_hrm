<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CVController extends Controller
{
    public function form(){
        $users=User::get();
        return view('cv.form',compact('users'));
    }
}
