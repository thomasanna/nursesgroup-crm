<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAvailability extends Model
{
    protected $table = 'staff_avaiablity';

    protected $primaryKey = 'avaiabilityId';

    protected $fillable = [
                            'staffId',
                            'date',
                        ];

    public function staff(){
        return $this->belongsTo(Staff::class,'staffId','staffId')->withDefault([
            'name' =>''
        ]);
    }

}
