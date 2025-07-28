<?php

namespace app\management\controller;


class Users extends BaseController
{
    public function index()
    {
        if (!session("aid"))
            $this->error("请先登入。","/management/admin_auth/login");
        if(empty(check_permission(session("aid"),"view_users")))
            $this->error("权限不足。");

        global $ainfo;
        $this->assign("ainfo", $ainfo);

        return view();
    }
}
