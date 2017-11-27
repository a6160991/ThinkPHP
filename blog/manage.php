<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','manage');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 必须是管理员才能登陆
_manage_login();




 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<link rel="stylesheet" type="text/css" href="styles/1/blog.css">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
 	<title>留言系统-后台中心</title>
</head>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>

<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="member">
	<?php 
		require 'includes/manage.inc.php';
	 ?>
	<div id="member_main">
		<h2>后台管理中心</h2>
		<dl>
			<dd>·服务器主机名称：<?php echo $_SERVER['SERVER_NAME']; ?></dd>
			<dd>·服务器版本：<?php echo PHP_OS; ?></dd>
			<dd>·通信协议名称/版本：<?php echo $_SERVER['SERVER_PROTOCOL']; ?></dd>
			<dd>·服务器IP：<?php echo $_SERVER["SERVER_ADDR"]; ?></dd>
			<dd>·客户端IP：<?php echo $_SERVER["REMOTE_ADDR"]; ?></dd>
			<dd>·服务器端口：<?php echo $_SERVER['SERVER_PORT']; ?></dd>
			<dd>·客户端端口：<?php echo $_SERVER["REMOTE_PORT"]; ?></dd>
			<dd>·管理员邮箱：<?php echo $_SERVER['SERVER_ADMIN'] ?></dd>
			<dd>·Host头部的内容：<?php echo $_SERVER['HTTP_HOST']; ?></dd>
			<dd>·服务器主目录：<?php echo $_SERVER["DOCUMENT_ROOT"]; ?></dd>
			<dd>·服务器系统盘：<?php echo $_SERVER["SystemRoot"]; ?></dd>
			<dd>·脚本执行的绝对路径：<?php echo $_SERVER['SCRIPT_FILENAME']; ?></dd>
			<dd>·Apache及PHP版本：<?php echo $_SERVER["SERVER_SOFTWARE"]; ?></dd>
		</dl>
	</div>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>

</html>