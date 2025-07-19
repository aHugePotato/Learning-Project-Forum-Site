<?php

namespace app\common\model;

use think\Model;
use traits\Model\SoftDelete;

class Post extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    public function user()
    {
        return $this->belongsTo("User");
    }
}
