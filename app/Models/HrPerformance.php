<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrPerformance extends Model
{
    protected $table = 'hr_performance';

    protected $primaryKey = 'performanceId';

    protected $fillable = [
      'hrId',
      'action',
      'applicantId',
      'staffId',
      'actionDate'
    ];
}
