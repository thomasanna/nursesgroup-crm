<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSms extends Model
{
    protected $table = 'booking_sms';

    protected $primaryKey = 'bookingSmsId';

    protected $fillable = [
      'bookingId',
      'smsType',
      'staffId',
      'sentTime'
    ];
}
