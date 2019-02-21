<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxYear extends Model
{
    protected $table = 'tax_years';
       protected $fillable = [
        'taxYearFrom', 'taxYearTo'
    ];

    protected $primaryKey = 'taxYearId';
}
