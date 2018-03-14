<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
 */
class Conversation extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $fillable = [
        'name',
        'is_group',
        'last_message_at',
        'encryption_key'
    ];

    protected $casts = [
        'is_group' => 'boolean',
        'last_message_at' => 'timestamp'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_conversation');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function events()
    {
        return $this->hasMany(ConversationEvent::class);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 150, 150);
    }
}