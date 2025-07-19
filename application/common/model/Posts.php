<?php

namespace app\common\model;

use think\Model;
use traits\Model\SoftDelete;

class Posts extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    public function users()
    {
        return $this->belongsTo("Users", "user_id");
    }
}
