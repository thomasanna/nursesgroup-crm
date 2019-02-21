<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes,Notifiable;
    protected $table = 'applicants';

    protected $primaryKey = 'applicantId';

    protected $fillable = [
      'title',
      'forname',
      'surname',
      'categoryId',
      'dateOfBirth',
      'email',
      'mobile',
      'whatsappNumber',
      'lanLineNumber',
      'gender',
      'photo',
      'address',
      'pincode',
      'joinedDate',
      'modeOfTransport',
      'pickupLocation',
      'branchId',
      'zoneId',
      'bandId',
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
    public function band(){
      return $this->hasOne(StaffBand::class,'bandId','bandId');
    }
    public function branch(){
      return $this->hasOne(Branch::class,'branchId','branchId')->withDefault([
        'name' =>''
      ]);
    }

}
