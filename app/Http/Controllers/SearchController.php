<?php

namespace App\Http\Controllers;

use App\Transformers\SearchUserTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function search($query)
    {
        $query = trim($query);

        if(! $query) {
            return $this->response([]);
        }

        if(starts_with($query, '@')) {
            return $this->usernameSearch($query);
        }

        return $this->response([]);
    }


    protected function usernameSearch($name)
    {
        $name = trim(trim($name, '@'));
        $currentUserId = auth()->id();

        if(! $name) {
            return $this->response([]);
        }

        /** @var Collection $users */
        $users = User::where('username', 'like', $name.'%')
            ->whereNotIn('id', [$currentUserId])
            ->with(['media', 'conversations' => function($query) use($currentUserId) {
                $query->whereHas('users', function($query) use($currentUserId) {
                        $query->where('user_id', $currentUserId);
                    })->where('is_group', 0);
            }])
            ->limit(10)
            ->get();

        $users = $users->sort(function(User $a, User $b) {
            if ($a->conversations->count() === $b->conversations->count()) {
                if($a->name === $b->name) {
                    return 0;
                }

                return $a->name < $b->name ? -1 : 1;
            }

            return ($a->conversations->count() < $b->conversations->count()) ? 1 : -1;
        });

        return $this->response(SearchUserTransformer::transform($users));
    }

}
