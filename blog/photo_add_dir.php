<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','photo_add_dir');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 管理员才能登陆
_manage_login();
 ?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<link rel="stylesheet" type="text/css" href="styles/1/blog.css">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
 	<title>留言系统-blog</title>
<script type="text/javascript" src="js/blog.js"></script>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>

<div id="photo">
	<h2>添加相册目录</h2>
	
</div>

<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>

</html>