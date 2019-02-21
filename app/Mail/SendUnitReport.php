<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUnitReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $bookings;
     public $contact;
    public function __construct($bookings,$contact)
    {
        $this->bookings = $bookings;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('email.unitReport')
      ->subject('Unit Report - '.$this->contact->unit->name)
      ->with(['bookings' => $this->bookings,'contact'=>$this->contact]);
    }
}
