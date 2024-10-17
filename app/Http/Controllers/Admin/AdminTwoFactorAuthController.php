<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RecoveryCodeUsed;
use App\Notifications\TwoFactorEnabled;
use App\Notifications\TwoFactorDisabled;

class AdminTwoFactorAuthController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
        // $this->middleware('auth');
    }

    public function show2faForm()
    {
        $user = request()->user();

        if (!$user->two_factor_secret) {
            $secretKey = $this->google2fa->generateSecretKey();
            $user->two_factor_secret = $secretKey;
            $user->save();
        }

        $qrCodeData = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        // Generate recovery codes if they don't exist
        if (empty($user->getRecoveryCodes())) {
            $user->setRecoveryCodes($this->generateRecoveryCodes());
            $user->save();
        }

        return view('admin.2fa.index', [
            'user' => $user,
            'qrCodeData' => $qrCodeData,
            'secretKey' => $user->two_factor_secret,
            'recoveryCodes' => $user->getRecoveryCodes()
        ]);
    }

    public function enable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = request()->user();

        if ($this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->two_factor_enabled = 'enabled';
            $user->save();

            // Send notification
            $user->notify(new TwoFactorEnabled);

            // Log the event using the activity logger
            activity()
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('Two-factor authentication was enabled');


            return redirect()->route('admin.2fa.index')
                ->with('success', 'Two-factor authentication has been enabled.');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    // public function disable2fa(Request $request)
    // {
    //     $user = request()->user();

    //     if ($request->has('recovery_code')) {
    //         if (!$this->validateRecoveryCode($user, $request->recovery_code)) {
    //             return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
    //         }
    //     } else {
    //         $request->validate([
    //             'current_password' => 'required|string',
    //             'code' => 'required|string|size:6'
    //         ]);

    //         if (!Hash::check($request->current_password, $user->password)) {
    //             return back()->withErrors(['current_password' => 'The password is incorrect.']);
    //         }

    //         if (!$this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
    //             return back()->withErrors(['code' => 'Invalid verification code.']);
    //         }
    //     }

    //     $user->two_factor_enabled = 'disabled';
    //     $user->two_factor_secret = null;
    //     $user->two_factor_recovery_codes = null;
    //     $user->save();

    //     // Send notification
    //     $user->notify(new TwoFactorDisabled);

    //     // Log the event
    //     activity()
    //         ->causedBy($user)
    //         ->log('2FA was disabled');

    //     return redirect()->route('profile.2fa')
    //         ->with('success', 'Two-factor authentication has been disabled.');
    // }

    public function disable2fa(Request $request)
    {
        $user = request()->user();

        if ($request->has('recovery_code')) {
            if (!$user->useRecoveryCode($request->recovery_code)) {
                return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
            }
        } else {
            $request->validate([
                'current_password' => 'required|string',
                'code' => 'required|string|size:6'
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The password is incorrect.']);
            }

            if (!$this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
                return back()->withErrors(['code' => 'Invalid verification code.']);
            }
        }

        $user->two_factor_enabled = 'disabled';
        $user->two_factor_secret = null;
        $user->setRecoveryCodes([]);
        $user->save();

        // Send notification
        $user->notify(new TwoFactorDisabled);

        // Log the event
        // Log the event using the activity logger
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Two-factor authentication was disabled');


        return redirect()->route('profile.2fa')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    public function showVerifyForm()
    {
        return view('auth.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = request()->user();

        if ($this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $request->session()->put('2fa_verified', true);

            // Update last verified timestamp
            $user->two_factor_verified_at = now();
            $user->save();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function verifyWithRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string'
        ]);

        $user = request()->user();

        if ($user->useRecoveryCode($request->recovery_code)) {
            $request->session()->put('2fa_verified', true);

            // Generate a new recovery code to replace the used one
            $newCodes = $user->getRecoveryCodes();
            $newCodes[] = Str::random(10);
            $user->setRecoveryCodes($newCodes);
            $user->two_factor_verified_at = now();
            $user->save();

            // Notify user that a recovery code was used
            $user->notify(new RecoveryCodeUsed);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Recovery code accepted. A new recovery code has been generated.');
        }

        return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
    }

    protected function generateRecoveryCodes()
    {
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10);
        }
        return $recoveryCodes;
    }
}
