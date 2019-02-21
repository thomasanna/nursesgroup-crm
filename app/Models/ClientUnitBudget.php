<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUnitBudget extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'clientUnitBudgetId';

    protected $table = 'client_unit_budget';

    protected $fillable = [
      'clientUnitId',
      'year',
      'month',
      'budget'
    ];
}
