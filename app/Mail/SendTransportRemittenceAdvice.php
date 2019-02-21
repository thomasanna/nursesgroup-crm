<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;

class SendTransportRemittenceAdvice extends Mailable
{
    use Queueable, SerializesModels;

    public $compTrips;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trips)
    {
        $this->compTrips = $trips;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Transport Remittence Advice')->view('email.transport.remittanceAdvice');
    }
}
