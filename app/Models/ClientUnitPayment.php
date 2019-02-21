<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUnitPayment extends Model
{
    protected $table = 'client_unit_payments';
    protected $primaryKey = 'clientUnitPaymentId';
    protected $fillable = [
      'clientUnitId',
      'staffCategoryId',
      'rateType',
      'dayMonday',
      'nightMonday',
      'dayTuesday',
      'nightTuesday',
      'dayWednesday',
      'nightWednesday',
      'dayThursday',
      'nightThursday',
      'dayFriday',
      'nightFriday',
      'daySaturday',
      'nightSaturday',
      'daySunday',
      'nightSunday',
      'bhDay',
      'bhNight',
      'splBhDay',
      'splBhNight',
      'taPerMile',
      'taNoOfMiles',
      'status'
    ];

    public function unit(){
      return $this->hasOne(ClientUnit::class,'clientUnitId','clientUnitId');
    }
    public function category(){
      return $this->hasOne(StaffCategory::class,'categoryId','staffCategoryId');
    }
}
