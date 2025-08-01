<?php

namespace app\management\controller;

use app\common\controller\BaseUploadHandler;

/**
 * class UploadHandler
 * 处理大文件上传
 */
class UploadHandler extends BaseUploadHandler
{
    protected $fileExtensions = "xlsx,xls";

    protected function auth()
    {
        return check_permission(session("aid"),"import_posts");
    }
}
