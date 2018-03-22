<?php

namespace App\Transformers;

use App\User;

class SearchUserTransformer extends Transformer
{

    /**
     * @param User $user
     * @return array
     */
    public function transformModel($user)
    {
        return [
            'id' => $user->public_id,
            'name' => $user->name,
            'image' => $user->profile_image,
            'username' => $user->username,
            'conversationId' => optional($user->conversations->first())->id
        ];
    }
}