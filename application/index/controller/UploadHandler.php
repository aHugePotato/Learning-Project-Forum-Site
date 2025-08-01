<?php

namespace app\index\controller;

use app\common\controller\BaseUploadHandler;

/**
 * class UploadHandler
 * 处理大文件上传
 */
class UploadHandler extends BaseUploadHandler
{
    protected $fileExtensions = "mp4,mov,avi,mwv,mkv,mpg,mpeg";
    protected $inputName = "video";

    protected function auth()
    {
        return session("uid");
    }
}
