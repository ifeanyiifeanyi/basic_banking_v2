<?php

namespace App;

use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Request;

trait LoginTracker
{
    /**
     * Update user login information on successful login
     */
    public function updateLoginInfo(): void
    {
        $ip = $this->getClientIp();

        $this->update([
            'last_login' => Carbon::now(),
            'last_ip' => $ip,
            'login_attempts' => 0,
            'failed_login_attempts' => 0,
            'lockout_until' => null
        ]);
    }

    protected function getClientIp(): string
    {
        // Check for IP from trusted proxies/load balancers
        foreach (
            [
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
            ] as $key
        ) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    // Validate IP address
                    if (filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    ) !== false) {
                        return $ip;
                    }
                }
            }
        }

        // Fallback to request IP
        return request()->ip() ?? '0.0.0.0';
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedLoginAttempts(): void
    {
        $this->increment('failed_login_attempts');
        $this->increment('login_attempts');

        // If failed attempts exceed threshold, implement lockout
        if ($this->failed_login_attempts >= 5) {
            $this->update([
                'lockout_until' => Carbon::now()->addMinutes(15)
            ]);
        }
    }

    /**
     * Check if user is locked out
     */
    public function isLockedOut(): bool
    {
        if ($this->lockout_until && Carbon::now()->lt($this->lockout_until)) {
            return true;
        }
        return false;
    }
}
