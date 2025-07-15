<!DOCTYPE html>
<html lang="ch">

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

        #bottomSec {
            background-color: white;
            height: 60px;
            width: 800px;
            border: 2px solid rgb(215, 215, 215);
            border-bottom: none;
            position: fixed;
            left: calc(50vw - 400px);
            bottom: 0px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
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
                            <div id="postTime"><?php echo $post["create_time"]; ?></div>
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
                <input type="text" name="newPost" value="<?php if (isset($editContent)) echo $editContent; ?>">
                <input type="hidden" name="__token__" value="{$Request.token}">
                <button type="submit">发送</button>
            </form>
        </div>
    <?php } ?>
</body>

</html>