<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLicence extends Model
{
    protected $table = 'driver_licences';

    protected $primaryKey = 'driverLicenceId';

    protected $fillable = [
      'driverId',
      'number',
      'dateOfIssue',
      'dateOfExpiry',
      'validFrom',
      'validTo',
      'issuedBy',
      'softCopy',
    ];

    public function driver(){
      return $this->hasOne(Driver::class,'driverId','driverId');
    }
}
