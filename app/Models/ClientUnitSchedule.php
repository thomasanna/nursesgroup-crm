<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class ClientUnitSchedule extends Model
{
  // use Cachable;
  protected $table = 'client_unit_schedules';
  protected $primaryKey = 'clientUnitScheduleId';
  protected $fillable = [
    'clientUnitId',
    'staffCategoryId',
    'shiftId',
    'startTime',
    'endTime',
    'unPaidBreak',
    'paidBreak',
    'totalHoursUnit',
    'totalHoursStaff',
    'status'
  ];

  public function unit(){
    return $this->hasOne(ClientUnit::class,'clientUnitId','clientUnitId');
  }
  public function category(){
    return $this->hasOne(StaffCategory::class,'categoryId','staffCategoryId');
  }
}
