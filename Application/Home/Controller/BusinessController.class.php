<?php

namespace Home\Controller;

//use Think\Controller;

use Common\Compose\Base;

class BusinessController extends Base{

//class ConffController extends Controller{

    public function Business(){
    	R("Base/getMenu");
		//var_dump(session(data));
        $this->display();
    }
    public function Business1(){
    	R("Base/getMenu");
		//var_dump(session(data));
        $this->display();
    }
    public function business_register(){
    	R("Base/getMenu");
		//var_dump(session(data));
        $this->display();
    }

	public function business_detail(){
	
		$SysNO = I('SysNo','');
		$data['sysno'] = $SysNO;
		$data['CustomersTopSysNo'] = session('SysNO');
		$data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );

        $list = http_request(C('SERVER_HOST')."IPP3Customers/IPP3CustomerShopList",$data,$head);
		$list = json_decode($list,true);
		$info['customer'] = $list['model'][0]['Customer']; 
		$info['phone'] = $list['model'][0]['Phone']; 
		$info['CustomerName'] = $list['model'][0]['CustomerName']; 
		$info['Email'] = $list['model'][0]['Email']; 
		$info['Fax'] = $list['model'][0]['Fax']; 
		$info['DwellAddress'] = $list['model'][0]['DwellAddress']; 
		$info['DwellZip'] = $list['model'][0]['DwellZip']; 
		$info['user_rate'] = $list['model'][0]['UserRate']; 
		$info['rate'] = $list['model'][0]['Rate']; 
		$info['RegisterTime'] =date("Y-m-d H:i:s",substr($list['model'][0]['RegisterTime'],6,10));
		$StaffSysNo= $this -> QueryStaff($SysNO);
		$info['TopStaffId'] = $this->QueryStaffInfo($StaffSysNo);
		$this ->ajaxreturn($info);
	
	}

	public function QueryStaff($id){
	
	$data['CustomerServiceSysNo'] = $id;
	$data = json_encode( $data );
	$head = array(
		"Content-Type:application/json;charset=UTF-8",
		"Content-length:" . strlen( $data ),
		"X-Ywkj-Authentication:" . strlen( $data )
	);
	$list = http_request(C('SERVER_HOST')."IPP3Customers/IPP3CustomerUsersList",$data,$head);
	$list = json_decode($list,true);
	return $list['model'][0]['SystemUserSysNo'];	
	}

	public function QueryStaffInfo($id){
		$data['SysNo']= $id;
		$data = json_encode( $data );
		$head = array(
		"Content-Type:application/json;charset=UTF-8",
		"Content-length:" . strlen( $data ),
		"X-Ywkj-Authentication:" . strlen( $data)
		);
		$list = http_request(C('SERVER_HOST')."IPP3Customers/IPP3SystemUserList",$data,$head);
		$list = json_decode($list,true);
		return $list['model'][0]['DisplayName'];
	
	}
	public function customerrateupdate(){
		$data['SysNo'] =	I('SysNo');
		$data['UserRate'] = I('Rate');
		$data = json_encode( $data );
		$head = array(
		"Content-Type:application/json;charset=UTF-8",
		"Content-length:" . strlen( $data ),
		"X-Ywkj-Authentication:" . strlen( $data)
		);
		$list = http_request(C('SERVER_HOST')."IPP3Customers/IPP3CustomerUserRateUpdate",$data,$head);
		$list = json_decode($list,true);
		$this ->ajaxreturn($list);
	
	
	
	}
	public function businessregister (){
		$arr  = array(
	                "Customer"         => I( "username" ),
	                "Pwd"              => I( "passwd" ),
	                "CustomerName"     => I( "realname" ),
	                "Email"            => I( "email" ),
	                "Phone"            => I( "phone" ),
	                "Fax"              => I( "fax" ),
	                "DwellAddress"     => I( "address" ),
	                "DwellAddressID"     => I( "AddressNum" ),
	                "DwellZip"         => I( "zipcode" ),
	                "Rate"         => (double)I( "sx_rate" ),
	                "CustomersType"    => "1",
	                //"Status"           => 0,
	                "Vip_CustomerType" => "1",
					"Customer_field_one" => I("Customer_Wft"),
	                "SystemUserSysNo" => I("SystemUserSysNo")
		);
		$data = json_encode( $arr );

		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data )
		);
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerShopInsert";
		$res  = http_request( $url, $data, $head );
		$data = json_decode( $res, TRUE );
		if($data['Code']==0){
			
			$CustomerSysno = $this->servicequerycustomer($arr['Customer']);
			$this -> CustomerUserRoleInsert($CustomerSysno);
		}
		$this->ajaxReturn( $data );
	}

////调拨
	public function customeruserupdate(){
		$data  = array(
					"CustomerServiceSysNo" => I("customerid"),
	                "SystemUserSysNo"	 => I("staffid")
		);
		$data = json_encode( $data );
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerUserUpdate";

		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data )
		);
		$res  = http_request( $url, $data, $head );
		$data = json_decode( $res, TRUE );
		$this->ajaxReturn( $data );
		
	
	
	}
	private function servicequerycustomer($Customers){
	
		
		if(session(flag)==0){
		$data['CustomersTopSysNo'] = session(data)['SysNo']; 
		}
		
		$data['Customer']   = $Customers;
		$data['PagingInfo']['PageSize']   = 1;
		$data['PagingInfo']['PageNumber'] = 0;
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerList";
		$data = json_encode($data);
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data ),
			//"X-Ywkj-Authentication:" . strlen( $data ),
		);
		$list = http_request( $url, $data, $head );
        $list = json_decode( $list ,true);
		return $list['model'][0]['SysNo'];
	
	}

	private function CustomerUserRoleInsert($CustomerServiceSysNo){
		$RoleList = array(0=>12,1=>13,2=>14,3=>22,4=>23,5=>24,6=>25,7=>27);
		foreach($RoleList as $row){
			$data[] = array(
				"SystemRoleSysNo"		=> $row,
				"CustomerServiceSysNo"	=> $CustomerServiceSysNo,
				"InUser"				=> $CustomerServiceSysNo,
				"EditUser"				=> $CustomerServiceSysNo
			); 
		}
		$data = json_encode($data);
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

		$list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3CustomerRoleInsert",$data, $head );
		$list = json_decode($list,true);
	
	}
	





}