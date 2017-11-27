<?php
session_start();
define('IN_TG',true);
//用来指定本页内容
define('SCRIPT','login');
//引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 登陆状态
_login_state();
global $_system;
if (@$_GET['action'] == 'login') {
	if (!empty($_system['code'])) {
		// 防止恶意注册
		_check_code($_POST['code'],$_SESSION['code']);
	}
	
	// 引入验证文件
	include ROOT_PATH.'/includes/login.func.php';
	// 接收数据
	$_clean = array();
	$_clean['username'] = _check_username($_POST['username'],2,20);
	$_clean['password'] = _check_password($_POST['password'],6);
	$_clean['time'] = _check_time($_POST['time']);
	if (!!$_rows = $sqli->_fetch_array("SELECT 
		bg_username,bg_level 
		FROM 
		bg_user 
		WHERE 
		bg_username='{$_clean['username']}' 
		AND 
		bg_password='{$_clean['password']}'")) {
		// 登录成功后,记录登录信息
		$sqli->_query("UPDATE bg_user SET 
						bg_last_time=NOW(),
						bg_last_ip='{$_SESSION["REMOTE_ADDR"]}',
						bg_login_count=bg_login_count+1
					WHERE
						bg_username='{$_rows['bg_username']}';
						");
		
		//_session_destroy();
		_setcookies($_rows['bg_username'],$_clean['time']);
		if ($_rows['bg_level'] == 1) {
			$_SESSION['admin'] = $_rows['bg_username'];
		}
		$sqli->closeDb();
		_location(null,'member.php');
	} else {
		$sqli->closeDb();
		//_session_destroy();
		_location('用户名密码不正确','login.php');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-登陆</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<link rel="shortcut icon" href="favicon.ico">
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
	
<div id="login">
	<h2>登陆</h2>
	<form method="post" name="login" action="login.php?action=login">
		<dl>
			<dt></dt>
			<dd>用&ensp;户&ensp;名 : <input type="text" name="username" class="text"></dd>
			<dd>密&emsp;&emsp;码 : <input type="password" name="password" class="text"></dd>
			<dd>保&emsp;&emsp;留 : <input type="radio" name="time" value="0" checked="checked" /> 不保留 <input type="radio" name="time" value="1" /> 一天 <input type="radio" name="time" value="2" /> 一周 <input type="radio" name="time" value="3" /> 一月</dd>
			<?php 
				if (!empty($_system['code'])) {	
			?>
			<dd>验&ensp;证&ensp;码 : <input type="text" name="code" class="text code"> <img src="code.php" id="code"></dd>
			<?php } ?>
			<dd><input type="submit" value="登录" class="button" /> <input type="button" value="注册" id="location" class="button location" /></dd>
		</dl>
	</form>
</div>

<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>
</html>