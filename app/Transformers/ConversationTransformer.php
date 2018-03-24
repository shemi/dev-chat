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
            'lastMessage' => optional($conversation->messages->last())->transform(),
            'lastMessageAt' => $this->formatDate(optional($conversation->messages->last())->created_at),
            'messages' => MessageTransformer::transform($conversation->messages),
            'contacts' => UserTransformer::transform($contacts),
            'image' => $conversation->is_group ? null : $contacts->first()->profile_image,
            'isGroup' => $conversation->is_group
        ];
    }
}