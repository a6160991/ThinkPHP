<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','blog');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 分页模块
global $_pagesize, $_pagenum,$_system;
_page("SELECT bg_id FROM bg_user",$_system['blog']); //第一个参数获取总参数, 第二个参数指定每页多少条
// 从数据库提取数据
$_result = mysqli_query($sqli->_connect(),"SELECT bg_id,bg_username,bg_sex,bg_face FROM bg_user ORDER BY bg_reg_time DESC LIMIT $_pagenum,$_pagesize");

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

<div id="blog">
	<h2>列表</h2>
	<?php
		$_html = array();
		while (!!$_rows = $sqli->_fetch_array_list($_result)) {
		$_html['id'] = $_rows['bg_id'];
		$_html['username'] = $_rows['bg_username'];
		$_html['face'] = $_rows['bg_face'];
		$_html['sex'] = $_rows['bg_sex'];
		$_html = _html($_html);
	 ?>
	<dl>
		<dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
		<dt><img src="<?php echo $_html['face']?>" alt="炎日" /></dt>
		<dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
		<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['id']?>">加为好友</a></dd>
		<dd class="guest"> 留言</dd>
		<dd class="gift"><a href="javascript:;" name="gift" title="<?php echo $_html['id']?>"> 送礼物</a></dd>
	</dl>
	<?php }
		$sqli->_free_result($_result);

		// _paging函数调用分页, 1或2, 1表示数字分页, 2表示文本分页
		_paging(2);
	?>
	
	
</div>

<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>

</html>