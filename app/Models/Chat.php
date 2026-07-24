<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'uuid', 'project_id', 'last_message_at', 'last_read_at'];

    protected $casts = [
        'user_id' => 'integer',
        'project_id' => 'integer',
        'last_message_at' => 'datetime',
        'last_read_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chat) {
            if (empty($chat->uuid)) {
                $chat->uuid = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        return $this->messages()
            ->where('status', '!=', 'read')
            ->count();
    }

    /**
     * Get last unread message
     */
    public function lastUnreadMessage()
    {
        return $this->messages()
            ->where('status', '!=', 'read')
            ->latest()
            ->first();
    }

    /**
     * Mark all messages as read
     */
    public function markAllAsRead()
    {
        $this->messages()
            ->where('status', '!=', 'read')
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        $this->update(['last_read_at' => now()]);
    }
}
