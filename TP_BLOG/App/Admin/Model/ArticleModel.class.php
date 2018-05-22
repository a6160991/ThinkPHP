<?php
namespace Admin\Model;
use Think\Model;
class ArticleModel extends Model {
    protected $_validate = array(
    array('title','require','文章标题不能为空',1,'regex',3),
    array('cateid','require','栏目不能为空',1,'regex',3), // 在新增的时候验证name字段是否唯一
    array('content','require','文章不能为空',1,'regex',3),
    );
}