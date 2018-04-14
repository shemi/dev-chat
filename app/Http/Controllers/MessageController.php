<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageSent;
use App\Message;
use App\Transformers\ConversationTransformer;
use App\Transformers\MessageTransformer;
use App\User;
use Colors\RandomColor;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param $conversationId
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($conversationId, Request $request)
    {
        $this->validate($request, [
            'body' => 'nullable|string|min:1|max:700',
            'type' => 'required|in:1,2,3,4'
        ]);

        /** @var User $user */
        $user = auth()->user();

        /** @var Conversation $conversation */
        $conversation = $user->conversations()
            ->publicId($conversationId)
            ->with('users')
            ->firstOrFail();

        $message = new Message();
        $message->body = $request->input('body');
        $message->user_id = $user->id;
        $message->type = (int) $request->input('type');
        $message->statuses = [
            'read' => [$user->public_id],
            'sent' => $conversation->users->transform(function(User $user) { return $user->public_id; })
        ];
        $message = $conversation->messages()->save($message);

        $conversation->last_message_id = $message->id;
        $conversation->last_message_at = $message->created_at;
        $conversation->save();

        MessageSent::broadcast($message, $conversation)->toOthers();

        return $this->response(MessageTransformer::transform($message));
    }

    public function updateStatuses($conversationId, Request $request)
    {
        $this->validate($request, [
            'ids' => 'required|array'
        ]);


        /** @var User $user */
        $user = auth()->user();

        /** @var Conversation $conversation */
        $conversation = $user->conversations()
            ->publicId($conversationId)
            ->firstOrFail();

        $messagesIds = collect($request->input('ids'))
            ->unique()
            ->transform(function($id) {
                return Message::decodePublicId($id);
            });

        $messages = $conversation->messages()
            ->whereIn('id', $messagesIds)
            ->get();

        $messages->each(function(Message $message) use ($user) {
            if(! isset($message->statuses['read'])) {
                $message->statuses['read'] = [];
            }

            if(! in_array($user->public_id, $message->statuses['read'])) {
                $statuses = $message->statuses;
                array_push($statuses['read'], $user->public_id);

                $message->statuses = $statuses;
                $message->save();
            }
        });

        return $this->response([
            'messages' => MessageTransformer::transform($messages)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
