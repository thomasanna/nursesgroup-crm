<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    protected $table = 'transportations';

    protected $primaryKey = 'tripId';

    protected $fillable = [
        "bookingId",
        "direction",
        "clubId",
        "driverId",
        "staffId",
        "unitId",
        "date",
        "pickupTime",
        "aggreedAmount",
        "paymentDate",
        "bankId",
        "transactionNumber",
        "handledBy",
        "recordPaymentTime",
        "status",
        'order',
        'taxYear',
        'payeeWeek'
    ];

    public function booking(){
      return $this->belongsTo(Booking::class,'bookingId','bookingId');
    }

    public function driver(){
      return $this->belongsTo(Driver::class,'driverId','driverId');
    }

    public function unit(){
      return $this->hasOne(ClientUnit::class,'clientUnitId','unitId');
    }

    public function staff(){
      return $this->belongsTo(Staff::class,'staffId','staffId')->withDefault([
        'name' =>'',
        'modeOfTransport' =>''
      ]);
    }

}
