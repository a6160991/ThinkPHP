<?php 
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','member_message_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
    _alert_close('未登录账号!');
}
// 删除短信模块
if (isset($_GET['action']) == 'delete' && isset($_GET['id'])) {
    // 验证短信是否合法
    if (!!$_rows = $sqli->_fetch_array("SELECT 
        bg_id
        FROM 
        bg_message 
        WHERE 
        bg_id='{$_GET['id']}' 
        LIMIT 1
        ")) {
        // 删除单条短信
        $sqli->_query("DELETE FROM 
            bg_message 
            WHERE 
            bg_id='{$_GET['id']}' 
            LIMIT 1");
        if ($sqli->_affected_rows() == 1) {
            $sqli->closeDb();
            _session_destroy();
            _location('删除成功!','member_message.php');
        } else {
            $sqli->closeDb();
            _session_destroy();
            _alert_back('删除失败');
        }
    } else {
        _alert_back('信息不存在');
    }
}
// 处理id
if (isset($_GET['id'])) {
    $_rows = $sqli->_fetch_array("SELECT 
        bg_id,
        bg_state,
        bg_fromuser,
        bg_content,
        bg_time 
        FROM 
        bg_message 
        WHERE 
        bg_id='{$_GET['id']}' 
        LIMIT 1
        ");
    if ($_rows) {
        if (empty($_rows['bg_state'])) {
            $sqli->_query("UPDATE 
                bg_message 
                SET 
                bg_state=1 
                WHERE 
                bg_id='{$_GET['id']}' 
                LIMIT 1");
            if (!$sqli->_affected_rows()) {
                _alert_back('异常');
            }
        }
        $_html = array();
        $_html['id'] = $_rows['bg_id'];
        $_html['fromuser'] = $_rows['bg_fromuser'];
        $_html['content'] = $_rows['bg_content'];
        $_html['date'] = $_rows['bg_time'];
        $_html = _html($_html);
    } else {
        _alert_back('此信息不存在');
    }   
} else {
    _alert_back('非法登陆');
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
<div id="member">
<?php 
    require ROOT_PATH.'includes/member.inc.php';
?>
    <div id="member_main">
    <h2>信息详细</h2>
        <dl>
            <dd>发信人: <?php echo $_html['fromuser'] ?></dd>
            <dd>内容: <strong><?php echo $_html['content'] ?></strong></dd>
            <dd>发信时间: <?php echo $_html['date'] ?></dd>
            <dd class="button"><input type="button" value="返回列表" id="return"> <input type="button" id="delete" name="<?php echo $_html['id'] ?>" value="删除"></dd>
        </dl>
    </div>
</div>
<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>
