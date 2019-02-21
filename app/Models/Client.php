<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'clientId';

    protected $fillable = [
      'name',
      'phone',
      'landlineNumber',
      'altPhoneNumber',
      'mobileNumber',
      'fax',
      'email',
      'personInCharge',
      'registeredAddress',
      'businessAddress',
      'companyNumber',
      'typeOfCompany',
      'numberOfBranches',
      'status'
    ];
}
