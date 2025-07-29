<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="/static/style.css">
    <link rel="stylesheet" href="/static/filepond-master/dist/filepond.css">
    <style>
        #title {
            color: black;
        }

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

            & video {
                width: 60%;
                border-radius: 4px;
            }
        }

        #bottomSec {
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

        #bottomSec-replyTo {
            padding-top: 0.5em;
            padding-bottom: 0.5em;
        }

        #postReplyToSec {
            font-size: 0.8em;
            font-style: italic;
            color: #404040;
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

        #postTime {
            color: grey;
        }

        #postTextSec {
            font-size: 1.2em;
            word-break: break-all;
        }
    </style>

    <script src="/static/filepond-master/dist/filepond.js" referrerpolicy="origin"></script>
    <script src="/static/filepond-plugin-file-validate-type-master/dist/filepond-plugin-file-validate-type.js" referrerpolicy="origin"></script>
    <script src="/static/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="module">
        import zh_CN from '/static/filepond-master/locale/zh-cn.js';

        var filepondMock =
            <?php
            if (isset($editVidInfo))
                echo "{source:'" . $editVidInfo["source"] . "',name:'" . $editVidInfo["name"]
                    . "',size:'" . $editVidInfo["size"] . "',type:'" . $editVidInfo["type"] . "'}";
            else echo "null";
            ?>

        function onDOMCLoad() {
            FilePond.setOptions(zh_CN);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            const inputElement = document.querySelector('.filepond')
            const pond = FilePond.create(inputElement, {
                server: "/upload_handler",
                files: filepondMock ? [{
                    // the server file reference
                    source: filepondMock.source,
                    // set type to local to indicate an already uploaded file
                    options: {
                        type: 'local',
                        // mock file information
                        file: {
                            name: filepondMock.name,
                            size: filepondMock.size,
                            type: filepondMock.type,
                        }
                    }
                }] : null
            })

            tinymce.init({
                selector: '.tinyMCE',
                license_key: 'gpl',
                relative_urls: false,
                language_url: "/static/tinymce/js/tinymce/langs/zh_CN.js",
                language: 'zh_CN',
                plugins: 'image',
                images_upload_url: "/ajax_img_upload",
                promotion: false,
            })
        }
        document.addEventListener("DOMContentLoaded", onDOMCLoad)
    </script>
</head>

<body>
    <div id="nav">
        <a href="/">
            <h1 id="title">论坛</h1>
        </a>
        <div id="uSec">
            <?php if (isset($uinfo)) { ?>
                <div><?php echo $uinfo["name"]; ?></div>
                <a href="/user_auth/logout"> 登出</a>
            <?php } else { ?>
                <a href="/user_auth/login">登入</a>
                <span>/</span>
                <a href="/user_auth/signup">注册</a>
            <?php } ?>
        </div>
    </div>
    <div id="mainSec">
        <ul id="mainList">
            <?php
            foreach ($posts as $post) { ?>
                <li>
                    <div id="postReplyToSec">
                        <?php
                        if ($post["reply_to_update_time"])
                            echo "回复 " . $post["reply_to_user_name"] . " 在 " . $post["reply_to_update_time"] . ":";
                        ?>
                    </div>
                    <div id="postUpSec">
                        <div id="postUpLeftSec">
                            <div><?php echo $post["user_name"]; ?></div>
                            <div id="postTime"><?php echo $post["update_time"]; ?></div>
                            <?php if (isset($uinfo)) echo "<a href='/?replyTo=" . $post["id"] . "#bottomSec'>回复</a>" ?>
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
                            <video controls src="<?php echo "/uploads/" . $post['media']; ?>"></video>
                        </div>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <?php echo $posts->render(); ?>
    </div>
    <?php if (isset($uinfo)) { ?>
        <div id="bottomSec">
            <div id="bottomSec-replyTo">
                <?php if (isset($replyToPost))
                    echo "回复 " . $replyToPost["user"]["name"] . " 在 " . $post["update_time"] . " :";
                ?>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="postEdit">
                <input type="hidden" name="__token__" value="{$Request.token}">

                <label for="bottomSec-text" style="display: none;">编辑新发文</label>
                <textarea name="newPost" id="bottomSec-text" class="tinyMCE">
                    <?php if (isset($editContent)) echo $editContent; ?>
                </textarea>

                <div id="bottomSec-fileSelSec">
                    <label for="bottomSec-fileSelBut">上传视频:</label><br>
                    <input name="video" type="file" accept=".mp4,.mov,.avi,.mwv,.mkv,.mpg,.mpeg"
                        class="filepond" id="bottomSec-fileSelBut">
                </div>

                <div id="bottomSec-submit">
                    <button type="submit">发送</button>
                </div>
            </form>
        </div>
    <?php } ?>
</body>

</html>