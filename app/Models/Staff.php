<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Staff extends Model
{
    use SoftDeletes,Notifiable;
    protected $table = 'staffs';

    protected $primaryKey = 'staffId';

    protected $fillable = [
      'title',
      'forname',
      'surname',
      'categoryId',
      'username',
      'password',
      'dateOfBirth',
      'email',
      'mobile',
      'whatsappNumber',
      'lanLineNumber',
      'gender',
      'address',
      'photo',
      'performancePoint',
      'isPermenent',
      'pincode',
      'longitude',
      'latitude',
      'joinedDate',
      'modeOfTransport',
      'pickupLocation',
      'branchId',
      'zoneId',
      'bandId',
      'quickNotes',
      'personalProgress',
      'referenceProgress',
      'rtwProgress',
      'dbsProgress',
      'trainingProgress',
      'nokFullName',
      'nokRelationship',
      'nokAddress',
      'nokPostCode',
      'nokPhone',
      'nokMobile',
      'nokEmail',
      'nmcPinNumber',
      'nmcPinExpiryDate',
      'nmcPinReValidationDate',
      'bankSortCodeA',
      'bankSortCodeB',
      'bankSortCodeC',
      'bankAccountNumber',
      'niNumber',
      'niDocumentFile',
      'latestTaxBand',
      'paymentMode',
      'selfPaymentCompanyName',
      'selfPaymentCompanyNumber',
      'selfPaymentCompanyRegAddress',
      'selfPaymentCompanyLandLine',
      'selfPaymentCompanyAltPhone',
      'selfPaymentCompanyBusAddress',
      'selfPaymentCompanyType',
      'selfPaymentCompanyMobile',
      'selfPaymentCompanyEmail',
      'selfPaymentCompanyPersonInCharge',
      'selfPaymentCompanyNumberOfBranches',
      'selfPaymentCompanyFax',
      'payRateWeekday',
      'payRateWeekNight',
      'payRateWeekendDay',
      'payRateWeekendNight',
      'payRateSpecialBhday',
      'payRateSpecialBhnight',
      'payRateBhday',
      'payRateBhnight',
      'status'
    ];

    public function category(){
      return $this->hasOne(StaffCategory::class,'categoryId','categoryId');
    }

    public function training(){
      return $this->hasMany(StaffTraining::class,'staffId','staffId');
    }

    public function band(){
      return $this->hasOne(StaffBand::class,'bandId','bandId');
    }

    public function branch(){
      return $this->hasOne(Branch::class,'branchId','branchId')->withDefault([
        'name' =>''
      ]);
    }
    public function zone(){
      return $this->hasOne(BranchZone::class,'id','zoneId')->withDefault([
        'name' =>''
      ]);
    }

    public function bookingalerts(){
      return $this->hasMany(BookingAlertLog::class,'staffId','staffId');
    }

    public function alertstatus(){
      return $this->hasOne(BookingAlertLog::class,'staffId','staffId');
    }

    public function log(){
      return $this->hasOne(StaffLog::class,'staffId','staffId')->withDefault([
        'priority' =>0
      ]);
    }

    // public function getHistoricalRateAttribute(){
    //   $payments = Payment::whereHas('booking.staff', function ($query) {
    //                 $query->where('staffId',$this->staffId);
    //               })
    //               ->where('status',2)->take(4)->get();
    //               $sum = $payments->sum('hourlyRate')+$payments->sum('ta')+$payments->sum('extraTa');
    //   if($payments->count() > 0){
    //     return number_format($sum/$payments->count(),2);
    //   }else{
    //     return number_format(0,2);
    //   }
    // }

    // protected $appends = ['historical_rate'];

}
