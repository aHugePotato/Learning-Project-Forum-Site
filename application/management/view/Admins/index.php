<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style_management.css">
    <title>Posts</title>
    <style>
        #mainSec {
            margin-top: 50px;
        }

        #midSec {
            display: flex;
            list-style-type: none;
            gap: 20px;

            li {
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25em;
                
                &.currentPage{
                    border-bottom: 2px solid #1eb8ff;
                }

                &>* {
                    color: black;
                }
            }
        }
    </style>
</head>

<body>
    <div id="nav">
        <h1 id="title">综合管理</h1>
        <ul id="midSec">
            <li><a href="/management/posts"><span>发文</span></a></li>
            <li><a href="/management/users"><span>用户</span></a></li>
            <li class="currentPage"><a href="/management/admins"><span>管理员</span></a></li>
        </ul>
        <div id="uSec">
            <div><?php echo $ainfo["name"]; ?></div>
            <a href="/management/adminauth/logout"> 登出</a>
        </div>
    </div>
    <div id="mainSec">
        <br>
        管理员管理
    </div>
    <div id="bottomSec">
    </div>
</body>

</html>