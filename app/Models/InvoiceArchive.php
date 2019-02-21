<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceArchive extends Model
{
    protected $table = 'invoice_archives';

    protected $primaryKey = 'archiveId';

    protected $fillable = [
      'invoiceId',
      'invoiceStatus',
      'invoiceNumber',
      'invoiceDate',
      'paymentDate',
      'bankId',
      'transactionNumber',
      'handledBy',
      'recordPaymentTime',
      'isPaymentRecorded',
      'isEmailSent',
    ];

    // public function payment(){
    //   return $this->hasOne(Payment::class,'paymentId','paymentId');
    // }

    public function invoice(){
      return $this->hasOne(Invoice::class,'invoiceId','invoiceId');
    }
    public function handled(){
      return $this->hasOne(Admin::class,'adminId','handledBy');
    }
}
