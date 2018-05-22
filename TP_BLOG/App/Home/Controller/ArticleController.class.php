<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    public function index(){
        $arts = D('article')->find(I('id'));
        $this->assign('arts',$arts);
        $this->catename($arts['cateid']);
        $this->display();
    }

    public function catename($cateid) {
        $cates = D('cate')->find($cateid);
        $this->assign('catename',$cates['catename']);
    }
}