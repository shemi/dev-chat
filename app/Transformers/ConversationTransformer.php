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

        $lastMessages = optional($conversation->lastMessage)->transform();
        $messages = collect([]);

        if($conversation->relationLoaded('messages')) {
            $messages = MessageTransformer::transform($conversation->messages);
        } else if($lastMessages) {
            $messages = $messages->push($lastMessages);
        }

        return [
            'conversationId' => $conversation->public_id,
            'name' => $conversation->is_group ? $conversation->name : $contacts->first()->name,
            'lastMessage' => $lastMessages,
            'newMessageCount' => $conversation->getAttribute('new_messages_count'),
            'lastMessageAt' => $this->formatDate($conversation->last_message_at),
            'messages' => $messages,
            'contacts' => UserTransformer::transform($conversation->users),
            'image' => $conversation->is_group ? null : $contacts->first()->profile_image,
            'isGroup' => $conversation->is_group
        ];
    }
}