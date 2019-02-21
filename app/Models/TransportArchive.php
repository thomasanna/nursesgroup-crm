<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportArchive extends Model
{
    protected $table = 'transport_archives';

    protected $primaryKey = 'archiveId';

    protected $fillable = [
      'tripId',
    ];

    public function trip(){
      return $this->hasOne(Transportation::class,'tripId','tripId');
    }
}
