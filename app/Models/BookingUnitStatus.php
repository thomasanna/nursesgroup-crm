<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingUnitStatus extends Model
{
    protected $table = 'booking_unit_status';

    protected $primaryKey = 'bookingUnitStatusId';

    protected $fillable = [
      'bookingId',
      'confirmedBy',
      'confirmedAt',
      'temporaryBy',
      'temporaryAt',
      'unableToCoverBy',
      'unableToCoverAt',
      'cancelledBy',
      'cancelledAt',
      'bookingErrorBy',
      'bookingErrorAt',
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','unableToCoverBy')->withDefault([
        'name' =>''
      ]);
    }
}
