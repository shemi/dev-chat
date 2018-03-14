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
            'name' => $user->name,
            'image' => $user->profile_image,
            'username' => $user->username
        ];
    }
}