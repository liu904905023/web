<?php
namespace Home\Controller;
use Think\Controller;
class QRCodeListController extends Controller {

	public function codelist(){
		R("Base/getMenu");
		$this->display();
	}


	public function produce_qrcode(){
		$data['len'] = I('qcodenum');
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3QR_CodeList"; 
		$data = json_encode( $data );
		$head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request($url,$data,$head);
		$list = json_decode($list,true);
		foreach ($list as $row=>$val){
			$info[$row]['SysNo'] = $val['SysNo'];
		}
		$this ->ajaxreturn($info);
	}

	public function code_register(){
		$data['CodeSysNo'] = I('CodeSysNo','');
		$data['SystemUserTopSysNo'] = I('Topid','');
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3QR_CodeQuery"; 
		$data = json_encode( $data );
		$head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request($url,$data,$head);
		$list = json_decode($list,true);
		if($list['Code']==1){
			if($list['Data']['Result']==5){
				R("Base/getMenu");
				$this->display('QRCodeList/coderegister');
			}
		}
		if($list['Code']==0){
			$this->redirect("Wxpay/newpay/?systemUserSysNo=".$list['Data']['SystemUserSysNo']); 
		}
	}


	public function qrcode_register(){
		$codesysno 		= I('codesysno');
		$username 		= I('sh_yhm');
		$storename 		= I('sh_mdmc','');
		$staffid 		= I('sh_staffid','');

		$loginname 		= I('sx_dlm','');
		$loginpassword 	= I('sx_pass','');
		$realname 		= I('sx_name','');
		$addressNum 	= I( 'AddressNum' );
		$address 		= I( 'address' );

		$bankname 		= I( 'sx_khhmc' );
		$accountnumber 	= I( 'sx_khhh' );//支行;
		
		$bankcardnumber	= I( 'sx_yhkh' );
		$accountname 	= I( 'sx_khxm' );
		$accounttype 	= I( 'sx_skrzhlx' );

		$arr  = array(
			"CodeSysNo"			 => $codesysno,
			"Customer" 		 	 => $username,
			"Pwd"				 => $loginpassword,
			"CustomerName"		 => $storename,
			"SystemUserTopSysNo" => $staffid,

	        "LoginName"          => $loginname,
	        "Password"         	 => $loginpassword,
	        "DisplayName"     	 => $realname,
	        "DwellAddress"     	 => $address,
	        "DwellAddressID"   	 => $addressNum,

	        "BankName"			 => $bankname,
	        "BankNo"			 => $accountnumber,
	        "BankCardNo"		 => $bankcardnumber,
	        "AccountName"		 => $accountname

		);
		$Total_Bank = explode('|',$accountnumber);
		if($Total_Bank[0]=="305100000013"){
			$arr["PayeeAccountType"]  = "借记卡";
			
		}else{
			$arr["PayeeAccountType"]  = "他行";
		}
		$data = json_encode( $arr );
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data )
		);
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerService_User";
		$res  = http_request( $url, $data, $head );
		$data = json_decode( $res, TRUE );
		$data['Result'] =$data['Data']['Result'];
		$data['SysNo'] =$data['Data']['SystemUserSysNo'];
			if($data['Code']==0){
				$CustomerServiceSysNo =$data['Data']['CustomerServiceSysNo'];
				$this -> CustomerUserRoleInsert($CustomerServiceSysNo);
			}
		$this->ajaxReturn( $data );
	}
	public function getbanklist(){
		$data='';
		$data = json_encode( $data );
		$url=C('SERVER_HOST')."IPP3Customers/IPP3Parent_BankList";
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data )
		);
		$res  = http_request( $url, $data, $head );
		$list = json_decode( $res, TRUE );
		foreach ($list as $row=>$val){
			$info[$row]['Bank_No'] = $val['Parent_bank_no'];
			$info[$row]['Bank_Name'] = $val['Parent_bank_name'];
		
		}
		$this->ajaxReturn( $info );
	
	}
	public function searchbank(){
		$data['Parent_bank_no']=I('Bank_No');
		$data['Bank_name']=I('Bank_Key');
		$data = json_encode( $data );
		$url=C('SERVER_HOST')."IPP3Customers/IPP3BankList";
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $data )
		);
		$res  = http_request( $url, $data, $head );
		$list = json_decode( $res, TRUE );
		foreach ($list as $row=>$val){
			$info[$row]['Total_Bank'] = $val['Total_bank_no'].'|'.$val['Bank_no'];
			$info[$row]['Bank_Name'] = $val['Bank_name'];
		
		}
		$this->ajaxreturn($info);
	
	}
	public function aaa(){
	
	$this -> CustomerUserRoleInsert(2962);
	
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