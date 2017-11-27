
<?php 
// 防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}

// 设置字符集编码
header('Content-Type: text/html; charset=utf-8');

// 转换路径
define('ROOT_PATH',substr(dirname(__FILE__),0,-8));

//拒绝PHP低版本
if (PHP_VERSION < '5.6.0') {
	exit('Version is too Low!');
}

// 引入核心函数库
require ROOT_PATH.'includes/mysql.class.php';
require ROOT_PATH.'includes/global.func.php';


// 执行耗时
define('START_TIME',_runtime());

// 实例化数据库类
$sqli = new DbMysqli();	

// 短信提醒
$_message = @$sqli->_fetch_array("SELECT 
									COUNT(bg_id) 
								AS 
									count 
								FROM 
									bg_message 
								WHERE 
									bg_state=0
						 	    AND
					   			bg_touser='{$_COOKIE['username']}'
");
if (empty($_message['count'])) {
	$GLOBALS['message'] = '<strong class="noread"><a href="member_message.php">(0)</a></strong>';
} else {
	$GLOBALS['message'] = '<strong class="read"><a href="member_message.php">('.$_message['count'].')</a></strong>';
}

// 网站系统初始化
if (!!$_rows = $sqli->_fetch_array("SELECT 
									bg_webname,
									bg_article,
									bg_blog,
									bg_photo,
									bg_skin,
									bg_string,
									bg_post,
									bg_re,
									bg_code,
									bg_register 
									FROM 
									bg_system 
									WHERE 
									bg_id=1 
									LIMIT 
									1"
									)) 
	{
		$_system = array();
		$_system['webname'] = $_rows['bg_webname'];
		$_system['article'] = $_rows['bg_article'];
		$_system['blog'] = $_rows['bg_blog'];
		$_system['skin'] = $_rows['bg_skin'];
		$_system['string'] = $_rows['bg_string'];
		$_system['post'] = $_rows['bg_post'];
		$_system['re'] = $_rows['bg_re'];
		$_system['code'] = $_rows['bg_code'];
		$_system['register'] = $_rows['bg_register'];
		$_system = _html($_system);
	} else {
		exit('数据表异常,请检查!');
	}


?>