<?php

namespace app\common\model;

use think\Model;
use traits\Model\SoftDelete;

class Post extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    public function scopeInfo($query)
    {
        $query->alias("a")
            ->join("tp_user b", "a.user_id = b.id")
            ->join("tp_post c", "a.post_id = c.id", "left")
            ->join("tp_user d", "c.user_id = d.id", "left")
            ->field("a.id, a.text, a.create_time, a.update_time, a.delete_time, a.media, a.user_id, a.post_id,
                 b.name as user_name")
            ->field("FROM_UNIXTIME(c.update_time) as reply_to_update_time, c.user_id as reply_to_user_id, 
            d.name as reply_to_user_name")
        ;
    }

    public function user()
    {
        return $this->belongsTo("User");
    }

    public function post()
    {
        return $this->belongsTo("Post");
    }
}
