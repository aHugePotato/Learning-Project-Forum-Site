<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style.css">
    <title>Document</title>
    <style>
        #mainSec {
            width: 350px;
            margin: auto;
            margin-top: 100px;
        }

        #mainFrom * {
            display: block;
        }

        #mainForm label {
            display: block;
            margin-top: 15px;
            width: 100%;
        }

        #mainForm input {
            width: 100%;
        }

        #mainForm button {
            margin-top: 15px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="mainSec">
        <form id="mainForm" action="" method="post">
            <label for="uemail">email</label>
            <input name="email" id="uemail" type="text" placeholder="输入" required>
            <label for="upwd">密码</label>
            <input name="password" id="upwd" type="password" placeholder="输入" required>
            <input type="hidden" name="__token__" value="{$Request.token}">
            <button type="submit">登入</button>
        </form>
    </div>
</body>

</html>