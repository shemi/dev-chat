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
        return [
            'name' => $conversation->name,
            'lastMessage' => optional($conversation->last_message)->transform(),
            'lastMessageAt' => $this->formatDate($conversation->last_message_at)
        ];
    }
}