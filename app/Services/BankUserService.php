<?php

namespace App\Services;

use App\Models\BankUser;
use App\Models\Transaction;
use App\Models\User;

class BankUserService
{
    public function getAllUsers()
    {
        return User::where('role', 'member')->with('accounts')->get();
    }

    public function getUserById($id)
    {
        return User::with('accounts')->findOrFail($id);
    }

    public function updateUser($id, array $data)
    {
        $user = $this->getUserById($id);
        $user->update($data);
    }

    public function updateBalance($id, $amount, $type)
    {
        $user = $this->getUserById($id);
        if ($type === 'add') {
            $user->balance += $amount;
        } else {
            $user->balance -= $amount;
        }
        $user->save();

        // Transaction::create([
        //     'bank_user_id' => $user->id,
        //     'amount' => $amount,
        //     'type' => $type === 'add' ? 'deposit' : 'withdrawal',
        //     'transaction_date' => now(),
        // ]);
    }

    public function toggleSuspension($id)
    {
        $user = $this->getUserById($id);
        $user->account_status = !$user->account_status;
        $user->save();
    }

    public function toggleTransferAbility($id)
    {
        $user = $this->getUserById($id);
        $user->can_transfer = !$user->can_transfer;
        $user->save();
    }

    public function toggleReceiveAbility($id)
    {
        $user = $this->getUserById($id);
        $user->can_receive = !$user->can_receive;
        $user->save();
    }

    public function archiveUser($id)
    {
        $user = $this->getUserById($id);
        $user->is_archived = true;
        $user->save();
    }

    public function unarchiveUser($id)
    {
        $user = $this->getUserById($id);
        $user->is_archived = false;
        $user->save();
    }
}
