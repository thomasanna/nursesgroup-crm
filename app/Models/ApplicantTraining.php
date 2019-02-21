<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTraining extends Model
{
    protected $table = 'applicant_trainings';

    protected $primaryKey = 'applicantTrainingId';

    protected $fillable = [
      'courseId',
      'applicantId',
      'provider',
      'issueDate',
      'expiryDate',
      'status',
      'comment',
      'documentFile',
    ];

    public function course(){
      return $this->hasOne(TrainingCourse::class,'trainingCourseId','courseId')->withDefault([
        'courseName' =>''
      ]);
    }
}
