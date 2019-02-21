<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    public function getModuleNameAttribute(){
      $name=explode('_',$this->name);
      return ucwords($name[0]);
    }
    public function getPermissionNameAttribute(){
      $name=explode('_',$this->name);
      return ucwords($name[1]);
    }

    protected $appends = ['module_name','permission_name'];
}
?>
