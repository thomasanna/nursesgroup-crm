<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantPreferredUnit extends Model
{
    protected $table = 'applicants_preferred_units';

    protected $primaryKey = 'applicantPreferredUnitId';

    protected $fillable = [
      'applicantId',
      'unitId',
      'status'
    ];
}
