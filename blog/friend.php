<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','friend');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_alert_back('请登录账号');
}
// 添加好友
if (isset($_GET['action']) == 'add') {
	include ROOT_PATH.'includes/check.func.php';
	_check_code($_POST['code'],$_SESSION['code']);
	$_clean = array();
	$_clean['touser'] = $_POST['touser'];
	$_clean['fromuser'] = $_COOKIE['username'];
	$_clean['content'] = _check_content($_POST['content']);
	$_clean = _mysql_string($_clean);
	// 不能添加自己
	if ($_clean['touser'] == $_clean['fromuser']) {
		_alert_close('无法添加自己!');
	}
	// 数据库好友是否已经添加
	if (!!$_rows = $sqli->_fetch_array("SELECT 
										bg_id 
										FROM 
										bg_friend 
										WHERE 
										(bg_touser='{$_clean['touser']}' 
										AND 
										bg_fromuser='{$_clean['fromuser']}') 
										OR
										(bg_touser='{$_clean['fromuser']}' 
										AND 
										bg_fromuser='{$_clean['touser']}') 
										LIMIT 1
										")) {
		_alert_close('已添加为好友');
	} else {
		// 添加好友信息
		$sqli->_query("INSERT INTO 
					bg_friend(
					bg_touser,
					bg_fromuser,
					bg_content,
					bg_date
					) 
					VALUES(
					'{$_clean['touser']}',
					'{$_clean['fromuser']}',
					'{$_clean['content']}',
					NOW()
					)
					");
		if ($sqli->_affected_rows() == 1) {
		$sqli->closeDb();
		_session_destroy();
		_alert_close('好友添加成功!');
		} else {
			$sqli->closeDb();
			_session_destroy();
			_alert_back('添加失败!');
		}
	}
}
// 获取数据
if (isset($_GET['id'])) {
	if (!!$_rows = $sqli->_fetch_array("SELECT 
		bg_username 
		FROM 
		bg_user 
		WHERE 
		bg_id='{$_GET['id']}' 
		LIMIT 1
		")) {
		$_html = array();
		$_html['touser'] = $_rows['bg_username'];
		$_html = _html($_html);
	} else {
		_alert_close('不存在此用户');
	}

} else {
	_alert_back('非法操作!');
} 

 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-添加好友</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>
<body>

<div id="message">
	<h3>添加好友</h3>
	<form method="post" action="?action=add">
	<input type="hidden" name="touser" value="<?php echo $_html['touser']?>" />
	<dl>
		<dd><input type="text" readonly="readonly" value="TO:<?php echo $_html['touser']?>" class="text" /></dd>
		<dd><textarea name="content">申请好友,字数补丁.....</textarea></dd>
		<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" /> <input type="submit" class="submit" value="发送短信" /></dd>
	</dl>
	</form>
</div>


</body>
</html>