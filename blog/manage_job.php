<?php 
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','manage_job');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 必须是管理员才能登陆
_manage_login();
// 添加管理员
if (@$_GET['action'] == 'add') {
	if (!!$_rows = $sqli->_fetch_array("SELECT 
								bg_active 
								FROM 
								bg_user 
								WHERE 
								bg_username='{$_COOKIE['username']}' 
								LIMIT 
								1"
	)) {
	
		$_clean = array();
		$_clean['username'] = $_POST['manage'];

		// 添加
		$sqli->_query("UPDATE bg_user SET bg_level=1 WHERE bg_username='{$_clean['username']}'");
		if ($sqli->_affected_row() == 1) {
			$sqli->closeDb();
			_location('添加成功', 'SCRIPT'.php);
		} else {
			$sqli->closeDb();
			_alert_back('添加失败');
		}
	} else {
		_alert_back('非法登陆');
	}
}

// 退出管理员权限
if (@$_GET['action'] == 'job' && isset($_GET['id'])) {
	if (!!$_rows = $sqli->_fetch_array("SELECT 
								bg_active 
								FROM 
								bg_user 
								WHERE 
								bg_username='{$_COOKIE['username']}' 
								LIMIT 
								1"
	)) {
		// 退出
		$sqli->_query("UPDATE bg_user SET bg_level=0 WHERE bg_username='{$_COOKIE['usernmae']}' AND bg_id='{$_GET['id']}'");
		if ($sqli->_affected_row() == 1) {
				$sqli->closeDb();
				_location('退出成功', 'index.php');
			} else {
				$sqli->closeDb();
				_alert_back('退出失败');
			}
	} else {
		_alert_back('非法登陆');
	}
}

global $_pagesize, $_pagenum;
_page("SELECT bg_id FROM bg_user",15); 
// 从数据库提取数据
$_result = mysqli_query($sqli->_connect(),"SELECT 
											bg_id,
											bg_state,
											bg_username,
											bg_email,
											bg_reg_time 
											FROM 
											bg_user 
											WHERE 
											bg_level=1 
											ORDER BY 
											bg_reg_time 
											DESC 
											LIMIT 
											$_pagenum,$_pagesize");



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
	<table cellspacing="1">
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
				$_html['username'] = $_rows['bg_username'];
				$_html['email'] = $_rows['bg_email'];
				$_html['reg_time'] = $_rows['bg_reg_time'];
				$_html = _html($_html);
				if ($_COOKIE['username'] == $_html['username']) {
					$_html['job_html'] = '<a href="manage_job.php?action=job&id='.$_html['id'].'">退出</a>';
				} else {
					$_html['job_html'] = '无权操作';
				}
			 ?>
			<tr>
			<td><?php echo $_html['id'] ?></td>
			<td><?php echo $_html['username'] ?></td>
			<td><?php echo $_html['email'] ?></td>
			<td><?php echo $_html['reg_time'] ?></td>
			<td><a href="?action=del&id=<?php echo $_html['id'] ?>"></a><?php echo $_html['job_html'] ?></td>
			</tr>
			<?php } ?>
		</tr>
	</table>
	<form method="post" action="?add">
		<input type="text" name="manage" class="text"> <input type="submit" value="添加管理员" >
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
