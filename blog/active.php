<?php 
define('IN_TG',true);
//用来指定本页内容
define('SCRIPT','active');
//引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-激活</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<script type="text/javascript" src="js/registet.js"></script>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>

<div id="active">
	<h2>激活账户</h2>
	<p>本页面是为了模拟您的邮件的功能，点击以下超级连接激活您的账户</p>
	<p><a href="active.php?active=<?php echo $_GET['active'] ?>">激活</a></p>
</div>
	
<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>
</html>