<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Controllers\Payments\Payee\PayeeWeek;

class SendPayeeRemittenceAdvice extends Mailable
{
    use Queueable;

    public $payments;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payments)
    {
        $this->payments = PayeeWeek::processPaymentArray($payments);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Remittence Advice - ';
        $subject .= $this->payments[0]->booking->staff->forname." ".$this->payments[0]->booking->staff->surname." | ";
        $subject .= "Week - ".$this->payments[0]->paymentWeek;
        return $this->subject($subject)->view('email.ra_email');
    }
}
