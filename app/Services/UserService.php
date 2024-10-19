<?php

// app/Services/UserService.php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $data)
    {
        $password = "12345678";
        $data['password'] = Hash::make($password);
        $data['username'] = $this->generateUsername($data['first_name'], $data['last_name']);

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
}
