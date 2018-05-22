<?php
namespace Admin\Controller;
use Think\Controller;
class CateController extends CommonController {
    public function lst(){
        $cate = D('cate');
        $cateres = $cate->order('sort asc')->select();
        $this->assign('cateres',$cateres);
        $this->display();
    }

    public function add(){
        $cate = D('cate');
        if (IS_POST) {
            $date['catename'] = I('catename');
            if ($cate->create($date)) {
                if ($cate->add($date)) {
                    $this->success('添加栏目成功! ' , U('lst'));
                } else {
                    $this->error('添加栏目失败! ');
                }
            } else {
                $this->error($cate->getError());
            }
            return;
        }
        $this->display();
    }
    // 获取不了id
    public function edit(){
        $cate = D('cate');     
        if (IS_POST) {
            $date['id'] = I('get.id');
            $date['catename'] = I('catename');
            if ($cate->create($date)) {
                if ($cate->save()) {
                    $this->success('修改栏目成功! ' , U('lst'));
                } else {
                    $this->error('修改栏目失败! ');
                }
            } else {
                $this->error($cate->getError());
            }
            $cater = $cate->find(I('get.id'));
            $this->assign('cater',$cater);
            return;
        }
        $this->display();
    }

    public function del(){
        $cate = D('cate');
        if ($cate->delete(I('id'))) {
            $this->success('删除栏目成功!',U('lst'));
        } else {
            $this->error('删除栏目失败! ');
        }
    }

    public function sort(){
        $cate = D('cate');
        foreach ($_POST as $id => $sort) {
            $cate->where(array('id'=>$id))->setField('sort',$sort);
        }
        $this->success('排序成功!',U('lst'));
    }
}