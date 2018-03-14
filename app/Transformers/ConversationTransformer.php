<?php

namespace App\Transformers;

use App\Conversation;
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
            'last_message' => $conversation->last_message,
        ];
    }
}