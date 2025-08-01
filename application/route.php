<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::alias(
    [
        'user_auth' => 'index/user_auth',
        'upload_handler' => 'index/upload_handler',
        'test' => 'index/Test'
    ]
);

Route::rule(
    [
        "" => "index/Index/index",
        "delete" => "index/Index/delete",
        "ajax_img_upload" => "index/Index/ajax_img_upload",
        "management^"=>"management/Posts/index"
    ]
);

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
