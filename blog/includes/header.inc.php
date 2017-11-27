<?php 
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
 ?>	
<div id="header">
	<h1><a href="index.php">留言系统</a>&ensp;</h1>
		<ul>
			<li><a href="index.php">首页</a>&ensp;</li>
			<?php 
				if (isset($_COOKIE['username'])){
				echo '<li><a href="member.php">'.$_COOKIE['username'].'·个人中心</a> '.$GLOBALS['message'].'</li>';
				echo "\n";
				} else {
					echo '<li><a href="register.php">注册</a>&ensp;</li>';
					echo "\n";
					echo "\t\t\t";
					echo '<li><a href="login.php">登陆</a>&ensp;</li>';
					echo "\n";
				}
			 ?>
			<li><a href="blog.php">广场</a>&ensp;</li>
			<?php 
				if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) {
					echo '<li><a href="manage_set.php" class="manage">管理 </a>&ensp;</li>';
				}

				if (isset($_COOKIE['username'])) {
					echo '<li><a href="logout.php">退出</a></li>';
				}
			 ?>	
			
		</ul>
</div>