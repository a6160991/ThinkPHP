<?php
namespace Admin\Model;
use Think\Model;
class CateModel extends Model {
    protected $_validate = array(
    array('catename','require','添加栏目不能为空',1,'regex',3), // 在新增的时候验证name字段是否唯一
    array('catename','','栏目添加不能重复',1,'unique',3),
    );
}