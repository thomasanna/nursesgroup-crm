<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SendUnitInvoice extends Mailable
{
    use Queueable;

    public $invoices;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdfName = $this->invoices[0]->booking->unit->name."_"."Year_".$this->invoices[0]->weekYear."_Month_".$this->invoices[0]->monthNumbr.".pdf" ;

        if($this->invoices[0]->invceFrqncy==1)
        return $this->subject('Unit Invoice')->view('invoices.weekly.pdf')->attach(storage_path()."/app/public/invoices/".$pdfName);
        else
        return $this->subject('Unit Invoice')->view('invoices.monthly.pdf')->attach(storage_path()."/app/public/invoices/".$pdfName);
    }
}
