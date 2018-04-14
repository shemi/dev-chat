<?php

namespace App\Transformers;

use App\User;

class UserTransformer extends Transformer
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
            'color' => optional($user->pivot)->color ?: '',
            'is_owner' => (boolean) optional($user->pivot)->is_owner
        ];
    }
}