<?php 
/****************
 *  核心函数库	*
 ****************/

function _manage_login() {
	if ((!isset($_COOKIE['username'])) || (!isset($_SESSION['admin']))) {
		_alert_back('非法登陆');
	}	
}

// 发帖时间限制
function _timed($_nowtime,$_pretime,$_second) {
	if ($_nowtime - $_pretime < $_second) {
		_alert_back('发帖过于频繁!');
	}
}

/**
 * _runtime用来获取执行耗时
 * @access public 
 * @return float 表示返回浮点型数字
 */
function _runtime() {
	$_mtime = explode(' ',microtime());
	return $_mtime[1] + $_mtime[0];
}

/**
 * _alert_back 表示JS弹窗
 * @access public 
 * @param   $_info 
 * @return  void 弹窗
 */
function _alert_back($_info) {
	echo "<script type='text/javascript'>alert('$_info');history.back();</script>";
	exit(); 
}

// 页面跳转
function _alert_close($_info) {
	echo "<script type='text/javascript'>alert('$_info');window.close();</script>";
	exit();
}

/**
 * _login_state登录状态的判断
 */
function _location($_info,$_url) {
	if (!empty($_info)) {
		echo "<script type='text/javascript'>alert('$_info');location.href='$_url';</script>";
		exit();
	} else {
		header('Location:'.$_url);
	}
}

// 登陆状态的判断
function _login_state() {
	if (isset($_COOKIE['username'])) {
		_alert_back('已登陆');
		_location('null','index.php');
	}
}

// 销毁当前会话中的全部数据
function _session_destroy() {
	if (@session_start()) {
		session_destroy();
	}
}

// 清除cookies
function _unsetcookies() {
	setcookie('username','',time()-1);
	session_destroy();
	_location(null,'index.php');
}

/*
	删除反斜杠
	addslashes()单引号（'）双引号（"）反斜杠（\）前添加反斜杠
 */
function _mysql_string($_string) {
	$_string = is_array($_string) ?
               array_map('_mysql_string', $_string) :
               stripslashes($_string);

    return $_string;

}

function _sha1_uniqid() {
	return sha1(uniqid(rand(),true));
}

function _get_xml($_xmlfile) {
	$_html = array();
	if (file_exists($_xmlfile)) {
		$_xml = file_get_contents($_xmlfile);
		preg_match_all('/<vip>(.*)<\/vip>/s',$_xml,$_dom);
		foreach ($_dom[1] as $_value) {
			preg_match_all('/<id>(.*)<\/id>/s',$_value,$_id);
			preg_match_all('/<username>(.*)<\/username>/s',$_value,$_username);
			preg_match_all( '/<sex>(.*)<\/sex>/s', $_value, $_sex);
			preg_match_all( '/<face>(.*)<\/face>/s', $_value, $_face);
			preg_match_all( '/<email>(.*)<\/email>/s', $_value, $_email);
			preg_match_all( '/<url>(.*)<\/url>/s', $_value, $_url);
			$_html['id'] = $_id[1][0];
			$_html['username'] = $_username[1][0];
			$_html['sex'] = $_sex[1][0];
			$_html['face'] = $_face[1][0];
			$_html['email'] = $_email[1][0];
			$_html['url'] = $_url[1][0];
		}
	} else {
		echo '文件不存在';
	}
	return $_html;
}

function _set_xml($_xmlfile,$_clean) {
	$_fp = @fopen('new.xml','w');
	if (!$_fp) {
		exit('系统错误，文件不存在！');
	}
	flock($_fp,LOCK_EX);
	
	$_string = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "<vip>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<id>{$_clean['id']}</id>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<username>{$_clean['username']}</username>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<sex>{$_clean['sex']}</sex>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<face>{$_clean['face']}</face>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<email>{$_clean['email']}</email>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "\t<url>{$_clean['url']}</url>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string = "</vip>";
	fwrite($_fp,$_string,strlen($_string));
	
	flock($_fp,LOCK_UN);
	fclose($_fp);
}

function _ubb($_string) {
	$_string = nl2br($_string);
	$_string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$_string);
	$_string = preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$_string);
	$_string = preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$_string);
	$_string = preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$_string);
	$_string = preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$_string);
	$_string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$_string);
	$_string = preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$_string);
	$_string = preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1">\1</a>',$_string);
	$_string = preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="图片" />',$_string);
	$_string = preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:480px;height:400px;" src="\1" />',$_string);
	return $_string;
}

/**
 * 判断标识符是否异常
 * @param  [type] $_mysql_uniqid  [description]
 * @param  [type] $_cookie_uniqid [description]
 * @return [type]                 [description]
 */
function _uniqid($_mysql_uniqid,$_cookie_uniqid) {
	if ($_mysql_uniqid != $_cookie_uniqid) {
		_alert_back('数据异常!');
	}
}

/**
 * _check_coed
 * @param  sting $_fist_code 
 * @param  string $_end_code  
 * @return viod  验证码比对
 */
function _check_code($_fist_code,$_end_code) {
	if ($_fist_code != $_end_code) {
		_alert_back('验证码不正确!');
	}
}
/**
 * _title() 截取函数
 * @param  [type] $_string [description]
 * @return [type]          [description]
 */
function _title($_string,$_strlen) {
	if (mb_strlen($_string,'utf-8') > $_strlen) {
		$_string = mb_substr($_string,0,$_strlen,'utf-8').'...';
	}
	return $_string;
}


/**
 * _html() 函数表示对字符串进行HTML过滤显示，如果是数组按数组的方式过滤，
 * 如果是单独的字符串，那么就按单独的字符串过滤
 * @param unknown_type $_string
 */
function _html($_string) {
	if (is_array($_string)) {
		foreach ($_string as $_key => $_value) {
			$_string[$_key] = _html($_value);
		}
	} else {
		$_string = htmlspecialchars($_string);
	}
	return $_string;
}
/**
 * _paging函数调用分页, 1或2, 1表示数字分页, 2表示文本分页
 * @param  
 * @return 返回分页
 */
function _page($_sql, $_size) {
	$sqli = new DbMysqli();
	global $_page, $_pagesize, $_pagenum, $_pageabsolute, $_num;
	if (isset($_GET['page'])) {
		$_page = $_GET['page'];
		if (empty($_page) || $_page <= 0 || !is_numeric($_page)) {
			$_page = 1;
		} else {
			$_page = intval($_page);
		}
	} else {
		$_page = 1;
	}
	$_pagesize = $_size;
	// id人数
	$_num = mysqli_num_rows($sqli->_query($_sql));
	// 页码
	if ($_num == 0) {
		$_pageabsolute = 1;
	} else {
		$_pageabsolute = ceil($_num / $_pagesize);
	}
	if ($_page > $_pageabsolute) {
		$_page = $_pageabsolute; 
	}
	$_pagenum = ($_page-1)*$_pagesize;
}

/**
 * _paging函数调用分页, 1或2, 1表示数字分页, 2表示文本分页
 * @param  $_type
 * @return 返回分页
 */
function _paging($_type) {
	global $_page,$_pageabsolute,$_num,$_id;
	if ($_type == 1) {
		echo '<div id="page_num">';
		echo '<ul>';
			for ($i=0;$i<$_pageabsolute;$i++) {
				if ($_page == ($i+1)) {
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
				} else {
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
				}				
			}
		echo '</ul>';
	echo '</div>';
	} elseif ($_type == 2) {
		echo '<div id="page_text">';
		echo '<ul>';
			echo '<li>'.$_page.'/'.$_pageabsolute.'页 </li>';
			echo '<li> 共有<strong>'.$_num.'</strong>条数据 |</li>';
		if ($_page == 1) {
			echo '<li>首页 |</li>';
			echo '<li> 上一页 |</li>';
		} else {
			echo '<li><a href="'.SCRIPT.'.php">首页</a> |</li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a> |</li>';
		}
		if ($_page == $_pageabsolute) {
			echo '<li> 下一页 |</li>';
			echo '<li> 尾页 |</li>';
		} else {
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a> |</li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.$_pageabsolute.'">尾页</a> |</li>';
		}
		echo '</ul>';
	echo '</div>';
	} else {
		_paging(2);
	}
}

 header("Content-Type:text/html;charset=utf-8");

/** 
 * _code() 验证码函数
 * @access public 
 * @return void 函数执行后产生一组验证码
 */
function _code() {
	$w = 75; //设置图片宽和高
	$h = 25;
	$str = Array(); //用来存储随机码
	$string = "abcdefghijklmnopqrstuvwxyz0123456789";//随机挑选其中4个字符，也可以选择更多，注意循环的时候加上，宽度适当调整
	for($i = 0;$i < 4;$i++){
	$str[$i] = $string[rand(0,35)];
	$_nmsg .= $str[$i];
	}
	session_start(); //启用超全局变量session
	$_SESSION['code'] = $_nmsg;
	$im = imagecreatetruecolor($w,$h);
	$white = imagecolorallocate($im,255,255,255); //第一次调用设置背景色
	$black = imagecolorallocate($im,0,0,0); //边框颜色
	imagefilledrectangle($im,0,0,$w,$h,$white); //画一矩形填充
	imagerectangle($im,0,0,$w-1,$h-1,$black); //画一矩形框
	//生成雪花背景
	for($i = 1;$i < 200;$i++){
	$x = mt_rand(1,$w-9);
	$y = mt_rand(1,$h-9);
	$color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
	imagechar($im,1,$x,$y,"*",$color);
	}
	//将验证码写入图案
	for($i = 0;$i < count($str);$i++){
	$x = 13 + $i * ($w - 15)/4;
	$y = mt_rand(3,$h / 3);
	$color = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
	imagechar($im,5,$x,$y,$str[$i],$color);
	}
	ob_clean();//原来的程序没有这一栏
	header("Content-type:image/jpeg"); //以jpeg格式输出，注意上面不能输出任何字符，否则出错
	imagejpeg($im);
	imagedestroy($im);

}



 ?>