<?php 
// 防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}

if (!function_exists('_alert_back')) {
	exit('_alert_back()函数不存在, 请检查');
}

if (!function_exists('_mysql_string')) {
	exit('_mysql_string()函数不存在，请检查!');
}
/**
 * _setcookies 生成登陆cookies
 * @param  [type] $_username [description]
 * @return [type]            [description]
 */
function _setcookies($_username,$_time) {
	switch ($_time) {
		case '0':	//浏览器进程
			setcookie('username', $_username);
			break;
		case '1':	//一天
			setcookie('username', $_username, time()+3600*24);
			break;
		case '2':	//一周
			setcookie('username', $_username, time()+3600*24*7);
			break;
		case '3':	//一月
			setcookie('username', $_username,time()+3600*24*30);
			break;	
		default:
			
			break;
	}
}

/**
 * _check_username 表示检测并过滤用户名
 * @access public 
 * @param string $_string  受污染的用户名
 * @param int $_min_num 最小位数
 * @param int $_max_num 最大位数
 * @return return string 过滤后的用户名
 */
function _check_username($_string,$_min_num,$_max_num){
	// 去掉两边的空格
	$_string = trim($_string);

	// 长度小于两位或者大于20位
	if(mb_strlen($_string, 'utf-8') < $_min_num || mb_strlen($_string, 'utf-8') > $_max_num) {
		_alert_back('用户名长度小于'.$_min_num.'位或者大于'.$_max_num.'位');
	}
	// 限制敏感字符
	$_char_pattern = '/[<>\'\"\ \	]/';
	if (preg_match($_char_pattern,$_string)) {
		_alert_back('用户名包含敏感字符');
	}
	// 将用户名转义输入
	return _mysql_string($_string);
}

/**
 * _check)password 验证密码
 * @access public
 * @param  string $_first_pass 
 * @param  string $_end_pass   
 * @param  int $_min_num    
 * @return string $_first_pass 返回一个加密后的密码
 */
function _check_password($_string,$_min_num) {
	//判断密码
	if (strlen($_string) < $_min_num) {
		_alert_back('密码不得小于'.$_min_num.'位！');
	}
	
	//将密码返回
	return sha1($_string);
}

function _check_time($_string) {
	$_time = array('0','1','2','3');
	if (!in_array($_string,$_time)) {
		_alert_back('保留方式出错！');
	}
	return $_string;
}
 ?>