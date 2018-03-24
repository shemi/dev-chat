<?php

namespace App\Http\Controllers;

use App\Transformers\ConversationTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function start(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $conversations = $user
            ->conversations()
            ->with(['users', 'messages' => function($query) {
                $query->oldest();
            }])
            ->latest()
            ->get();

        return $this->response([
            'user' => UserTransformer::transform($user),
            'conversations' => ConversationTransformer::transform($conversations)
        ]);
    }

}
