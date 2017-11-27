<?php 
session_start();

define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','article_del');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';

// 删除模块
if (@$_GET['action'] == 'del') {
    _check_code($_POST['code'],$_SESSION['code']); //验证码判断
    if (!!$_rows = $sqli->_fetch_array("SELECT 
        bg_active 
        FROM
        bg_user 
        WHERE 
        bg_username='{$_COOKIE['username']}' 
        LIMIT 
        1 
        ")) {
        // 开始修改
        include ROOT_PATH.'includes/check.func.php';
        $_clean = array();
        $_clean['id'] = $_POST['id'];

        // 执行数据库 
        $sqli->_query("UPDATE 
            bg_article 
            SET 
            bg_del_state=1 
            WHERE 
            bg_id='{$_clean['id']}' 
            LIMIT 1");

        if ($sqli->_affected_rows() == 1) { 
                // 获取新增的ID
                $sqli->closeDb();
                //_session_destroy();
                _location('删除成功!','index.php');
            } else {
                $sqli->closeDb();
                //_session_destroy();
                _alert_back('帖子修改失败!');
            }

    } else {
        _alert_back('非法登陆!');
    }
}
if (isset($_GET['id'])) {
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                        bg_title,
                                        bg_content 
                                        FROM 
                                        bg_article 
                                        WHERE 
                                        bg_id='{$_GET['id']}' 
                                        ")) {
        $_html['id'] = $_GET['id'];
        $_html['title'] = $_rows['bg_title'];
        $_html['content'] = $_rows['bg_content'];

        //判断权限
        if (!$_SESSION['admin']) {
            if ($_COOKIE['username'] != $_html['username']) {
                _alert_back('你没有权限修改！');
            }
        }

    } else {
        _alert_back('不存在此帖子！');
    }
}
?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
 <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>多用户留言系统--短信列表</title>
<?php 
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/member_modify_detail.js"></script>
 </head>
 <body>
<?php 
    require ROOT_PATH.'includes/header.inc.php';
?>
<div id="post">
    <h2>删除帖子</h2>
    <form method="post" name="del" action="?action=del">
    <input type="hidden" value="<?php echo $_html['id']; ?>" name="id">
        <dl>
            <dt>请确定是否要删除此贴</dt>
            <dd>标&ensp;&ensp;题 : <input type="text" name="title" value="<?php echo $_html['title']; ?>" class="text" readonly="readonly"> (*必填, 至少10位)
            </dd>
            <dd>
                <textarea name="content" rows="9" readonly="readonly"><?php echo $_html['content'] ?></textarea>
            </dd>
            <dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" /> <input type="submit" class="submit"  name="del" value="确定" /></dd>   
            </dl>
    </div>
</div>
<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>


