<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberUpdateProfile;
use App\Services\UserService;
use Illuminate\Http\Request;
use WisdomDiala\Countrypkg\Models\Country;

class MemberProfileController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    public function index(){
        return view('members.profile.index', ['user' => request()->user(), 'countries' => Country::all()]);
    }

    public function update(MemberUpdateProfile $request){
        $user = request()->user();
        $this->userService->updateProfile($user, $request->validated());


    }
}
