<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id', 'role', 'content', 'status', 'delivered_at', 'read_at'];

    protected $casts = [
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        return $this;
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
        return $this;
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Check if message is delivered
     */
    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'read']);
    }
}
