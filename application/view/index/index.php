<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style.css">
    <title>Posts</title>
    <style>
        #mainSec {
            width: 650px;
            margin: auto;
            margin-top: 50px;
            margin-bottom: 60px;
            overflow: auto;
        }

        #mainList {
            list-style-type: none;
            margin: 0px;
            padding: 0px;
        }

        #mainList li {
            min-height: 100px;
            border: 1px solid rgb(230, 230, 230);
            border-bottom: none;
            width: 100%;
            padding: 1em;
        }

        #mainList li:last-child {
            border-bottom: 1px solid rgb(230, 230, 230) !important;
        }

        #postEdit {
            margin: auto;
            width: 650px;

            button[type="submit"] {
                display: block;
                margin-top: 1em;
                margin-bottom: 1em;
                float: right;
            }
        }

        #postUpSec {
            display: flex;
            justify-content: space-between;
        }

        #postUpLeftSec {
            display: flex;
            gap: 10px;
        }

        #postOpSec {
            display: flex;

            a {
                margin-left: 10px;
            }
        }

        #postTextSec {
            font-size: 1.2em;
            word-break: break-all;
        }

        #postTime {
            color: grey;
        }
    </style>
    <script src="/static/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.tinyMCE',
            license_key: 'gpl',
            language_url: "/static/tinymce/js/tinymce/langs/zh_CN.js",
            language:'zh_CN',
            plugins: 'image',
            images_upload_url:"/index/ajax_img_upload",
            menubar: 'file edit view insert format',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
            promotion: false,
        });
    </script>
</head>

<body>
    <div id="nav">
        <h1 id="title">论坛</h1>
        <div id="uSec">
            <?php if (isset($uinfo)) { ?>
                <div><?php echo $uinfo["name"]; ?></div>
                <a href="/user/logout"> 登出</a>
            <?php } else { ?>
                <a href="/user/login">登入</a>
                <span>/</span>
                <a href="/user/signup">注册</a>
            <?php } ?>
        </div>
    </div>
    <div id="mainSec">
        <ul id="mainList">
            <?php
            foreach ($posts as $post) { ?>
                <li>
                    <div id="postUpSec">
                        <div id="postUpLeftSec">
                            <div><?php echo $post["users"]["name"]; ?></div>
                            <div id="postTime"><?php echo $post["update_time"]; ?></div>
                        </div>
                        <div id="postOpSec">
                            <?php if (isset($uinfo) && $post["user_id"] == $uinfo["id"]) { ?>
                                <a href="/index/delete/id/<?php echo $post["id"] ?>">删除</a>
                                <a href="/index?id=<?php echo $post["id"] ?>&op=edit">编辑</a>
                            <?php } ?>
                        </div>
                    </div>
                    <p id="postTextSec">
                        <?php echo $post["text"]; ?>
                    </p>
                </li>
            <?php } ?>
        </ul>
        <?php echo $posts->render(); ?>
    </div>
    <?php if (isset($uinfo)) { ?>
        <div id="bottomSec">
            <form action="" method="post" id="postEdit">
                <textarea name="newPost" id="" class="tinyMCE"><?php if (isset($editContent)) echo $editContent; ?></textarea>
                <input type="hidden" name="__token__" value="{$Request.token}">
                <button type="submit">发送</button>
            </form>
        </div>
    <?php } ?>
</body>

</html>