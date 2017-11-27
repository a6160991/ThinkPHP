<?php 
//用来授权调用includes里的文件
define('IN_TG',true);
//用来指定本页内容
define('SCRIPT','face');

//引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-头像选择</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<script type="text/javascript" src="js/opener.js"></script>
</head>
<body>

<div id="face">
	<h3>选择头像</h3>
	<dl>
		<?php foreach(range(1,9) as $num) { ?>
		<dd><img src="face/m0<?php echo $num; ?>.gif" alt="face/m0<?php echo $num; ?>" title="头像<?php echo $num; ?>"/></dd>
		<?php } ?>

	</dl>
	<dl>
		<?php foreach(range(10,64) as $num) { ?>
		<dd><img src="face/m<?php echo $num; ?>.gif" alt="face/m<?php echo $num; ?>" title="头像<?php echo $num; ?>"/></dd>
		<?php } ?>

	</dl>
</div>

</body>
</html>