<?php 
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','member_gift');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_alert_close('未登录账号!');
}
// 批量删除
if (isset($_GET['action']) == 'delete' && isset($_POST['ids'])) {
	$_clean = array();
	$_clean['ids'] = $sqli->_mysql_string(implode(',',$_POST['ids']));
	// 防止非法登陆
	if (!!$_rows = $sqli->_fetch_array("SELECT 
								bg_active 
								FROM 
								bg_user 
								WHERE 
								bg_username='{$_COOKIE['username']}' 
								LIMIT 
								1"
	)) {
		$sqli->_query("	DELETE FROM 
						bg_gift 
						WHERE 
						bg_id 
						IN 
						({$_clean['ids']})"
		);
		if ($sqli->_affected_rows()) {
			$sqli->_closeDb();
			_location('礼物删除成功','member_gift.php');
		} else {
			$sqli->_closeDb();
			_alert_back('礼物删除失败');
		}
	} else {
		_alert_back('非法登录');
	}
}
// 分页模块
global $_pagesize, $_pagenum;
_page("SELECT bg_id FROM bg_gift WHERE bg_touser='{$_COOKIE['username']}'",15);
// 从数据库提取数据
$_result = mysqli_query($sqli->_connect(),"SELECT 
											bg_id,
											bg_fromuser,
											bg_gift,
											bg_content,
											bg_date 
											FROM 
											bg_gift 
											WHERE 
											bg_touser='{$_COOKIE['username']}' 
											ORDER BY 
											bg_date 
											DESC LIMIT 
											$_pagenum,$_pagesize");
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--礼物列表</title>
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
	require ROOT_PATH.'includes/member.inc.php';
?>
	<div id="member_main">
		<h2>花朵管理中心</h2>
		<form method="post" action="?action=delete">
		<table cellspacing="1">
			<tr>
				<th>送花人</th>
				<th>花朵数目</th>
				<th>感言</th>
                <th>时间</th>
				<th>操作</th>
			</tr>
			<?php 
				$_html = array();
				while (!!$_rows = $sqli->_fetch_array_list($_result)) {
					$_html['id'] = $_rows['bg_id'];
					$_html['fromuser'] = $_rows['bg_fromuser'];
					$_html['content'] = $_rows['bg_content'];
					$_html['gift'] = $_rows['bg_gift'];
					$_html['date'] = $_rows['bg_date'];
					$_html = _html($_html);
					$_html['count'] = $_html['gift'];
			?>
			<tr>
			<td><?php echo $_html['fromuser']?></td>
			<td><img src="images/x4.gif" alt="花朵" /> x <?php echo $_html['gift']?>朵</td>
			<td><?php echo _title($_html['content'],15)?></td>
			<td><?php echo $_html['date']?></td>
			<td><input name="ids[]" value="<?php echo $_html['id']?>" type="checkbox" /></td>
            </tr>
			
			<tr>
			<tr>
            <td colspan="5">共<strong><?php echo $_html['count']?></strong>朵花</td>
            </tr>
			<?php 
				}
				$sqli->_free_result($_result);
			?>
			<td colspan="5"><label for="all">全选 <input type="checkbox" name="chkall" id="all" /></label> <input type="submit" value="批删除" /></td>
			</tr>
		</table>
		</form>
		<?php _paging(2);?>
	</div>
</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>