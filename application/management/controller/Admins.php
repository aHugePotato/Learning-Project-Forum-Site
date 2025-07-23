<?php

namespace app\management\controller;


class Admins extends BaseController
{
    public function index()
    {
        if (!session("aid"))
            $this->error("请先登入。","/management/adminauth/login");
        if(empty(check_permission(session("aid"),"view_admins")))
            $this->error("权限不足。");

        global $ainfo;
        $this->assign("ainfo", $ainfo);

        return view();
    }
}
