<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffReference extends Model
{
    protected $table = 'staff_references';

    protected $primaryKey = 'staffReferenceId';

    protected $fillable = [
      'staffId',
      'fullName',
      'address',
      'phone',
      'position',
      'email',
      'website',
      'sentDate',
      'modeOfReference',
      'sentBy',
      'status',
      'onStatusChanged',
      'comment',
      'documentFile',
    ];
}
