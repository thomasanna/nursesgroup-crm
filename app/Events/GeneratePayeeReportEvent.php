<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Booking;

class GeneratePayeeReportEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookings;
    public $ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id)
    {
      $this->ids = $id;
      $this->bookings = Booking::with(['unit','category'])->whereIn('bookingId',$this->ids)->orderBy('date','ASC')->orderBy('shiftId','ASC')->get();
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
