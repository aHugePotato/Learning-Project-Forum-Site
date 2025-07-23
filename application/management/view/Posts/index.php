<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style_management.css">
    <title>Posts</title>
    <style>
        #navMidSec {
            display: flex;
            list-style-type: none;
            gap: 20px;

            li {
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25em;

                &.currentPage {
                    border-bottom: 2px solid #1eb8ff;
                }

                & * {
                    color: black;
                }
            }
        }

        #mainSec {
            margin: auto;
            margin-top: 80px;
            margin-bottom: 80px;
            min-width: max(650px, 75vw);
            max-width: 900px;
            padding-left: 50px;
            padding-right: 50px;
        }

        #mainTable {
            width: 100%;
        }

        #mainSecTitle,
        #mainSecTitle2 {
            margin: 0px;
            font-size: 1.8em;
            padding-top: 1em;
            padding-bottom: 1em;
            font-weight: normal;
        }

        #sumTable td,
        #sumTable th {
            padding-left: 1em;
            padding-right: 1em;
        }
    </style>
</head>

<body>
    <div id="nav">
        <h1 id="title">综合管理</h1>
        <ul id="navMidSec">
            <li class="currentPage"><a href="/management/posts"><span>发文</span></a></li>
            <li><a href="/management/users"><span>用户</span></a></li>
            <li><a href="/management/admins"><span>管理员</span></a></li>
        </ul>
        <div id="uSec">
            <div><?php echo $ainfo["name"]; ?></div>
            <a href="/management/adminauth/logout"> 登出</a>
        </div>
    </div>
    <div id="mainSec">
        <h2 id="mainSecTitle">发文一览</h2>
        <table id="mainTable">
            <tr>
                <th>id</th>
                <th>内容缩略</th>
                <th>发布时间</th>
                <th>修改时间</th>
                <th>删除时间</th>
                <th>用户id</th>
                <th>用户名</th>
                <th>视频路径</th>
                <th>操作</th>
            </tr>
            <?php foreach ($tableData as $i) { ?>
                <tr>
                    <td><?php echo $i["id"]; ?></td>
                    <td><?php echo strip_tags($i["text"]) ?></td>
                    <td><?php echo $i["create_time"] ?></td>
                    <td><?php echo $i["update_time"] ?></td>
                    <td><?php echo $i["delete_time"] ?></td>
                    <td><?php echo $i["user"]["id"] ?></td>
                    <td><?php echo $i["user"]["name"] ?></td>
                    <td><?php echo $i["media"] ?></td>
                    <td><a href="<?php echo "/management/posts/detail/id/" . $i["id"] ?>">详细/管理</a></td>
                </tr>
            <?php } ?>
        </table>
        <?php echo $tableData->render() ?>
        <h2 id="mainSecTitle2">统计</h2>
        <table id="sumTable">
            <tr>
                <th>合计</th>
            </tr>
            <tr>
                <td><?php echo $tableSum[0]["count"] ?></td>
            </tr>
        </table>
    </div>
    <div id="bottomSec">
    </div>
</body>

</html>