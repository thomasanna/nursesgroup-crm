<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Booking extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'bookingId';

    protected $fillable = [
      'categoryId',
      'date',
      'time',
      'handledBy',
      'unitId',
      'unitStatus',
      'type',
      'modeOfRequest',
      'requestedBy',
      'requestedTime',
      'requestedDate',
      'shiftId',
      'noOfShifts',
      'homeStatus',
      'staffId',
      'staffStatus',
      'confirmedBy',
      'confirmedAt',
      'aggreedHrRate',
      'importantNotes',
      'newSmsStatus',
      'finalConfirmationSms',
      'modeOfCancelRequest',
      'cancelDate',
      'cancelRequestedBy',
      'cancelExplainedReason',
      'cancelInformUnitTo',
      'canceledOrUTCreason',
      'confirmSmsStatus',
      'tvStatus',
      'paymentStatus',
      'invoiceStatus',
      'status',
      'distanceToWorkplace',
      'staffAllocateStatus',
      'modeOfTransport',
      'outBoundDriver',
      'outBoundDriverType',
      'outBoundDriverId',
      'outBoundClubId',
      'inBoundDriver',
      'inBoundDriverType',
      'inBoundDriverId',
      'bonus',
      'finalConfirm',
      'transportAllowence',
      'outBoundClubId',
      'inBoundClubId',
    ];

    public function shift(){
      return $this->hasOne(Shift::class,'shiftId','shiftId')->withDefault([
        'name' =>''
      ])->orderBy('name','ASC');
    }
    public function unit(){
      return $this->hasOne(ClientUnit::class,'clientUnitId','unitId')->withDefault([
        'name' =>''
      ]);
    }
    public function category(){
      return $this->hasOne(StaffCategory::class,'categoryId','categoryId')->withDefault([
        'name' =>''
      ]);
    }
    public function staff(){
      return $this->hasOne(Staff::class,'staffId','staffId')->withDefault([
        'name' =>'',
        'forname' =>'',
        'modeOfTransport' =>''
      ]);
    }
    public function transportation(){
      return $this->hasMany(Transportation::class,'bookingId','bookingId');
    }

    public function confirmedby(){
      return $this->hasOne(Admin::class,'adminId','confirmedBy');
    }

    public function canceled(){
      return $this->hasOne(Admin::class,'adminId','cancelAuthorizedBy')->withDefault([
        'name' =>''
      ]);
    }

    public function unabletocover(){
      return $this->hasOne(BookingUnitStatus::class,'bookingId','bookingId')->withDefault([
        'name' =>''
      ]);
    }

    public function unitcontacts(){
      return $this->hasMany(ClientUnitContact::class,'clientUnitId','unitId');
    }

    public function unitinformlog(){
      return $this->hasOne(ClientUnitInformLog::class,'bookingId','bookingId');
    }

    public function scopeUnit($query,$unitId){
      return $query->where('unitId',$unitId)->with(['staff','category','shift']);
    }

}
