<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $primaryKey = 'adminLogId';

    protected $table = 'admin_logs';

    protected $fillable = [
      'adminId',
      'content',
      'author',
      'type'
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','author');
    }
}
?>