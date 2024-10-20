<?php

namespace App\Services;

use App\Models\User;
use App\Models\Account;
use App\Models\BankUser;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BankUserService
{

    public function getAllUsers()
    {
        return User::where('role', 'member')->with('accounts')->latest()->get();
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




    /**
     * Suspend a specific account number
     */
    public function suspendAccount($accountId)
    {
        return DB::transaction(function () use ($accountId) {
            $account = Account::findOrFail($accountId);
            $account->update([
                'is_suspended' => true,
                'suspension_reason' => request('reason'),
                'suspended_at' => now(),
                'suspended_by' => Auth::id()
            ]);

            // Log the suspension
            activity()
                ->performedOn($account)
                ->causedBy(request()->user())
                ->log('Account suspended');

            return $account;
        });
    }

    /**
     * Reactivate a specific account number
     */
    public function reactivateAccount($accountId)
    {
        return DB::transaction(function () use ($accountId) {
            $account = Account::findOrFail($accountId);
            $account->update([
                'is_suspended' => false,
                'suspension_reason' => null,
                'suspended_at' => null,
                'suspended_by' => null,
                'reactivated_at' => now(),
                'reactivated_by' => Auth::id()
            ]);

            activity()
                ->performedOn($account)
                ->causedBy(request()->user())
                ->log('Account reactivated');

            return $account;
        });
    }

    /**
     * Suspend all user accounts and user access
     */
    public function suspendUser($userId, $reason)
    {
        return DB::transaction(function () use ($userId, $reason) {
            $user = $this->getUserById($userId);

            // Suspend all accounts
            $user->accounts()->update([
                'is_suspended' => true,
                'suspension_reason' => $reason,
                'suspended_at' => now(),
                'suspended_by' => Auth::id()
            ]);

            // Suspend user access
            $user->update([
                'account_status' => false,
                'can_transfer' => false,
                'can_receive' => false,
                'suspension_reason' => $reason,
                'suspended_at' => now(),
                'suspended_by' => Auth::id()
            ]);

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log('User suspended with all accounts');

            return $user;
        });
    }

    public function reactivateUser($userId, $reason = null)
    {
        return DB::transaction(function () use ($userId, $reason) {
            $user = $this->getUserById($userId);

            // Reactivate all accounts
            $user->accounts()->update([
                'is_suspended' => false,
                'suspension_reason' => null,
                'suspended_at' => null,
                'suspended_by' => null,
                'reactivated_at' => now(),
                'reactivated_by' => Auth::id()

            ]);

            // Reactivate user access
            $user->update([
                'account_status' => true,
                'can_transfer' => true,
                'can_receive' => true,
                'suspension_reason' => null,
                'suspended_at' => null,
                'suspended_by' => null,
            ]);

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log('User reactivated with all accounts');

            return $user;
        });
    }


    /**
     * Archive user and all their accounts
     */
    public function archiveUser($userId)
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->getUserById($userId);

            // Archive all accounts
            $user->accounts()->delete(); // Soft delete

            // Archive user
            $user->update([
                'is_archived' => true,
                'archived_at' => now(),
                'archived_by' => Auth::id(),
                'account_status' => false
            ]);

            $user->delete(); // Soft delete

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log('User archived');

            return $user;
        });
    }

    /**
     * Restore user from archive
     */
    public function restoreUser($userId)
    {
        return DB::transaction(function () use ($userId) {
            $user = User::withTrashed()->findOrFail($userId);

            // Restore accounts
            $user->accounts()->restore();

            // Restore user
            $user->update([
                'is_archived' => false,
                'archived_at' => null,
                'archived_by' => null,
                'account_status' => true
            ]);

            $user->restore();

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log('User restored from archive');

            return $user;
        });
    }

    /**
     * Get archived users with their accounts
     */
    public function getArchivedUsers()
    {
        return User::onlyTrashed()
            ->where('role', 'member')
            ->with(['accounts' => function ($query) {
                $query->onlyTrashed();
            }])
            ->get();
    }

    public function toggleTransferAbility($userId)
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->getUserById($userId);
            $user->update([
                'can_transfer' => !$user->can_transfer
            ]);

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log($user->can_transfer ? 'Transfer ability enabled' : 'Transfer ability disabled');

            return $user;
        });
    }

    public function toggleReceiveAbility($userId)
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->getUserById($userId);
            $user->update([
                'can_receive' => !$user->can_receive
            ]);

            activity()
                ->performedOn($user)
                ->causedBy(request()->user())
                ->log($user->can_receive ? 'Receive ability enabled' : 'Receive ability disabled');

            return $user;
        });
    }
}
