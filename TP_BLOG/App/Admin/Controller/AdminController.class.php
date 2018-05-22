<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends CommonController {
    public function lst() {
        $admin = D('admin');
        $count = $admin->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $list = $admin->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function add() {
        $admin = D('admin');
        if (IS_POST) {
            $date['username'] = I('username');
            $date['password'] = md5(I('password'));
            if ($admin->create($date)) {
                if ($admin->add($date)) {
                    $this->success('添加管理员成功! ' , U('lst'));
                } else {
                    $this->error('添加管理员失败! ');
                }
            } else {
                $this->error($admin->getError());
            }
            return;
        }
        $this->display();
    }
    // 获取不了id
    public function edit() {
        $admin = D('admin');     
        if (IS_POST) {
            $date['id'] = I('get.id');
            $date['username'] = I('username');
            $admins = $admin->find($date['id']);
            $password = $admins['password'];
            if (I('password')) {
                $date['password'] = md5(I('password'));
            } else {
                $date['password'] = $password;
            }
            if ($admin->create($date)) {
                if ($admin->save()) {
                    $this->success('修改管理员成功! ' , U('lst'));
                } else { 
                    $this->error('修改管理员失败! ');
                }
            } else {
                $this->error($admin->getError());
            } 
            return;
        }
        $adminr = $admin->find(I('get.id'));
        $this->assign('adminr',$adminr);
        $this->display();
    }

    public function del( ){
        $admin = D('admin');
        $id = I('id');
        if ($id == 1) {
            $this->error('本管理员不能删除!');
        } else {
            if ($admin->delete(I('id'))) {
                $this->success('删除管理员成功!',U('lst'));
            } else {
                $this->error('删除管理员失败! ');
            }
        }
        
    }

    public function sort() {
        $admin = D('admin');
        foreach ($_POST as $id => $sort) {
            $admin->where(array('id'=>$id))->setField('sort',$sort);
        }
        $this->success('排序成功!',U('lst'));
    }

    public function logout() {
        sesstion(null); // 删除name
        $this->success('退出成功...',U('Login/index'));
    }
}