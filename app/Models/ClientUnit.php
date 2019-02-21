<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class ClientUnit extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    // use Cachable;
    protected $primaryKey = 'clientUnitId';

    protected $fillable = [
      'name',
      'alias',
      'type',
      'clientId',
      'branchId',
      'username',
      'password',
      'businessAddress',
      'nameOfManager',
      'fax',
      'website',
      'address',
      'postCode',
      'email',
      'localAuthoritySocialServices',
      'nameOfDeputyManager',
      'nameOfRotaHRAdministrator',
      'residenceCapacity',
      'agencyUsageLevelHCA',
      'agencyUsageLevelRGN',
      'agencyUsageLevelOthers',
      'invoiceFrequency',
      'paymentTermAgreed',
      'latestCQCReport',
      'status'
    ];

    public function client(){
      return $this->hasOne(Client::class,'clientId','clientId');
    }
    public function contact(){
      return $this->hasOne(ClientUnitContact::class,'clientUnitId','clientUnitId')->withDefault([
        'phone' =>''
      ]);
    }
}
