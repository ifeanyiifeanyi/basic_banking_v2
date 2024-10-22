<?php

// app/Services/UserService.php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function createUser(array $data)
    {
        $password = "12345678";
        $data['password'] = Hash::make($password);
        $data['username'] = $this->generateUsername($data['first_name'], $data['last_name']);
        $data['email_verified_at'] = now();


        // Handle profile photo if provided
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $photo = $data['photo'];
            $filename = time() . '_' . Str::slug($data['first_name']) . '_' . Str::slug($data['last_name']) . '.' . $photo->getClientOriginalExtension();

            // Move the file to public/users/assets/images/users directory
            $photo->move(public_path('members/photo'), $filename);

            // Save the relative path in database
            $data['photo'] = 'members/photo/' . $filename;
        }

        $user = User::create($data);

        // You might want to send welcome email with password here

        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        return $user->update($data);
    }

    public function toggleStatus(User $user)
    {
        $user->account_status = !$user->account_status;
        $user->save();
        return $user;
    }

    public function resetPassword(User $user)
    {
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->save();

        // You might want to send password reset email here

        return $password;
    }

    protected function generateUsername($firstName, $lastName)
    {
        $baseUsername = strtolower(substr($firstName, 0, 1) . $lastName);
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function updateProfile(User $user, array $data)
    {
        if (isset($data['photo'])) {
            if ($user->photo && !str_contains($user->photo, 'avatar-9.jpg')) {
                Storage::delete($user->photo);
            }
            $data['photo'] = $data['photo']->store('members/photo', 'public');
        }

        $user->update($data);
        return $user;
    }
}
