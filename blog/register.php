<?php
session_start();

define('IN_TG',true);
//用来指定本页内容
define('SCRIPT','register');
//引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
// 登陆状态
_login_state();
global $_system;
	//判断是否提交
if (@$_GET['action'] == 'register') {
	if (empty($_system['register'])) {
		exit('非法注册');
	}
	// 验证码注册
	_check_code($_POST['code'],$_SESSION['code']);
	// 引入验证文件
	include ROOT_PATH.'/includes/check.func.php';
	// 用空数组,存放提交过来的合法数据
	$_clean = array();
	$_clean['uniqid'] = _check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
	$_clean['active'] = _sha1_uniqid();
	$_clean['username'] = _check_username($_POST['username'],2,20);
	$_clean['password'] = _check_password($_POST['password'],$_POST['notpassword'],6);
	$_clean['question'] = _check_question($_POST['question'],2,20);
	$_clean['answer'] = _check_answer($_POST['question'],$_POST['answer'],2,20);
	$_clean['sex'] = _check_sex($_POST['sex']);
	$_clean['face'] = _check_face($_POST['face']);
	$_clean['email'] = _check_email($_POST['email'],6,40);
	$_clean['QQ'] = _check_qq($_POST['qq']);
	$_clean['url'] = _check_url($_POST['url'],40);
	// 新增前, 判断用户是否重复
	$sqli->_is_repeat(
		"SELECT bg_username FROM bg_user WHERE bg_username='{$_clean['username']}'",
		'此用户已被注册'
		);
	// 新增用户
	$sqli->_query("INSERT INTO
						bg_user
								(
								bg_uniqid,
								bg_active,
								bg_username,
								bg_password,
								bg_question,
								bg_answer,
								bg_sex,
								bg_face,
								bg_email,
								bg_qq,
								bg_url,
								bg_reg_time,
								bg_last_time,
								bg_last_ip
								) 
						VALUES (
								'{$_clean['uniqid']}',
								'{$_clean['active']}',
								'{$_clean['username']}',
								'{$_clean['password']}',
								'{$_clean['question']}',
								'{$_clean['answer']}',
								'{$_clean['sex']}',
								'{$_clean['face']}',
								'{$_clean['email']}',
								'{$_clean['QQ']}',
								'{$_clean['url']}',
								NOW(),
								NOW(),
								'{$_SERVER["REMOTE_ADDR"]}'
									)"
	);
	
	if ($sqli->_affected_rows() == 1) {
		// 获取新增的ID
		$_clean['id'] = mysqli_insert_id($sqli->_connect());
		$sqli->closeDb();
		_session_destroy();
		// 生产XML
		_set_xml('new.xml',$_clean);
		_location('注册成功!','index.php');
	} else {
		$sqli->closeDb();
		_session_destroy();
		_location('注册失败!','register.php');
	}
} else {
	$_SESSION['uniqid'] = $_uniqid = _sha1_uniqid();
}
	
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>留言系统-注册</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php'; 
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/register.js"></script>
</head>
<body>
	
<?php require ROOT_PATH.'includes/header.inc.php'; ?>
	
<div id="register">
	<h2>会员注册</h2>
	<?php if (!empty($_system['register'])) { ?>
	<form method="post" name="register" action="register.php?action=register">
	<input type="hidden" name="uniqid" value="<?php $_uniqid ?>" />
		<dl>
			<dt>请认真填写以下内容</dt>
			<dd>用&ensp;户&ensp;名 : <input type="text" name="username" class="text"> (*必填, 至少两位)</dd>
			<dd>密&emsp;&emsp;码 : <input type="password" name="password" class="text"> (*必填, 至少六位)</dd>
			<dd>确认密码 : <input type="password" name="notpassword" class="text"> (必填, 同上)</dd>
			<dd>密码提示 : <input type="text" name="question" class="text"> (*必填, 至少两位)</dd>
			<dd>密码回答 : <input type="text" name="answer" class="text"> (*必填, 至少两位)</dd>
			<dd>&emsp;性别&emsp; : <input type="radio" name="sex" value="男" checked="checked"> 男 <input type="radio" name="sex" value="女"> 女 </dd>
			<dd class="face"><input type="hidden" name="face" value="face/m01.gif" ><img src="face/m01.gif" alt="头像选择" id="faceimg"/></dd>
			<dd>电子邮件 : <input type="text" name="email" class="text"> (*必填)</dd>
			<dd>&emsp;Q Q&emsp;&thinsp;:  <input type="text" name="qq" class="text"></dd>
			<dd>主页地址 : <input type="text" name="url" class="text" value="http://"></dd>
			<dd>验&ensp;证&ensp;码：<input type="text" name="code" class="text yzm" /> <img src="code.php" id="code" /></dd>
			<dd><input type="submit" class="submit" value="注册" /></dd>
		</dl>
	</form>
	<?php } else {
		echo '<h4 style="text-align:center;padding:20px;">本站关闭了注册功能</h4>';
		} ?>
</div>

<?php require ROOT_PATH.'includes/footer.inc.php';?>

</body>
</html>