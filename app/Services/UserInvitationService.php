<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\UserInvitationNotification;
use Illuminate\Support\Str;

class UserInvitationService
{
    /**
     * Create an invitation code, persist it on the user with expiry, and send the invitation email.
     */
    public function sendInvitation(User $user): void
    {
        $token = Str::random(48);

        $user->invitation_code = $token;
        $user->invitation_expires = now()->addDay();
        $user->save();

        $user->notify(new UserInvitationNotification($token, $user->invitation_expires));
    }
}
