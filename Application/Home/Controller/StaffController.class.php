<?php

namespace Home\Controller;

use Common\Compose\Base;

class StaffController extends Base{

    public function index(){
        
    }

    public function staff_register(){
    	R("Base/getMenu");
        if( IS_POST ){
            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserInsert";
            $arr  = array(
                "CustomerServiceSysNo" => session( 'data' )['SysNo'],
                "loginname"            => I( 'sx_dlm' ),
                "displayname"          => I( 'sx_name' ),
                "departmentname"       => '',
                "phonenumber"          => I( 'mobile' ),
                "email"                => I( 'email' ),
                "password"             => I( 'sx_pass' ),
                "Alipay_store_id"             => I( 'store_id' ),
                "inuser"               => session( 'data' )['SysNo'],
                "edituser"             => session( 'data' )['SysNo'],
				"DwellAddress"     => I( "address" ),
	            "DwellAddressID"     => I( "AddressNum" )
            );
            $data = json_encode( $arr );
//			var_dump($data);exit;
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:".strlen( $data )
            );

            $res = http_request($url,$data,$head);

            $result = json_decode($res,TRUE);
//            dump( $result );
            $this->ajaxReturn( $result, 'json' );
            exit();
        }
        $this->display( 'staff_register' );
    }

    public function staff_list(){
//		var_dump( SESSION(data));
		R("Base/getMenu");
        $this->display();
    }


	public function stafflist(){
			if(session(flag)==1){
			exit;
			}
			
			$url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserListCSsysno";
            $data  = array(
                "CustomerServiceSysNo" => session( 'data' )['SysNo'],
                "CustomersType" => session('data')['CustomersType'],
                "LoginName"            => I( 'username', '', 'htmlspecialchars' ),
                "PhoneNumber"          => I( 'phone', '', 'htmlspecialchars' ),
            );
			$data['PagingInfo']['PageSize'] = I( 'PageSize', '' );
			$data['PagingInfo']['PageNumber'] =I( 'PageNumber', 0 );
            $data = json_encode( $data );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );

            $res = http_request( $url, $data, $head );
            $result = json_decode( $res, TRUE );
//			var_dump($result);exit;
			$ids = I('id');
			if($ids){
			$info = QueryCustomerSysNo($ids);
			$info = json_encode($info);
			$info = json_decode( $info, TRUE );
			$result['info']['topid']=$info['model'][0]['SysNO'];
			$result['info']['topname']=$info['model'][0]['DisplayName'];
			}
            $result['type'] =session(data)['CustomersType'] ;
			$result['AccessFlag'] = $this->checkasstoken();


			$this->ajaxReturn( $result, 'json');
	
	
	
	}

    public function query_staff(){
        $customerServiceSysNo = I( "customerServiceSysNo" );

        $arr = array(
            "customerServiceSysNo" => $customerServiceSysNo
        );

        $data['PagingInfo']['PageSize']   = 2;
        $data['PagingInfo']['PageNumber'] = 1;

        $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserByCSsysNo";
        $data = json_encode( $arr );
        //var_dump($data);
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

        $list = http_request( $url, $data, $head );
        $list = json_decode( $list );
        $this->ajaxReturn( $list, 'json' );
    }

	public function querycustomer(){
	
		$SystemUserSysNo = I("SystemUserSysNo");
		$data = array(
				"SystemUserSysNo" => $SystemUserSysNo
		);
		$data['PagingInfo']['PageSize']   = I("PageSize");
		$data['PagingInfo']['PageNumber'] = I("PageNumber");
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerShopList";
		$data = json_encode($data);
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data ),
			//"X-Ywkj-Authentication:" . strlen( $data ),
		);
		$list = http_request( $url, $data, $head );
        $list = json_decode( $list );
        $this->ajaxReturn( $list, 'json' );
		//$this->assign($list,"list");
		//$this -> display('Business/business');

	
	}


	public function servicequerycustomer(){
	
		//$CustomerSysNo = empty($_POST['CustomerSysNo'])? session(data)['SysNO']:$_POST['CustomerSysNo'];
		//$data['SystemUserSysNo']=I("SystemUserSysNo");
		if(isset($_POST['SystemUserSysNo'])){
			$data['SystemUserSysNo']=I("SystemUserSysNo");
		}
		
		if(session(flag)==0){
		$data['CustomersTopSysNo'] = session(data)['SysNo']; 
		}
		if(session(flag)==1){
		$data['SystemUserSysNo'] = session(data)['SysNO']; 
		}
		$data['Customer']   = I("CustomersName");
		$data['CustomerName']   = I("CustomersTrueName");
		$data['PagingInfo']['PageSize']   = I("PageSize");
		$data['PagingInfo']['PageNumber'] = I("PageNumber");
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerShopList";
		$data = json_encode($data);
		//var_dump($data);exit;
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data ),
			//"X-Ywkj-Authentication:" . strlen( $data ),
		);
		$list = http_request( $url, $data, $head );
        $list = json_decode( $list ,true);
		//var_dump($list);exit;
		/*foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['CustomerName']=$val['CustomerName'];
		$info['model'][$row]['Phone']=$val['Phone'];
		$info['model'][$row]['CellPhone']=$val['CellPhone'];
		$info['model'][$row]['CustomersType']=$val['CustomersType'];
		$info['model'][$row]['CreateTime']=substr($val['CreateTime'],6,13);
		}
		$info['totalCount'] =$list['totalCount'];*/
		if(session(data)['CustomersType']){
		$list['type']=session(data)['CustomersType'];
		}
		if(session(flag)==1){
		$list['flag']= staffstoreorservice(session(SysNO));
		}
        $this->ajaxReturn( $list, 'json' );
	
	}

	public function staffquerystore($id= 16){
		//$data['SystemUserSysNo'] = session(data)['SysNO']; 
		$data['SystemUserSysNo'] = $id; 
		$data['PagingInfo']['PageSize']   = 1;
		$data['PagingInfo']['PageNumber'] = 0;
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3GetCustomerServiceSysNo";
		$data = json_encode($data);
		//var_dump($data);
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data ),
			//"X-Ywkj-Authentication:" . strlen( $data ),
		);
		$list = http_request( $url, $data, $head );
        $list = json_decode( $list ,true);
		/*foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['CustomerName']=$val['CustomerName'];
		$info['model'][$row]['Phone']=$val['Phone'];
		$info['model'][$row]['CellPhone']=$val['CellPhone'];
		$info['model'][$row]['CustomersType']=$val['CustomersType'];
		$info['model'][$row]['CreateTime']=substr($val['CreateTime'],6,13);
		}
		$info['totalCount'] =$list['totalCount'];*/
        $this->ajaxReturn( $list, 'json' );
	
	
	}
	
	
		private function checkasstoken(){
	
			$url = C('SERVER_HOST')."IPP3Customers/IPP3CustomerAliPayConfig";
            $arr = array(
                "CustomerServiceSysNo"=> session('SysNO'),
               
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
            $data = json_decode( $res, TRUE );
			$Code=0;
           if(strlen($data['app_auth_token'])>0){
		   
		   $Code=1;
		   }
            return $Code;
	
	
		}

}
