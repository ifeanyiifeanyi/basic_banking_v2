<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    public function index(){
        return view('members.profile.index', ['user' => request()->user()]);
    }
}
