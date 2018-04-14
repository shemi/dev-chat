<?php

namespace App;

use App\Traits\HasPublicId;
use App\Transformers\MessageTransformer;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property int $id
 * @property string|null $body
 * @property int $user_id
 * @property int $conversation_id
 * @property int $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Conversation $conversation
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUserId($value)
 * @mixin \Eloquent
 * @property-read mixed $public_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message publicId($publicId)
 * @property array $statuses
 * @property-read bool $is_new
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereStatuses($value)
 */
class Message extends Model
{
    use HasPublicId;

    const TYPE_TEXT = 1;
    const TYPE_IMAGE = 2;
    const TYPE_VIDEO = 3;
    const TYPE_DOCUMENT = 4;

    protected $fillable = [
        'body',
        'user_id',
        'statuses',
        'conversation_id',
        'type'
    ];

    protected $casts = [
        'statuses' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function transform()
    {
        return MessageTransformer::transform($this);
    }

    public function setBodyAttribute($value)
    {
        $emojiRegex = '/<img.*?title=["|\'](.*?)["|\'].*?>/m';
        $spanRegex = '/(<span.*rangySelectionBoundary.*<\/span>)/m';
        $value = trim(preg_replace($emojiRegex, ':$1:', $value));
        $value = preg_replace($spanRegex, '', $value);

        $this->attributes['body'] = trim(strip_tags($value));
    }

    /**
     * @return bool
     */
    public function getIsNewAttribute()
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        if(! $currentUser) {
            return true;
        }

        if($currentUser->id === $this->user_id) {
            return false;
        }

        return ! in_array($currentUser->public_id, $this->statuses['read']);
    }

    /**
     * @return bool
     */
    public function getIsReadAttribute()
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        if(! $currentUser) {
            return false;
        }

        if($currentUser->id === $this->user_id) {
            return true;
        }

        return ! in_array($currentUser->public_id, $this->statuses['read']);
    }

}
