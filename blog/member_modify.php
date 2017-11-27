<?php 
session_start();
define('IN_TG',true);
// 用来指定本页内容
define('SCRIPT','member_modify');
// 引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//修改资料
if (@$_GET['action'] == 'modify') {
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows = $sqli->_fetch_array("SELECT bg_id FROM bg_user WHERE bg_username='{$_COOKIE['username']}'LIMIT 1")) {
		// 防止cookies伪造
		_uniqid($_rows['bg_active'],$_COOKIE['active']);
		include ROOT_PATH.'includes/check.func.php';
		$_clean = array();
		$_clean['password'] = _check_modify_password($_POST['password'],6);
		$_clean['sex'] = _check_sex($_POST['sex']);
		$_clean['face'] = _check_face($_POST['face']);
		$_clean['email'] = _check_email($_POST['email'],6,40);
		$_clean['qq'] = _check_qq($_POST['qq']);
		$_clean['url'] = _check_url($_POST['url'],40);
		$_clean['switch'] = $_POST['switch'];
		$_clean['autograph'] = _check_autogarph($_POST['autograph'],200);

		// 修改资料
		if (empty($_clean['password'])) {
			$sqli->_query("UPDATE bg_user SET
								bg_sex='{$_clean['sex']}',
								bg_face='{$_clean['face']}',
								bg_email='{$_clean['email']}',
								bg_qq='{$_clean['qq']}',
								bg_url='{$_clean['url']}',
								bg_switch='{$_clean['switch']}',
								bg_autograph='{$_clean['autograph']}'
							WHERE
								bg_username='{$_COOKIE['username']}' 
						");
		} else {
			$sqli->_query("UPDATE bg_user SET
								bg_sex='{$_clean['sex']}',
								bg_face='{$_clean['face']}',
								bg_email='{$_clean['email']}',
								bg_qq='{$_clean['qq']}',
								bg_url='{$_clean['url']}'
								bg_switch='{$_clean['switch']}'
								bg_autograph='{$_clean['autograph']}'
							WHERE
								bg_username='{$_COOKIE['username']}' 
						");
		}
	}
	// 判断是否修改成功
	if ($sqli->_affected_rows() == 1) {
		$sqli->closeDb();
		_session_destroy();
		_location('修改成功!','member.php');
	} else {
		$sqli->closeDb();
		_session_destroy();
		_location('修改失败!','member_modify.php');
	}
}
// 是否正常登陆
if (isset($_COOKIE['username'])) {
	// 获取数据
	$_rows = $sqli->_fetch_array("SELECT 
								bg_switch,bg_autograph,bg_username,bg_sex,bg_face,bg_email,bg_qq,bg_url 
								FROM 
								bg_user 
								WHERE 
								bg_username='{$_COOKIE['username']}'");
	if ($_rows) {
		$_html = array();
		$_html['username'] = ($_rows['bg_username']);
		$_html['sex'] = ($_rows['bg_sex']);
		$_html['face'] = ($_rows['bg_face']);
		$_html['email'] = ($_rows['bg_email']);
		$_html['url'] = ($_rows['bg_url']);
		$_html['qq'] = ($_rows['bg_qq']);
		$_html['switch'] = ($_rows['bg_switch']);
		$_html['autograph'] = ($_rows['bg_autograph']);
		$_html = _html($_html);
		// 性别选择
		if ($_html['sex'] == '男') {
			$_html['sex_html'] = '<input type="radio" name="sex" value="男" checked="checked" /> 男 <input type="radio" name="sex" value="女" /> 女';
		} elseif ($_html['sex'] == '女') {
			$_html['sex_html'] = '<input type="radio" name="sex" value="男" /> 男 <input type="radio" name="sex" value="女" checked="checked" /> 女';
		}

		// 头像选择
		$_html['face_html'] = '<select name="face">';
		foreach (range(1,9) as $_num) {
			$_html['face_html'] .= '<option value="face/m0'.$_num.'.gif">face/m0'.$_num.'.gif</option>';
		}
		foreach (range(10,64) as $_num) {
			$_html['face_html'] .= '<option value="face/m'.$_num.'.gif">face/m'.$_num.'.gif</option>';
		}
		$_html['face_html'] .= '</select>';

		// 签名开关
		if ($_html['switch'] == 1) {
			$_html['switch_html'] = '<input type="radio" name="switch" value="1"  checked="checked"> 启用 <input type="radio" name="switch" value="0"> 禁用'; 
		} elseif ($_html['switch'] == 0) {
			$_html['switch_html'] = '<input type="radio" name="switch" value="1"> 启用 <input type="radio" name="switch" value="0" checked="checked"> 禁用';
		}

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

<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/member_modify.js"></script>

<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
<div id="member">
	<?php 
		require 'includes/member.inc.php';
	 ?>
	<div id="member_main">
		<h2>会员管理中心</h2>
		<form method="post" name="modify" action="member_modify.php?action=modify">
		<dl>
			<dd>用&ensp;户&ensp;名 : <?php echo $_html['username'] ?></dd>
			<dd>密&emsp;&emsp;码 : <input type="password" name="password" class="text"> (空 则不修改)</dd>
			<dd>性&emsp;&emsp;别 : <?php echo $_html['sex_html'] ?></dd>
			<dd>头&emsp;&emsp;像 : <?php echo $_html['face_html'] ?></dd>
			<dd>电子邮件 : <input type="text" class="text" name="email" value="<?php echo $_html['email'] ?>" /></dd>
			<dd>主&emsp;&emsp;页 : <input type="text" class="text" name="url" value="<?php echo $_html['url'] ?>" /></dd>
			<dd>&emsp;QQ&emsp;&ensp;&thinsp;: <input type="text" class="text" name="qq" value="<?php echo $_html['qq'] ?>" /></dd>
			<dd>个性签名 : <?php echo $_html['switch_html'] ?>
				<p><textarea name="autograph"></textarea></p>
			</dd>
			<dd>验&ensp;证&ensp;码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" /> <input type="submit" class="submit" value="修改"></dd>
		</dl>
		</form>
	</div>
</div>
<?php require ROOT_PATH.'includes/footer.inc.php';?>
</body>

</html>