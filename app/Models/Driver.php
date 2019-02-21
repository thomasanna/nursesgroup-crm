<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $primaryKey = 'driverId';

    protected $fillable = [
      'forname',
      'surname',
      'email','mobile','branchId',
      'dateOfBirth','joinedDate','gender',
      'whatsappNumber','lanLineNumber','address',
      'pincode','latestTaxBand','niNumber',
      'photo','niDocumentFile','zoneId',
      'paymentMode','selfPaymentCompanyName','selfPaymentCompanyNumber',
      'selfPaymentCompanyRegAddress','nokFullName','nokRelationship',
      'nokMobile','nokEmail','nokAddress',
      'nokPostCode','nokPhone','ratePerMile',
      'extraStaffRate','bankSortCodeA','bankSortCodeB',
      'bankSortCodeC','bankAccountNumber','status'
    ];

    public function branch(){
      return $this->hasOne(Branch::class,'branchId','branchId')->withDefault([
        'name' =>''
      ]);
    }

    public function vehicle(){
      return $this->hasMany(Vehicle::class,'driverId','driverId');
    }

}
