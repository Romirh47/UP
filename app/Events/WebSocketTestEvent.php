<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebSocketTestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $creator; // Tambahkan properti untuk creator

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $creator)
    {
        $this->message = $message;
        $this->creator = $creator; // Simpan informasi creator
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|Array
     */
    public function broadcastOn()
    {
        return new Channel('test-channel'); // Ganti dengan nama channel yang kamu inginkan
    }
}
