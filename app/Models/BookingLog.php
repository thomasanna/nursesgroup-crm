<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;
class BookingLog extends Model
{
  // use Cachable;
    protected $table = 'booking_logs';

    protected $primaryKey = 'bookingLogId';

    protected $fillable = [
      'bookingId',
      'content',
      'type',
      'author',
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','author');
    }
  
}
