<?php

namespace app\management\model;

use think\Model;

class Role extends Model
{
    protected $autoWriteTimestamp = false;

    public function permission()
    {
        return $this->belongsToMany("permission");
    }
}
