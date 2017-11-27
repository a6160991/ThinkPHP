<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','message');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_alert_back('请登录账号');
}
// 写信息
if (isset($_GET['action']) == 'write') {
	include ROOT_PATH.'includes/check.func.php';
	// 验证码注册
	$_clean = array();
	_check_code($_POST['code'],$_SESSION['code']);
	$_clean['touser'] = $_POST['touser'];
	$_clean['fromuser'] = $_COOKIE['username'];
	$_clean['content'] = _check_content($_POST['content']);
	// 写入表
	$sqli->_query("INSERT INTO bg_message(
									bg_touser,
									bg_fromuser,
									bg_content,
									bg_time
									)
							VALUES(
									'{$_clean['touser']}',
									'{$_clean['fromuser']}',
									'{$_clean['content']}',
									NOW()
								)
	");
	// 新增成功
	if ($sqli->_affected_rows() == 1) {
		$sqli->closeDb();
		_session_destroy();
		_alert_close('信息发送成功');
	} else {
		$sqli->closeDb();
		_session_destroy();
		_alert_back('发送失败');
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
	<title>留言系统-写短信</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>
<body>

<div id="message">
	<h3>写短信</h3>
	<form method="post" action="?action=write">
	<input type="hidden" name="touser" value="<?php echo $_html['touser']?>" />
	<dl>
		<dd><input type="text" readonly="readonly" value="TO:<?php echo $_html['touser']?>" class="text" /></dd>
		<dd><textarea name="content"></textarea></dd>
		<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" /> <input type="submit" class="submit" value="发送短信" /></dd>
	</dl>
	</form>
</div>


</body>
</html>