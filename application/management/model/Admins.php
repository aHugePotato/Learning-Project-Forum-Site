<?php

namespace app\management\model;

use think\model;

class Admins extends Model
{
    protected $autoWriteTimestamp = false;

    public function roles()
    {
        return $this->belongsToMany("Roles","app\management\model\Admin_role");
    }
}
