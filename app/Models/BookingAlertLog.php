<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAlertLog extends Model
{

    protected $table = 'booking_alert_logs';
    
    protected $primaryKey = 'bookingAlertLogId';
    
    protected $fillable = [
      'bookingId',
      'staffId',
      'message'
    ];

}
