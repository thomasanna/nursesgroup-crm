<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'branchId';

    protected $fillable = [
      'name',
      'alias',
      'address',
      'phone',
      'mobile',
      'email',
      'mail_sender',
      'type',
      'personInCharge',
      'status'
    ];
}
