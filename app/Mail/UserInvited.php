<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserInvited extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var User
     */
    protected $invitedUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $invitedUser, User $user)
    {
        $this->user = $user;
        $this->invitedUser = $invitedUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitationRoute = route('invitation.show', [
            'code' => $this->invitedUser->invitation_code,
        ]);

        return $this->markdown('emails.user.invitation')
            ->subject('You are invited to join Monica')
            ->with('userName', $this->user->name)
            ->with('url', $invitationRoute);
    }
}
