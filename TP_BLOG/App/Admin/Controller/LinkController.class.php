<?php
namespace Admin\Controller;
use Think\Controller;
class LinkController extends CommonController {
    public function lst(){
        $link = D('link');
        $count = $link->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $list = $link->order('sort asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function add(){
        $link = D('link');
        if (IS_POST) {
            $date['title'] = I('title');
            $date['url'] = I('url');
            $date['desc'] = I('desc');
            if ($link->create($date)) {
                if ($link->add()) {
                    $this->success('添加链接成功! ' , U('lst'));
                } else {
                    $this->error('添加链接失败! ');
                }
            } else {
                $this->error($link->getError());
            }
            return;
        }
        $this->display();
    }

    public function edit(){
        $link = D('link');
        if (IS_POST) {
            $date['id'] = I('id');
            $date['title'] = I('title');
            $date['url'] = I('url');
            $date['desc'] = I('desc');
            if ($link->create($date)) {
                if ($link->save()) {
                    $this->success('修改链接成功! ' , U('lst'));
                } else {
                    $this->error('修改链接失败! ');
                }
            } else {
                $this->error($link->getError());
            }
            $linkr = $link->find(I('get.id'));
            $this->assign('linkr',$linkr);  
            return;
        }
        $this->display();
    }

    public function del(){
        $link = D('link');
        if ($link->delete(I('id'))) {
            $this->success('删除链接成功!',U('lst'));
        } else {
            $this->error('删除链接失败! ');
        }
    }

    public function sort(){
        $link = D('link');
        foreach ($_POST as $id => $sort) {
            $link->where(array('id'=>$id))->setField('sort',$sort);
        }
        $this->success('排序成功!',U('lst'));
    }
}