<?php

namespace app\management\model;

use think\Model;

class Admin extends Model
{
    protected $autoWriteTimestamp = false;

    public function role()
    {
        return $this->belongsToMany("Role");
    }
}
