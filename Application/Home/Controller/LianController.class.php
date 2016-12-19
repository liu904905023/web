<?php

namespace Home\Controller;

use Think\Controller;

class LianController extends Controller{
	
	public function index(){
		
		$this->display();
	
	}
	public function GetAddress(){
		$type=I('type');
		if($type==1){
		}
		else{
			$data['Parent_id']=I('parent_id');
		}
		
	
		$data = json_encode( $data );
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3GetAddress"; 
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
		
		$list = http_request($url,$data,$head);
		$list = json_decode($list,true);
		foreach ($list as $row=>$val){
			$info[$row]['region_id'] = $val['SysNo'];
			$info[$row]['region_name']   = $val['AddressName'];
		}

		$this->ajaxreturn($info);
	
	
	}


}
