<?php
session_start();

define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','article_modfiy');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_location('发帖前，必须登录','login.php');
}

// 修改数据
if (@$_GET['action'] == 'modify') {
	_check_code($_POST['code'],$_SESSION['code']); //验证码判断
	if (!!$_rows = $sqli->_fetch_array("SELECT 
									bg_active 
									FROM 
									bg_user 
									WHERE 
									bg_username='{$_COOKIE['username']}' 
									LIMIT 
									1"
									)) {

			// 开始修改
			include ROOT_PATH.'includes/check.func.php';
			$_clean = array();
			$_chean['id'] = $_POST['id'];
			$_clean['type'] = $_POST['type'];
			$_clean['title'] = _check_post_title($_POST['title'],2,40);
			$_clean['content'] = _check_post_content($_POST['content'],5);
			$_clean = _mysql_string($_clean);

			// 执行SQL
			$sqli->_query("UPDATE 
					bg_article 
					SET 
					bg_type='{$_clean['type']}',
					bg_title='{$_clean['title']}',
					bg_content='{$_clean['content']}',
					bg_last_modify_date=NOW() 
					WHERE 
					bg_id='{$_chean['id']}'
					");

			if ($sqli->_affected_rows() == 1) {	
				// 获取新增的ID
				$sqli->closeDb();
				//_session_destroy();
				_location('帖子修改成功！','article.php?id='.$_POST['id']);
			} else {
				$sqli->closeDb();
				//_session_destroy();
				_alert_back('帖子修改失败!');
			}
		} else {
			_alert_back('非法登陆');
		}
	}

//读取数据
if (isset($_GET['id'])) {
		if (!!$_rows = $sqli->_fetch_array("SELECT 
									bg_username,
									bg_title,
									bg_type,
									bg_content
									FROM 
									bg_article 
									WHERE
									bg_reid=0
									AND
									bg_id='{$_GET['id']}'")) {
			$_html = array();
			$_html['id'] = $_GET['id'];
			$_html['username'] = $_rows['bg_username'];
			$_html['title'] = $_rows['bg_title'];
			$_html['type'] = $_rows['bg_type'];
			$_html['content'] = $_rows['bg_content'];
			$_html = _html($_html);		

			//判断权限
			if (!$_SESSION['admin']) {
				if ($_COOKIE['username'] != $_html['username']) {
					_alert_back('你没有权限修改！');
				}
			}
			
		} else {
			_alert_back('不存在此帖子！');
		}
} else {
	_alert_back('非法操作！');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-修改发帖</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>

<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/post.js"></script>

</head>
<body>
	
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
	
<div id="post">
	<h2>修改发帖</h2>
	<form method="post" name="post" action="?action=modify">
	<input type="hidden" value="<?php echo $_html['id']; ?>" name="id">
		<dl>
			<dt>请修改以下内容</dt>
			<dd>
				类　　型：
				<?php 
					foreach (range(1,16) as $_num) {
						if ($_num == $_html['type']) {
							echo '<label for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'" checked="checked" /> ';
						} else {
							echo '<label for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'" /> ';
						}
						echo ' <img src="images/icon'.$_num.'.gif" alt="类型" /></label>';
						// 图片对齐
						if ($_num == 8) {
							echo '<br />　　　 　　';
						}
					}
				?>
			</dd>
			<dd>标&ensp;&ensp;题 : <input type="text" name="title" value="<?php echo $_html['title']; ?>" class="text"> (*必填, 至少10位)</dd>
			<dd id="q">贴　　图：　<a href="javascript:;">Q图系列[1]</a>　 <a href="javascript:;">Q图系列[2]</a>　 <a href="javascript:;">Q图系列[3]</a></dd>
			<dd>
				<?php include ROOT_PATH.'includes/ubb.inc.php' ?>
				<textarea name="content" rows="9"><?php echo $_html['content'] ?></textarea>
			</dd>
			<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" onclick="javascript:this.src='code.php?tm='+Math.random();" /> <input type="submit" class="submit" value="修改帖子" /></dd>
		</dl>
	</form>
</div>

<?php require ROOT_PATH.'includes/footer.inc.php';?>

</body>
</html>