<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentArchive extends Model
{
    protected $table = 'payment_archives';

    protected $primaryKey = 'archiveId';

    protected $fillable = [
      'paymentId',
      'raStatus',
      'raNumber',
      'raDate',
      'paymentDate',
      'bankId',
      'transactionNumber',
      'handledBy',
      'recordPaymentTime',
      'isPaymentRecorded',
      'isEmailSent',
    ];

    public function payment(){
      return $this->hasOne(Payment::class,'paymentId','paymentId');
    }
    public function handled(){
      return $this->hasOne(Admin::class,'adminId','handledBy');
    }
}
