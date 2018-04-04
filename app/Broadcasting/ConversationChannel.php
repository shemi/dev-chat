<?php

namespace App\Broadcasting;

use App\User;

class ConversationChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User $user
     * @param string $id
     * @return array|bool
     */
    public function join(User $user, $id)
    {
        return true;

        $conversation = \App\Conversation::findByPublicId($id);

        return (boolean) $conversation->users()
            ->select('users.id')
            ->find($user->id);
    }
}
