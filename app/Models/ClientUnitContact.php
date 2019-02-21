<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUnitContact extends Model
{
    protected $table = 'client_unit_contacts';
    protected $primaryKey = 'clientUnitPhoneId';
    protected $fillable = [
      'clientUnitId',
      'fullName',
      'position',
      'phone',
      'email',
      'mobile',
      'status'
    ];

    public function unit(){
      return $this->hasOne(ClientUnit::class,'clientUnitId','clientUnitId');
    }
}
