<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPriorityStaff extends Model
{

    protected $table = 'booking_priority_staffs';
    
    protected $primaryKey = 'bookingPriorityStaffId';
    
    protected $fillable = [
      'staffId',
      'date'
    ];

}
