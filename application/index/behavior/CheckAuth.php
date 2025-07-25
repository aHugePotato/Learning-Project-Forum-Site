<?php

namespace app\index\behavior;

use app\common\model\User;

/**
 * class CheckLogin
 * 检查登入状态并提供用户信息
 * 
 */
class CheckAuth
{
    public function run($params)
    {
        global $uinfo;
        if (!session("loginTime"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (session("uid") && !$uinfo = User::get(["id" => session("uid")]))
            session(null);
    }
}
