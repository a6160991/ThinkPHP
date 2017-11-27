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
 * _check_uniqid
 * @param unknown_type $_first_uniqid
 * @param unknown_type $_end_uniqid
 */

function _check_uniqid($_first_uniqid,$_end_uniqid) {
	
	return $_first_uniqid;
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
	global $_system;
	// 去掉两边的空格
	$_string = trim($_string);

	// 长度小于两位或者大于20位
	if(mb_strlen($_string, 'utf-8') < $_min_num || mb_strlen($_string, 'utf-8') > $_max_num) {
		_alert_back('用户名长度小于'.$_min_num.'位或者大于'.$_max_num.'位');
	}

	// 限制敏感用户名
	$_mg = explode('|', $_system['string']);

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
function _check_password($_first_pass,$_end_pass,$_min_num) {
	//判断密码
	if (strlen($_first_pass) < $_min_num) {
		_alert_back('密码不得小于'.$_min_num.'位！');
	}
	
	//密码和密码确认必须一致
	if ($_first_pass != $_end_pass) {
		_alert_back('密码和确认密码不一致！');
	}
	
	//将密码返回
	return sha1($_first_pass);
}

function _check_modify_password($_string,$_min_num) {
	//判断密码
	if (!empty($_string)) {
		if (strlen($_string) < $_min_num) {
		_alert_back('密码不得小于'.$_min_num.'位！');
		}
	}
	return sha1($_string);
}

/**
 * _check_question 返回密码提示
 * @access public
 * @param  string $_string  
 * @param  int $_min_num 
 * @param  int $_max_num 
 * @return string $_string 返回密码提示
 */
function _check_question($_string,$_min_num,$_max_num) {
	// 长度小于4位或者大于20位
	if(mb_strlen($_string, 'utf-8') < $_min_num || mb_strlen($_string, 'utf-8') > $_max_num) {
		_alert_back('密码提示不得小于'.$_min_num.'位或者大于'.$_max_num.'位');
	}

	// 返回密码提示,转义
	return _mysql_string($_string);

}

function _check_answer($_ques,$_asnw,$_min_num,$_max_num) {
	$_asnw = trim($_asnw);
	if(mb_strlen($_asnw, 'utf-8') < $_min_num || mb_strlen($_asnw, 'utf-8') > $_max_num) {
		_alert_back('密码提示不得小于'.$_min_num.'位或者大于'.$_max_num.'位');
	 	}

	// 密码提示和回答不能一致
	if ($_ques == $_asnw) {
			_alert_back('密码提示与回答不能一致');
		}

	// 加密返回
	return sha1($_asnw);

}

function _check_sex($_string) {
	return _mysql_string($_string);
}

function _check_face($_string) {
	return _mysql_string($_string);
}

function _check_email($_string,$_min_num,$_max_num) {
	//参考bnbbs@163.com
	//[a-zA-Z0-9_] => \w
	//[\w\-\.] 16.3.
	//\.[\w+] .com.com.com.net.cn
	//正则挺起来比较麻烦，但是你理解了，就很简单了。
	//如果听起来比较麻烦，就直接套用

	if (!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/',$_string)) {
		_alert_back('邮件格式不正确！');
	}
	if (strlen($_string) < $_min_num || strlen($_string) > $_max_num) {
		_alert_back('邮件长度不合法！');
	}
	return _mysql_string($_string);
}


function _check_qq($_string) {
	if (empty($_string)) {
		return null;
	} else {
		//123456
		if (!preg_match('/^[1-9]{1}[\d]{4,9}$/',$_string)) {
			_alert_back('QQ号码不正确！');
		}
	}
	
	return _mysql_string($_string);
}


function _check_url($_string,$_max_num) {
	if (empty($_string) || ($_string == 'http://')) {
		return null;
	} else {
		//http://ww.yc60.com
		//?表示0次或者一次
		if (!preg_match('/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/',$_string)) {
			_alert_back('网址不正确！');
		}
		if (strlen($_string) > $_max_num) {
			_alert_back('网址太长！');
		}
	}
	
	return _mysql_string($_string);
}

function _check_content($_string) {
	if (mb_strlen($_string,'utf-8') < 10 || mb_strlen($_string,'utf-8') > 200) {
		_alert_back('短信内容不得小于10位或者大于200位！');
	}
	return $_string;
}

function _check_post_title($_string,$_mim,$_max) {
	if (mb_strlen($_string,'utf-8') < 2 || mb_strlen($_string,'utf-8') > 40) {
		_alert_back('标题长度不得小于'.$_mim.'位或者大于'.$_max.'位！');
	}
	return $_string;
}

function _check_post_content($_string,$_num) {
	if (mb_strlen($_string,'utf-8') < $_num) {
		_alert_back('帖子长度不得小于'.$_num.'位！');
	}
	return $_string;
}

function _check_autogarph($_string,$_num) {
	if (mb_strlen($_string,'utf-8') > $_num) {
		_alert_back('帖子长度不得小于'.$_num.'位！');
	}
	return $_string;
}

 ?>