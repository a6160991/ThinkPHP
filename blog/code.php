<?php 
session_start();
define('IN_TG',true);

//引用公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//运行验证码函数
_code();

 ?>