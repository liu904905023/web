<?php

namespace Home\Controller;

//use Think\Controller;

use Common\Compose\Base;



class PermissionController extends Base {





    public function permission_assignment(){

    	R("Base/getMenu");

        $this->display();

    }



	public function query_staff(){

		$data = array(

				"CustomerServiceSysNo" => session(SysNO),

                "LoginName"            => I( 'username', '', 'htmlspecialchars' ),

                //"PhoneNumber"          => I( 'phone', '', 'htmlspecialchars' ),

		);

		

		$data['PagingInfo']['PageSize'] = I('PageSize');

		$data['PagingInfo']['PageNumber'] = I('PageNumber');

		$data = json_encode($data);

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            //"X-Ywkj-Authentication:" . strlen( $data ),

        );



		if(session( 'flag' )==0){

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3SystemUserListCSsysno",$data, $head );

		$list = json_decode($list);

        $this->ajaxReturn( $list ,json);

		}

		else{

		

		}

        

	

	}

	public function staffrolelist(){

		$data['PagingInfo']['PageSize'] = 200;

		$data['PagingInfo']['PageNumber'] = 0;

		$data = json_encode($data);

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            //"X-Ywkj-Authentication:" . strlen( $data ),

        );

        $list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3SystemRoleList",$data, $head );

		$list = json_decode($list);

        $this->ajaxReturn( $list ,json);

	}



	public function userrolelist(){

		$data['SystemUserSysNo'] = I('SystemUserSysNo');

		//$data['PagingInfo']['PageSize'] = 200;

		//$data['PagingInfo']['PageNumber'] = 0;

		$data = json_encode($data);

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            //"X-Ywkj-Authentication:" . strlen( $data ),

        );

        $list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3UserRoleList",$data, $head );

		$list = json_decode($list);

        $this->ajaxReturn( $list ,json);

	}

	

	public function roleinsert(){

		$SystemRoleSysNo = I('SystemRoleSysNo');

		$SystemUserSysNo = I('SystemUserSysNo');

		foreach($SystemRoleSysNo as $row){

			$data[] = array(

				"SystemRoleSysNo"=>$row,

				"SystemUserSysNo"=>$SystemUserSysNo,

				"InUser"=> session( data )['SysNo'],

				"EditUser"=> session( data )['SysNo']

			); 



		}

		

		$data1 = json_encode($data);

		// var_dump($data1);

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data1 ),

            //"X-Ywkj-Authentication:" . strlen( $data ),

        );

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3UserRoleInsert",$data1, $head );

		$list = json_decode($list,true);

		$this->ajaxReturn( $list ,json);

		}

	

	public function roledelete(){

	

		$SysNo = I('SystemUserSysNo');

		$data['SystemUserSysNo'] = $SysNo;

		$data = json_encode($data);

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            //"X-Ywkj-Authentication:" . strlen( $data ),

        );

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3UserRoleDelete",$data, $head );

		$list = json_decode($list,true);

		$this->ajaxReturn( $list ,json);

	}
//start add by qiwei 20161129
	public function permission_business(){
	
		R("Base/getMenu");

        $this->display();

	}
	
	//商户权限列表
	public function business_permission_list(){
		$data['CustomerServiceSysNo'] = I('CustomerServiceSysNo');
		$data = json_encode($data);
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

        $list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3CustomerRoleList",$data, $head );
		$list = json_decode($list);
        $this->ajaxReturn( $list ,json);
	}
	//商户权限新增
	public function business_permission_insert(){
		$SystemRoleSysNo 		= I('SystemRoleSysNo');
		$CustomerServiceSysNo 	= I('CustomerServiceSysNo');
		foreach($SystemRoleSysNo as $row){
			$data[] = array(
				"SystemRoleSysNo"		=> $row,
				"CustomerServiceSysNo"	=> $CustomerServiceSysNo,
				"InUser"				=> session( data )['SysNo'],
				"EditUser"				=> session( data )['SysNo']
			); 
		}

		$data1 = json_encode($data);
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data1 ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3CustomerRoleInsert",$data1, $head );
		$list = json_decode($list,true);
		$this->ajaxReturn( $list ,json);
	}
	//商户权限删除
	public function business_permission_delete(){
		$SysNo = I('CustomerServiceSysNo');
		$data['CustomerServiceSysNo'] = $SysNo;
		$data = json_encode($data);
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3CustomerRoleDelete",$data, $head );
		$list = json_decode($list,true);
		$this->ajaxReturn( $list ,json);
	}
//end add by qiwei 20161129
}