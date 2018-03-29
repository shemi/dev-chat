<?php

namespace App;

use App\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

/**
 * App\Conversation
 *
 * @property int $id
 * @property string|null $name
 * @property bool $is_group
 * @property int $last_message_at
 * @property string|null $encryption_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConversationEvent[] $events
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Message[] $messages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereEncryptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereIsGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereLastMessageAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $last_message
 * @property int|null $last_message_id
 * @property string|null $deleted_at
 * @property-read mixed $public_id
 * @property-read \App\Message|null $lastMessage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation publicId($publicId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Conversation whereLastMessageId($value)
 */
class Conversation extends Model implements HasMediaConversions
{
    use HasMediaTrait, HasPublicId, SoftDeletes;

    protected $fillable = [
        'name',
        'is_group',
        'last_message_at',
        'encryption_key'
    ];

    protected $dates = [
        'last_message_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'is_group' => 'boolean'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_conversation')
            ->withPivot('is_owner', 'color');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function events()
    {
        return $this->hasMany(ConversationEvent::class);
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id', 'id');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 150, 150);
    }
}
