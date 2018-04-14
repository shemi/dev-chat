<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Message;
use App\Transformers\ConversationTransformer;
use App\Transformers\MessageTransformer;
use App\User;
use Colors\RandomColor;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|string|min:2|max:100',
            'group' => 'required|boolean',
            'users' => 'required|array|min:1'
        ]);

        $currentUser = auth()->user();

        $usersIds = collect($request->input('users'))
            ->pluck('id')
            ->transform(function($publicId) {
                return User::decodePublicId($publicId);
            });

        $users = User::whereIn('id', $usersIds->toArray())
            ->get();

        $users->push($currentUser);

        $isGroup = $users->count() > 2;

        //TODO: if is not group and the current user has conversation with the user return the exists conversation

        $conversation = new Conversation();
        $conversation->is_group = $isGroup;

        if($isGroup) {
            $conversation->name = $request->input('name');
        }

        $conversation->save();

        $colors = RandomColor::many($users->count(), [
            'format' => 'hex',
            'luminosity' => 'light',
            'prng' => function($min, $max) {
                return random_int($min, $max);
            }
        ]);

        $usersToAttach = [];

        foreach ($users as $index => $user) {
            $usersToAttach[$user->id] = [
                'color' => $colors[$index],
                'is_owner' => $user->id === $currentUser->id
            ];
        }

        $conversation->users()->attach($usersToAttach);

        $conversation = $conversation->refresh()
            ->load(['users', 'messages', 'events']);

        return $this->response(
            ConversationTransformer::transform($conversation)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $conversationId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($conversationId, Request $request)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::publicId($conversationId)
            ->with(['users' => function($query) {
                $query->with(['media']);
            }])
            ->firstOrFail();

        $messages = $conversation->messages()
            ->take(21)
            ->offset((int) $request->input('offset', 0))
            ->latest()
            ->get()
            ->each(function(Message $message) use ($conversation) {
                $message->setRelations([
                    'conversation' => $conversation,
                    'user' => $conversation->users->find($message->user_id)
                ]);
            });

        if($hasMore = $messages->count() > 20) {
            $messages->pop();
        }

        return $this->response([
            'hasMore' => $hasMore,
            'messages' => MessageTransformer::transform($messages)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
