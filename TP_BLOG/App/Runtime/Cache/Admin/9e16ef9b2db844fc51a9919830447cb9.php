<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台登录</title>
    <link href="http://localhost/TP_BLOG/App/Admin/Public/css/admin_login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="admin_login_wrap">
    <h1>后台管理</h1>
    <div class="adming_login_border">
        <div class="admin_input">
            <form action="" method="post">
                <ul class="admin_items">
                    <li>
                        <label for="user">用户名：</label>
                        <input type="text" name="username" value="" id="user" size="35" class="admin_input_style" />
                    </li>
                    <li>
                        <label for="pwd">密码：</label>
                        <input type="password" name="password" value="" id="pwd" size="35" class="admin_input_style" />
                    </li>
                    <li>
                        <label for="pwd">验证码：</label>
                        <input type="password" name="varify" value="" id="varify" size="15" class="admin_input_style" /> <img align="absmiddle" width="100" height="40" src="/TP_BLOG/index.php/Admin/Login/verify" style="cursor: pointer;" onclick="this.src='/TP_BLOG/index.php/Admin/Login/verify'+'?'+Math.random()">
                    </li>
                    <li>
                        <input type="submit" tabindex="3" value="提交" class="btn btn-primary" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
</body>
</html>