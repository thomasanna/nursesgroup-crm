<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLicencePermit extends Model
{
    protected $table = 'driver_licence_permits';

    protected $primaryKey = 'driverLicencePermitId';

    protected $fillable = [
      'driverId',
      'licenceId',
      'permit'
    ];
}
