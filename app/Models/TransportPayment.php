<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportPayment extends Model
{
    protected $primaryKey = 'tripPaymentRecordId';

    protected $table = 'transport_recorded_payments';

    protected $fillable = [
      'completedTransId',
      'paymentDate',
      'bankId',
      'transactionNumber',
      'handledBy',
    ];
}
