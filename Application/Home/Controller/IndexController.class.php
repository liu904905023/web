<?php

namespace Home\Controller;

//use Think\Controller;
use Common\Compose\Base;

class IndexController extends Base{

    public function index(){
		R("Base/getMenu");
        $this->display();
    }

}
