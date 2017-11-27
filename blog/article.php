<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','article');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

//处理精华帖
if (@$_GET['action'] == 'nice' && isset($_GET['id']) && isset($_GET['on'])) {
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                        bg_active,
                                        bg_article_time 
                                        FROM 
                                        bg_user  
                                        WHERE 
                                        bg_username='{$_COOKIE['username']}' 
                                        LIMIT 
                                        1"
    )) {
        // 设置精华帖, 或取消
        $sqli->_query("UPDATE bg_article SET bg_nice='{$_GET['on']}' WHERE bg_id='{$_GET['id']}'");
        if ($sqli->_affected_rows() == 1) {
            $sqli->closeDb();
            _location('设置操作成功！','article.php?id='.$_GET['id']);
        } else {
            $sqli->closeDb();
            _alert_back('设置失败！');
        }
    } else {
        _alert_back('非法登录');
    }
}
//处理回帖
if (isset($_GET['action']) == 'rearticle') {
    global $_system;
    if (!empty($_system['code'])) {
        _check_code($_POST['code'],$_SESSION['code']); //验证码判断
    }
    
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                        bg_active,
                                        bg_article_time 
                                        FROM 
                                        bg_user  
                                        WHERE 
                                        bg_username='{$_COOKIE['username']}' 
                                        LIMIT 
                                        1"
            )) {
        _timed(time(),$_rows['bg_article_time'],$_system['re']);
        //接受数据
        $_clean = array();
        $_clean['reid'] = $_POST['reid'];
        $_clean['type'] = $_POST['type'];
        $_clean['title'] = $_POST['title'];
        $_clean['content'] = $_POST['content'];
        $_clean['username'] = $_COOKIE['username'];
        $_clean = _mysql_string($_clean);
        //写入数据库
        $sqli->_query("INSERT INTO bg_article (
                                    bg_reid,
                                    bg_username,
                                    bg_title,
                                    bg_type,
                                    bg_content,
                                    bg_date
                                    )
                                    VALUES (
                                    '{$_clean['reid']}',
                                    '{$_clean['username']}',
                                    '{$_clean['title']}',
                                    '{$_clean['type']}',
                                    '{$_clean['content']}',
                                    NOW()
                                    )"
        );
        if ($sqli->_affected_rows() == 1) {
            $_clean['time'] = time();
            //setcookie('article_time',time());
            $sqli->_query("UPDATE bg_article SET bg_commendcount=bg_commendcount+1 WHERE bg_reid=0 AND bg_id='{$_clean['reid']}'");
            $sqli->_query("UPDATE 
                bg_user 
                SET 
                bg_article_time='{$_clean['time']}' 
                WHERE 
                bg_username='{$_COOKIE['username']}'
                ");
            $sqli->closeDb();
            //_session_destroy();
            _location('回帖成功！','article.php?id='.$_clean['reid']);
        } else {
            $sqli->closeDb();
            //_session_destroy();
            _alert_back('回帖失败！');
        }
    } else {
        _alert_back('非法登录！');
    }
}

//读出数据
if (isset($_GET['id'])) {
    if (!!$_rows = $sqli->_fetch_array("SELECT 
                                        bg_id,
                                        bg_del_state,
                                        bg_username,
                                        bg_title,
                                        bg_type,
                                        bg_content,
                                        bg_readcount,
                                        bg_commendcount,
                                        bg_last_modify_date,
                                        bg_nice,
                                        bg_date 
                                        FROM 
                                        bg_article 
                                        WHERE
                                        bg_reid=0
                                        AND
                                        bg_id='{$_GET['id']}'")) {
    
        //累积阅读量
        $sqli->_query("UPDATE bg_article SET bg_readcount=bg_readcount+1 WHERE bg_id='{$_GET['id']}'");
        
        $_html = array();
        $_html['reid'] = $_rows['bg_id'];
        $_html['article_del'] = $_rows['bg_del_state'];
        $_html['username_subject'] = $_rows['bg_username'];
        $_html['title'] = $_rows['bg_title'];
        $_html['type'] = $_rows['bg_type'];
        $_html['content'] = $_rows['bg_content'];
        $_html['readcount'] = $_rows['bg_readcount'];
        $_html['commendcount'] = $_rows['bg_commendcount'];
        $_html['nice'] = $_rows['bg_nice'];
        $_html['last_modify_date'] = $_rows['bg_last_modify_date'];
        $_html['date'] = $_rows['bg_date'];
        
        //拿出用户名，去查找用户信息
        if (!!$_rows = $sqli->_fetch_array("SELECT 
                                            bg_id,
                                            bg_state,
                                            bg_sex,
                                            bg_face,
                                            bg_email,
                                            bg_url,
                                            bg_switch,
                                            bg_autograph,
                                            bg_level 
                                            FROM 
                                            bg_user 
                                            WHERE 
                                            bg_username='{$_html['username_subject']}'")) {
            //提取用户信息
            $_html['userid'] = $_rows['bg_id'];
            $_html['state'] = $_rows['bg_state'];
            $_html['sex'] = $_rows['bg_sex'];
            $_html['face'] = $_rows['bg_face'];
            $_html['email'] = $_rows['bg_email'];
            $_html['url'] = $_rows['bg_url'];
            $_html['switch'] = $_rows['bg_switch'];
            $_html['autograph'] = $_rows['bg_autograph'];
            $_html['level'] = $_rows['bg_level'];
            $_html = _html($_html);
            
            //创建一个全局变量，做个带参的分页
            global $_id;
            $_id = 'id='.$_html['reid'].'&';

            //修改主题帖子
            if (isset($_COOKIE['username'])) {
                if ($_html['username_subject'] == $_COOKIE['username'] || isset($_SESSION['admin'])) {
                    $_html['subject_modify'] = '[<a href="article_modify.php?id='.$_html['reid'].'">修改</a>]';
                }
            }
            //删除帖子
            if (isset($_COOKIE['username'])) {
                if ($_html['username_subject'] == $_COOKIE['username'] || isset($_SESSION['admin'])) {
                    $_html['subject_del'] = '[<a href="article_del.php?id='.$_html['reid'].'">删除</a>]';
                }
            }
            // 主题已被删除
            if ($_html['article_del'] == 1) {
                _location('不存在这个主题！','index.php');
            }
            //读取最后修改信息
            if ($_html['last_modify_date'] != '0000-00-00 00:00:00') {
                $_html['last_modify_date_string'] = '本贴已由['.$_html['username_subject'].']于'.$_html['last_modify_date'].'修改过！';
            }
            //给楼主回复
            if (isset($_COOKIE['username']) && $_html['state'] == 0) {      
                if ($_COOKIE['username']) {
                    $_html['re'] = '<span>[<a herf="#ree" name="re" title="回复1楼的'.$_html['username_subject'].'">回复</a>]</span>';
                }
            }
            //个性签名
            if (isset($_html['switch']) == 1) {
                $_html['autograph_html'] = '<p class="autograph">'._ubb($_html['autograph']).'</p>';
            }

            //读取回帖
            global $_pagesize,$_pagenum,$_page;
            _page("SELECT bg_id FROM bg_article WHERE bg_reid='{$_html['reid']}'",5); 
            $_result = $sqli->_query("SELECT 
                                    bg_username,bg_type,bg_title,bg_content,bg_date 
                                    FROM 
                                    bg_article 
                                    WHERE
                                    bg_reid='{$_html['reid']}'
                                    ORDER BY 
                                    bg_date 
                                    ASC 
                                    LIMIT 
                                    $_pagenum,$_pagesize
            "); 
                                                                            
        } else {
            //这个用户已被删除
            
        }
    } else {
        _alert_back('不存在这个主题！');
    }
} else {
    _alert_back('非法操作！');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--帖子详情</title>
<?php 
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>
<body>
<?php 
    require ROOT_PATH.'includes/header.inc.php';
?>

<div id="article">
    <h2>帖子详情</h2>
    <?php 
    if ($_page == 1) {
        if (!empty($_html['nice'])) {
     ?>
    <img src="images/nice.gif" alt="精华帖" class="nice">
    <?php 
        }
        // 浏览量达到400,却评论达到20即可为热门贴
        if ($_html['readcount'] >= 400 && $_html['commendcount'] >= 20) {
     ?>
    <img src="images/hot.gif" alt="热门帖" class="hot">
    
    <?php 
    }
        }
        if ($_page == 1) {
    ?>
    
    <div id="subject">
        <dl>
            <dd class="user"><?php echo $_html['username_subject']?>(<?php echo $_html['sex'] ?>)</dd>
            <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username_subject'] ?>" /></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid'] ?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid'] ?>">加为好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="gift"><a href="javascript:;" name="gift" title="<?php echo $_html['userid']?>">给他送花</a></dd>
            <dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email'] ?></a></dd>
            <dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_blank"><?php echo $_html['url'] ?></a></dd>
        </dl>
        <div class="content">
            <div class="user">
                <span>
                <?php 
                if (isset($_SESSION['admin'])) {
                    if (empty($_html['nice'])) { 
                ?>
                    [<a href="article.php?action=nice&on=1&id=<?php echo $_html['reid']?>">设置精华</a>]
                    <?php } else {?>
                    [<a href="article.php?action=nice&on=0&id=<?php echo $_html['reid']?>">取消精华</a>]
                <?php 
                    }
                } 
                ?>
                <?php 
                    if (isset($_html['subject_del'])) {
                        echo $_html['subject_del'];
                    }
                 ?>
                <?php 
                    if (isset($_html['subject_modify'])){
                        echo $_html['subject_modify'];
                    }
                ?> 1#
                </span>
                <?php echo $_html['username_subject'] ?> | 发表于：<?php echo $_html['date'] ?>
            </div>
            <h3>主题：<?php echo $_html['title']?> <img src="images/icon<?php echo $_html['type']?>.gif" alt="icon" /> <?php if (isset($_COOKIE['username'])){echo $_html['re'];} ?></h3>
            <div class="detail">
                <?php echo _ubb($_html['content'])?>
                <?php 
                    if ($_html['switch'] == 1) {
                        echo '<p class="autograph">'._ubb($_html['autograph']).'</p>';
                    }  
                ?>
            </div>
            <div class="read">
                <p><?php if (isset($_html['last_modify_date_string'])) {
                    echo $_html['last_modify_date_string'];
                } ?></p>
                阅读量：(<?php echo $_html['readcount'] ?>) 评论量：(<?php echo $_html['commendcount'] ?>)
            </div>
        </div>
    </div>
    <?php } ?>
    
    
    <p class="line"></p>
    
    <?php 
        $_i = 2;
        while (!!$_rows = $sqli->_fetch_array_list($_result)) {
            $_html['username'] = $_rows['bg_username'];
            $_html['type'] = $_rows['bg_type'];
            $_html['retitle'] = $_rows['bg_title'];
            $_html['content'] = $_rows['bg_content'];
            $_html['date'] = $_rows['bg_date'];
            $_html = _html($_html);
            
            if (!!$_rows = $sqli->_fetch_array("SELECT 
                                        bg_id,
                                        bg_state,
                                        bg_sex,
                                        bg_face,
                                        bg_email,
                                        bg_url,
                                        bg_switch,
                                        bg_autograph 
                                        FROM 
                                        bg_user 
                                        WHERE 
                                        bg_username='{$_html['username']}'")) {
                //提取用户信息
                $_html['userid'] = $_rows['bg_id'];
                $_html['username_del'] = $_rows['bg_state'];
                $_html['sex'] = $_rows['bg_sex'];
                $_html['face'] = $_rows['bg_face'];
                $_html['email'] = $_rows['bg_email'];
                $_html['url'] = $_rows['bg_url'];
                $_html['switch'] = $_rows['bg_switch'];
                $_html['autograph'] = $_rows['bg_autograph'];
                $_html = _html($_html);

                //用户已封
                if ($_html['username_del'] == 1) {
                    $_html['username'] = '此用户已被删除';
                    $_html['sex'] = '无';
                    $_html['userid'] = null;
                    $_html['email'] = null;
                    $_html['url'] = null;
                    $_html['date'] = '0000-00-00 00:00:00';
                    $_html['content'] = '信息已被删除';

                }

                //个性签名
                if ($_html['switch'] == 1) {
                    $_hmtl['autograph_html'] = '<p class="autograph">'.$_html['autograph'].'</p>';
                }
            }

            // 跟帖回复
                if (isset($_COOKIE['username'])) {
                    if ($_html['username_del'] == 1) {
                        $_html['re'] = ' ';
                    } else {
                        $_html['re'] = '<span>[<a href="#ree" name="re" title="回复' .($_i+(($_page-1)*$_pagesize)).'楼的'.($_html['username']).'">回复</a>]</span>';
                    }   
                }
    ?>
    <div class="re">
        <dl>
            <dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
            <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username']?>" /></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid'] ?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid'] ?>">加为好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="gift"><a href="javascript:;" name="gift" title="<?php echo $_html['userid'] ?>">给他送花</a></dd>
            <dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email'] ?></a></dd>
            <dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_blank"><?php echo $_html['url'] ?></a></dd>
        </dl>

        <div class="content">
            <div class="user">
                <span><?php echo $_i+(($_page-1)*$_pagesize); ?>#</span><?php echo $_html['username'] ?> | 发表于：<?php echo $_html['date']?>
            </div>
            <h3>主题：<?php echo $_html['retitle'] ?> <img src="images/icon<?php echo $_html['type']?>.gif" alt="icon" /> <?php if (isset($_COOKIE['username'])){echo $_html['re'];} ?></h3>
            <div class="detail">
                <?php echo _ubb($_html['content'])?>
                <?php 
                    if ($_html['switch'] == 1) {
                        echo '<p class="autograph">'._ubb($_html['autograph']).'</p>';
                    } 
                ?>
            </div>
        </div>
    </div>


    <p class="line"></p>
    <?php 
        $_i++;
     }
        $sqli->_free_result($_result);
        _paging(1);
    ?>

    <?php if (isset($_COOKIE['username'])) {?>
    <a name="ree" "></a>
    <form method="post" action="?action=rearticle">
        <input type="hidden" name="reid" value="<?php echo $_html['reid']?>" />
        <input type="hidden" name="type" value="<?php echo $_html['type']?>" />
        <dl>
            <dd>标　　题：<input type="text" name="title" class="text" value="RE:<?php echo $_html['title']?>" disabled="disabled"  /> (*必填，2-40位)</dd>
            <dd id="q">贴　　图：　<a href="javascript:;">Q图系列[1]</a>　 <a href="javascript:;">Q图系列[2]</a>　 <a href="javascript:;">Q图系列[3]</a></dd>
            <dd>
                <?php include ROOT_PATH.'includes/ubb.inc.php' ?>
                <textarea name="content" rows="9"></textarea>
            </dd>
                <dd>
            <?php if (!empty($_system['code'])) { ?>
            验 证 码：
            <input type="text" name="code" class="text yzm"  /> <img src="code.php" id="code" onclick="javascript:this.src='code.php?tm='+Math.random();" /> 
            <?php } ?>
            <input type="submit" class="submit" value="发表帖子" />
            </dd>       
        </dl>
    </form>
        <?php }?>
</div>

</body>
</html>
