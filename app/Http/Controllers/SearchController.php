<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use App\User;
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

        $users = User::where('username', 'like', $name.'%')
            ->whereNotIn('id', [$currentUserId])
            ->limit(10)
            ->get();

        return $this->response(UserTransformer::transform($users));
    }

}
