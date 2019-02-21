<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimesheetGenerate extends Mailable
{
    use Queueable, SerializesModels;

    public $bookId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.timesheetGenerate');
    }
}
