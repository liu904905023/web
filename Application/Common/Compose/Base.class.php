<?php

namespace Common\Compose;

use Think\Controller;

class Base extends Controller{

    public function __construct(){
        parent::__construct();
		$status = session('status');
		$data = session('data');
        if( !isset($status)){
          // header( 'Location:http://web.yunlaohu.cn/index.php/Login/login/' );
			$this->redirect("Login/login"); 
        }

        





    }

    public function userAuth( $SysNO ){
        $url = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3UserRoleList";

        $arr  = array(
            'SystemUserSysNo' => I( 'SystemUserSysNo' ),
        );
        $data = json_encode( $arr );

        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
        );
        $list = http_request( $url, $data, $head );
        return json_decode( $list, TRUE );
    }



}
