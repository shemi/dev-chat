<?php

namespace App\Http\Controllers;

use App\Conversation;
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

        /** @var Conversation $conversation */
        $conversation = Conversation::findByPublicId($conversationId);
        $user = auth()->user();

        if(! $conversation) {
            return $this->responseNotFound();
        }

        $message = new Message();
        $message->body = $request->input('body');
        $message->user_id = $user->id;
        $message->type = (int) $request->input('type');
        $message->statuses = [
            'read' => [$user->public_id],
            'sent' => [$user->public_id]
        ];
        $message = $conversation->messages()->save($message);

        $conversation->last_message_id = $message->id;
        $conversation->last_message_at = $message->created_at;
        $conversation->save();

        return $this->response(MessageTransformer::transform($message));
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
