<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUnitLogin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    // use Cachable;
    protected $primaryKey = 'clientUnitLoginId';
    protected $fillable = [
      'clientUnitId',
      'username',
      'password',      
      'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
