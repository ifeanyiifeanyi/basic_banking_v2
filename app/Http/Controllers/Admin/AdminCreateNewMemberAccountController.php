<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCreateNewMemberAccountController extends Controller
{
    public function index(){
        return view('admin.create_new_member_account.index');
    }

    public function store(Request $request){
        
    }
}
