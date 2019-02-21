<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUnitLog extends Model
{
    protected $primaryKey = 'clientUnitLogId';

    protected $table = 'client_unit_logs';

    protected $fillable = [
      'clientUnitId',
      'content',
      'author',
      'type'
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','author');
    }
}
?>