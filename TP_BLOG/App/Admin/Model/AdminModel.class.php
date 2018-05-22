<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    protected $_validate = array(
    array('username','require','添加管理员不能为空!',1,'regex',3), // 在新增的时候验证name字段是否唯一
    array('username','','管理员不能重复!',1,'unique',1),
    array('password','require','密码不能为空!',1,'regex',3),
    array('username','require','管理员不能为空!',1,'regex',4),
    array('password','require','密码不能为空!',1,'regex',4),
    array('varify','check_varify','验证码错误!',1,'callback',4),
    );

    public function login() {
        $password = $this->password;
        $info = $this->where(array('username' => $this->username))->find();
        if ($info) {
            if ($info['password'] == md5($password)) {
                session('id',$info['id']);
                session('username',$info['username']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }   
    } 

    function check_varify($code,$id="") {
        $Verify = new \Think\Verify();
        return $Verify->check($code,$id);
    }
}