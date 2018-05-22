<?php
namespace Admin\Model;
use Think\Model;
class LinkModel extends Model {
    protected $_validate = array(
    array('title','require','链接名称不能为空',1,'regex',3),
    array('url','require','链接地址不能为空',1,'regex',3), // 在新增的时候验证name字段是否唯一
    array('title','','连接名称不能重复',1,'unique',3),
    );
}