<?php

namespace App\Transformers;

use App\Conversation;
use App\Message;
use App\User;

class MessageTransformer extends Transformer
{

    /**
     * @param Message $message
     * @return array
     */
    public function transformModel($message)
    {
        return [
            'id' => $message->public_id,
            'body' => $message->body,
            'by' => UserTransformer::transform($message->user),
            'type' => $message->type,
            'createdAt' => $this->formatDate($message->created_at),
            'sent' => true
        ];
    }
}