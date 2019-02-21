<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxWeek extends Model
{
    protected $table = 'tax_weeks';

    protected $primaryKey = 'taxWeekId';

    public function year(){
      return $this->hasOne(TaxYear::class,'taxYearId','taxYearId');
    }
}
