<?php

namespace app\management\behavior;

use app\management\model\Admin;

/**
 * class CheckLogin
 * 检查登入状态并提供用户信息
 * 
 */
class CheckAuth
{
    public function run($params)
    {
        global $ainfo;
        if (!session("loginTime"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (session("aid") && (!($ainfo = Admin::get(["id" => session("aid")])) || $ainfo["disabled"]))
            session(null);
    }
}
