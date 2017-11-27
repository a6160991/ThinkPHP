<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
//读取XML文件
$_html = _html(_get_xml('new.xml'));
//读取帖子列表
global $_pagesize,$_pagenum,$_system;
_page("SELECT bg_id FROM bg_article WHERE bg_reid=0",$_system['article']); 
$_result = $sqli->_query("SELECT 
						bg_id,
						bg_del_state,
						bg_title,
						bg_type,
						bg_reid,
						bg_readcount,
						bg_commendcount 
						FROM 
						bg_article 
						WHERE 
						bg_reid=0 
						ORDER BY 
						bg_date DESC 
						LIMIT 
						$_pagenum,$_pagesize
							");	
$hot_res = $sqli->_query("SELECT 
						DISTINCT 
						bg_id,
						bg_del_state,
						bg_title,
						bg_type,
						bg_readcount 
						FROM 
						`bg_article` 
						WHERE 
						bg_title 
						NOT LIKE 
						'RE:%' 
						ORDER BY 
						`bg_article`.`bg_readcount` 
						DESC
						LIMIT 
						5
							");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--首页</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>

<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="list">
	<h2>帖子列表</h2>
	<a href="post.php" class="post">发表帖子</a>
	<ul class="article">
		<?php
			$_htmllist = array();
			while (!!$_rows = $sqli->_fetch_array_list($_result)) {
				$_htmllist['id'] = $_rows['bg_id'];
				$_htmllist['state'] =$_rows['bg_del_state'];
				$_htmllist['type'] = $_rows['bg_type'];
				$_htmllist['readcount'] = $_rows['bg_readcount'];
				$_htmllist['commendcount'] = $_rows['bg_commendcount'];
				$_htmllist['title'] = $_rows['bg_title'];
				$_htmllist = _html($_htmllist);
				if (empty($_htmllist['state'])) {
					echo '<li class="icon'.$_htmllist['type'].'"><em>阅读数(<strong>'.$_htmllist['readcount'].'</strong>) 评论数(<strong>'.$_htmllist['commendcount'].'</strong>)</em> <a href="article.php?id='.$_htmllist['id'].'">'._title($_htmllist['title'],20).'</a></li>';
				}
				
			}
			$sqli->_free_result($_result);
		?>
	</ul>
	<?php _paging(2);?>
</div>

<div id="user">
	<h2>新进会员</h2>
	<dl>
		<dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
		<dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username']?>" /></dt>
		<dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
		<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['id']?>">加为好友</a></dd>
		<dd class="guest">写留言</dd>
		<dd class="flower"><a href="javascript:;" name="gift" title="<?php echo $_html['id']?>">给他送花</a></dd>
		<dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email']?></a></dd>
		<dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_blank"><?php echo $_html['url']?></a></dd>
	</dl>
</div>

<div id="hot_article">
	<h2>热门帖子</h2>
	<ul class="article">
		<?php
		$_html_list = array();
		while (!!$_rows = $sqli->_fetch_array_list($hot_res)) {
			$_html_list['id'] = $_rows['bg_id'];
			$_html_list['state'] = $_rows['bg_del_state'];
			$_html_list['type'] = $_rows['bg_type'];
			$_html_list['readcount'] = $_rows['bg_readcount'];
			$_html_list['title'] = $_rows['bg_title'];
			$_html_list = _html($_html_list);
			if (empty($_html_list['state'])) {
				echo '<li class="icon'.$_html_list['type'].'"><a href="article.php?id='.$_html_list['id'].'">'._title($_html_list['title'],10).'</a></li>';
			}
		}
			
		?>
	</ul>
</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>