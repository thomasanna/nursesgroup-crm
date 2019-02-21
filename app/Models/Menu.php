<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{

    protected $primaryKey = 'id';

    protected $table = 'menus';

    protected $fillable = [
          'id',
          'name',
          'description',
          'read',
          'edit',
          'write',
          'delete',
          'viewall',
          'manageall',
    ];

    
}
?>