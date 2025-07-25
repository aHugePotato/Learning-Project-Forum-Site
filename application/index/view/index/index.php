<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="/static/style.css">
    <link rel="stylesheet" href="/static/filepond-master/dist/filepond.css">
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

        #postVideoSec {
            padding: 1em;
            text-align: center;

            & video {
                width: 60%;
                border-radius: 4px;
            }
        }

        #postEdit {
            margin: auto;
            width: 650px;
            margin-bottom: 1em;

            #bottomSec-fileSelSec {
                padding-top: 0.5em;
                padding-bottom: 0.5em;
            }

            #bottomSec-submit {
                padding-top: 0.5em;
                padding-bottom: 0.5em;
                text-align: right;
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

    <script src="/static/filepond-master/dist/filepond.js" referrerpolicy="origin"></script>
    <script src="/static/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        const inputElement = document.querySelector('.filepond');
        const pond = FilePond.create(inputElement);

        tinymce.init({
            selector: '.tinyMCE',
            license_key: 'gpl',
            relative_urls: false,
            language_url: "/static/tinymce/js/tinymce/langs/zh_CN.js",
            language: 'zh_CN',
            plugins: 'image',
            images_upload_url: "/ajax_img_upload",
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
                <a href="/userauth/logout"> 登出</a>
            <?php } else { ?>
                <a href="/userauth/login">登入</a>
                <span>/</span>
                <a href="/userauth/signup">注册</a>
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
                            <div><?php echo $post["user"]["name"]; ?></div>
                            <div id="postTime"><?php echo $post["update_time"]; ?></div>
                        </div>
                        <div id="postOpSec">
                            <?php if (isset($uinfo) && $post["user_id"] == $uinfo["id"]) { ?>
                                <a href="/delete/?id=<?php echo $post["id"] ?>">删除</a>
                                <a href="/?id=<?php echo $post["id"] ?>&op=edit#bottomSec">编辑</a>
                            <?php } ?>
                        </div>
                    </div>
                    <p id="postTextSec">
                        <?php echo $post["text"]; ?>
                    </p>
                    <?php if ($post["media"]) { ?>
                        <div id="postVideoSec">
                            <video controls src="<?php echo $post['media']; ?>"></video>
                        </div>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <?php echo $posts->render(); ?>
    </div>
    <?php if (isset($uinfo)) { ?>
        <div id="bottomSec">
            <form action="" method="post" enctype="multipart/form-data" id="postEdit">
                <textarea name="newPost" id="" class="tinyMCE"><?php if (isset($editContent)) echo $editContent; ?></textarea>
                <input type="hidden" name="__token__" value="{$Request.token}">
                <div id="bottomSec-fileSelSec">
                    <label for="bottomSec-fileSelBut">上传视频:</label><br>
                    <input name="video" type="file" accept=".mp4,.mov,.avi,.mwv,.mkv,.mpg,.mpeg" id="bottomSec-fileSelBut">
                </div>
                <div id="bottomSec-submit">
                    <button type="submit">发送</button>
                </div>
            </form>
        </div>
    <?php } ?>
</body>

</html>