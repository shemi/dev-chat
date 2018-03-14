<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ConversationEvent
 *
 * @property int $id
 * @property int $conversation_id
 * @property string $message
 * @property int $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversationEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConversationEvent extends Model
{
    //
}
