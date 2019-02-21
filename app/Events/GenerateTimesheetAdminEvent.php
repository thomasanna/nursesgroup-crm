<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GenerateTimesheetAdminEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookings;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->bookings = Booking::where('unitStatus',4)
              ->where('staffStatus',3)
              ->whereNotNull('staffId')
              ->where('date','<',date('Y-m-d'))->get();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
