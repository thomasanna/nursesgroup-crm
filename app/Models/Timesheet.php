<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Log;

class Timesheet extends Model
{
    protected $table = 'timesheets';

    protected $primaryKey = 'timesheetId';

    protected $fillable = [
      'bookingId',
      'number',
      'startTime',
      'endTime',
      'status',
      'timesheetRefId',
      'comments',
      'checkInBy',
      'verifiedBy',
      'breakHours',
      'staffHours',
      'smsAcceptedStatus',
      'smsRejectedStatus',
      'unitHours'
    ];

    public function booking(){
      return $this->hasOne(Booking::class,'bookingId','bookingId');
    }

    public function checkin(){
      return $this->hasOne(Admin::class,'adminId','checkInBy')->withDefault([
        'name' =>''
      ]);
    }

    public function verify(){
      return $this->hasOne(Admin::class,'adminId','verifiedBy')->withDefault([
        'name' =>''
      ]);
    }
}
