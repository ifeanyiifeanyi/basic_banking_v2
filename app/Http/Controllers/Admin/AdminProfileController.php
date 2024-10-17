<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;

class AdminProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('admin.profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        // dd($user);
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $old_image = $user->photo;

            if (!empty($old_image) && file_exists(public_path($old_image))) {
                unlink(public_path($old_image));
            }

            $thumb = $request->file('photo');
            $extension = $thumb->getClientOriginalExtension();
            $profilePhoto = time() . "." . $extension;
            $thumb->move('admin/profile/', $profilePhoto);
            $data['photo'] = 'admin/profile/' . $profilePhoto;
        } 

        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully');
 
    }

    public function updatePasswordView(){
        return view('admin.profile.password_view');
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user){
        // dd($user);
        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
