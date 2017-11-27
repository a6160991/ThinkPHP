<?php 
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','manage_member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//必须是管理员才能登录
_manage_login();
global $_pagesize,$_pagenum;
_page("SELECT bg_id FROM bg_user",15); 

if (@$_GET['action'] == 'lock') {
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                bg_active 
                                FROM 
                                bg_user 
                                WHERE 
                                bg_username='{$_COOKIE['username']}' 
                                LIMIT 
                                1"
    )) {
        $_inclean = array();
        $_inclean['id'] = $_GET['id'];

        // 修改数据
        $sqli->_query("UPDATE bg_user SET bg_state=1 WHERE bg_id='{$_inclean['id']}'");
        if ($sqli->_affected_rows() == 1) {   
            $sqli->closeDb();
            _location('封号成功!','manage_member.php');
        } else {
            $sqli->closeDb();
            _alert_back('封号失败!');
        }

    } else {
        _alert_back('非法登陆!');
    }
} 

if (@$_GET['action'] == 'unlock') {
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                bg_active 
                                FROM 
                                bg_user 
                                WHERE 
                                bg_username='{$_COOKIE['username']}' 
                                LIMIT 
                                1"
    )) {
        $_onclean = array();
        $_onclean['id'] = $_GET['id'];

        // 修改数据
        $sqli->_query("UPDATE bg_user SET bg_state=0 WHERE bg_id='{$_onclean['id']}'");
        if ($sqli->_affected_rows() == 1) {   
            $sqli->closeDb();
            _location('解封成功!','manage_member.php');
        } else {
            $sqli->closeDb();
            _alert_back('解封失败!');
        }

    } else {
        _alert_back('非法登陆!');
    }
} 

global $_pagesize, $_pagenum;
_page("SELECT bg_id FROM bg_user",15);

// 读列出表单
$_result = $sqli->_query("SELECT 
                    bg_id,
                    bg_state,
                    bg_level,
                    bg_username,
                    bg_email,
                    bg_reg_time
                    FROM 
                    bg_user 
                    ORDER BY 
                    bg_reg_time ASC 
                    LIMIT 
                    $_pagenum,$_pagesize
                            ");



?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--短信列表</title>
<?php 
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/member_message.js"></script>

</head>
<body>
<?php 
    require ROOT_PATH.'includes/header.inc.php';
?>

<div id="member">
<?php 
    require ROOT_PATH.'includes/manage.inc.php';
?>
    <div id="member_main">
    <h2>会员管理中心</h2>
    <form method="post" action="?action">
    <table id="tb" cellspacing="1">
        <tr>
            <th>ID号</th>
            <th>会员名</th>
            <th>邮件</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
            <?php 

                $_html = array();

                while (!!$_rows = $sqli->_fetch_array_list($_result)) {
                $_html['id'] = $_rows['bg_id'];
                $_html['state'] = $_rows['bg_state'];
                $_html['username'] = $_rows['bg_username'];
                $_html['email'] = $_rows['bg_email'];
                $_html['reg_time'] = $_rows['bg_reg_time'];
                $_html['level'] = $_rows['bg_level'];
                $_html = _html($_html);

                if ($_COOKIE['username'] == $_html['username']) {
                    $_html['job_html'] = '无权操作';
                } elseif ($_html['level'] == 1) {
                    $_html['job_html'] = '无权操作';
                } elseif ($_html['state'] == 1) {
                    $_html['job_html'] = ("<a href=\"javascript:if(confirm('是否确定要封号？')) location.href='manage_member.php?action=unlock&id={$_html['id']}'\">[解封]</a >");
                } else {
                    $_html['job_html'] = ("<a href=\"javascript:if(confirm('是否确定要解封？')) location.href='manage_member.php?action=unlock&id={$_html['id']}'\">[封号]</a >");
                }

             ?>
        <tr>
            <td><?php echo $_html['id'] ?></td>
            <td>
            <?php 
                if ($_SESSION['admin'] == $_html['username']) {
                    echo $_html['username'].'<font color="#FF0000">'.' (本)'.'</font>';
                } else {
                    echo $_html['username'];
                }
             ?>
            </td>

            <td><?php echo $_html['email'] ?></td>
            <td><?php echo $_html['reg_time'] ?></td>
            <td id="lock">

            <?php 
                echo $_html['job_html'];
            }
             ?> 

            </td>
        </tr>
    </table>
    </form>
    <?php 
        $sqli->_free_result($_result);
        _paging(2);
     ?>
    </div>
</div>
<?php 

    require ROOT_PATH.'includes/footer.inc.php';
 ?>
</body>
</html>