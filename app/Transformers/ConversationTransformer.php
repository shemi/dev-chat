<?php

namespace App\Transformers;

use App\Conversation;
use App\Message;
use App\User;

class ConversationTransformer extends Transformer
{

    /**
     * @param Conversation $conversation
     * @return array
     */
    public function transformModel($conversation)
    {
        $contacts = $conversation->users->reject(function(User $user) {
            return $user->id === auth()->id();
        });

        return [
            'conversationId' => $conversation->public_id,
            'name' => $conversation->is_group ? $conversation->name : $contacts->first()->name,
            'lastMessage' => optional($conversation->last_message)->transform(),
            'lastMessageAt' => $this->formatDate($conversation->last_message_at),
            'messages' => (array) [],
            'contacts' => UserTransformer::transform($contacts),
            'image' => null,
            'isGroup' => $conversation->is_group
        ];
    }
}