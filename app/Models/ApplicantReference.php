<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantReference extends Model
{
    protected $table = 'applicant_references';

    protected $primaryKey = 'applicantReferenceId';

    protected $fillable = [
      'applicantId',
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
      'followUpDate',
      'comment',
      'documentFile',
    ];
}
