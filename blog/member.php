<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','member');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 是否正常登陆
if (isset($_COOKIE['username'])) {
	// 获取数据
	$_rows = $sqli->_fetch_array("SELECT bg_username,bg_sex,bg_face,bg_email,bg_qq,bg_level,bg_url,bg_reg_time FROM bg_user WHERE bg_username='{$_COOKIE['username']}'LIMIT 1");
	if ($_rows) {
		$_html = array();
		$_html['username'] = _html($_rows['bg_username']);
		$_html['sex'] = _html($_rows['bg_sex']);
		$_html['face'] = _html($_rows['bg_face']);
		$_html['email'] = _html($_rows['bg_email']);
		$_html['qq'] = _html($_rows['bg_qq']);
		$_html['url'] = _html($_rows['bg_url']);
		switch ($_rows['bg_level']) {
			case 0:
				$_html['level'] = '普通会员';
				break;
			
			case 1:
				$_html['level'] = '管理员';
				break;
			default:
				$_html['level'] = '出错';

		}
		$_html['reg_time'] = _html($_rows['bg_reg_time']);
		$_html = _html($_html);
	} else {
		_alert_back('此用户不存在'); 
	}
} else {
	_alert_back('非法登陆');
}

 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<link rel="stylesheet" type="text/css" href="styles/1/blog.css">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
 	<title>留言系统-个人中心</title>
</head>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>

<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="member">
	<?php 
		require 'includes/member.inc.php';
	 ?>
	<div id="member_main">
		<h2>会员管理中心</h2>
		<dl>
			<dd>用&ensp;户&ensp;名 : <?php echo $_html['username'] ?></dd>
			<dd>性&emsp;&emsp;别 : <?php echo $_html['sex'] ?></dd>
			<dd>头&emsp;&emsp;像 : <?php echo $_html['face'] ?></dd>
			<dd>电子邮件 : <?php echo $_html['email'] ?></dd>
			<dd>主&emsp;&emsp;页 : <?php echo $_html['url'] ?></dd>
			<dd>&emsp;QQ&emsp;&ensp;&thinsp;: <?php echo $_html['qq'] ?></dd>
			<dd>注册时间 : <?php echo $_html['reg_time'] ?></dd>
			<dd>身&emsp;&emsp;份 : <?php echo $_html['level'] ?></dd>
		</dl>
	</div>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>

</html>