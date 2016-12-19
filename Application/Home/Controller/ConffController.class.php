<?php

namespace Home\Controller;

//use Think\Controller;

use Common\Compose\Base;

class ConffController extends Base{

//class ConffController extends Controller{

    public function index(){
        
        $this->display();
    }

    /**
     * 服务商配置
     */
    public function wxConfig(){
		// var_dump(SESSION('data'));
    R("Base/getMenu");
        if( IS_POST ){

            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerConfigEdit";
            $arr  = array(
//                "CustomerServiceSysNO" => 1,
                "CustomerServiceSysNO" => session( 'data' )['SysNo'],
                "APPID"                => I( 'sx_appid', '', 'htmlspecialchars' ),
                "NCHID"                => I( 'sx_fwsbh', '', 'htmlspecialchars' ),
                "KEY"                  => I( 'sx_shkey', '', 'htmlspecialchars' ),
                "APPSECRET"            => I( 'sx_appsecret', '','htmlspecialchars' ),
                "sub_mch_id"           => (int) I( 'sx_zshid', '','htmlspecialchars' ),
                "SSLCERT_PATH"         => I( 'safe', '','htmlspecialchars' ),
                "Status"               => 0,
                "SSLCERT_PASSWORD"     => (int) I( 'sx_pass', '','htmlspecialchars' ),
            );

            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );

            $arrData = json_decode( $res, TRUE );
            $this->ajaxReturn( $arrData );
            exit();

        }else{
            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerConfig";
            $arr  = array(
                'CustomerServiceSysNo' => session( 'data' )['SysNo'],
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );

            $arrData = json_decode( $res, TRUE );

            $this->assign( 'data', $arrData );
        }
        $this->display( 'commercial_tenant_config' );
    }

//查询页
    public function infoDetail(){
        R("Base/getMenu");
        if( IS_AJAX ){

            $url = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserUpdate";
            $arr = array(
                "SysNo"            => session('data')['SysNO'],
                "PhoneNumber"         => I('Phone'),
                "DisplayName"         => I('displayname'),
                "Alipay_store_id"         => I('store_id'),
                "Email"         => I('Email'),
                "Rate"         => (double)I('user_rate'),
				"DwellAddress"     => I( "address" ),
				"DwellAddressID"     => I( "AddressNum" ),
				"EditUser" =>session('servicestoreno')
               
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
            $data = json_decode( $res, TRUE );
            $this->ajaxReturn( $data, 'json' );
            exit();
        }else{

            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserList";
		
            $arr  = array(
                "SysNo" => session('data')['SysNO'],
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );

            $data = json_decode( $res, TRUE );
			$DetailAddress = explode("-",$data['model'][0]['DwellAddress']);
			$City=$this->GetAddress($data['model'][0]['Province']);
			$Country=$this->GetAddress($data['model'][0]['City']);
			$this->assign( 'data', $data['model'][0] );
			if($data['model'][0]['DwellAddressID']){
				$this->assign( 'Country', $Country );
				$this->assign( 'City', $City );
			}else{
				$this->assign( 'Country', array(0=>array("region_id"=>'','region_name'=>"请选择区")) );
				$this->assign( 'City',array(0=>array("region_id"=>'','region_name'=>"请选择市")) );
			}
			$this->assign( 'DetailAddress', $DetailAddress[1] );
		}
        $this->display();
    }

    public function password(){
       R("Base/getMenu");
        if( IS_POST ){
			if(session('flag')==0){
			$url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerUpdPwd"; 
			$arr  = array(
                "SysNo"       => session( 'SysNO' ),
                "OldPassWord" => I( 'oldpass' ),
                "NewPassWord" => I( 'newpass' ),
            );
			}else if (session('flag')==1){
			$url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3SystemUserUpdatePwd";
			 $arr  = array(
                "SysNo"       => session( 'SysNO' ),
                "OldPassWord" => I( 'oldpass' ),
                "Password" => I( 'newpass' ),
            );
			}
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
            $data = json_decode( $res, TRUE );
            $this->ajaxReturn( $data );
            exit();
        }else{
			if(session('flag')==0){
            $data = array(
                'username' => session( 'data' )['Customer'],
            );
			}else if (session('flag')==1){
			$data = array(
			'username' => session( 'data' )['LoginName'],
			);
			}
            $this->assign( 'data', $data );
        }
        $this->display( 'password' );
    }

 

    /**
     * 支付宝服务商配置
     */
    public function zfbConfig(){
		R("Base/getMenu");
        if( IS_POST ){
            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerAliPayConfigEdit";
            $arr  = array(
                "CustomerServiceSysNO"  => session( 'data' )['SysNo'],              //商户服务商编号
                "APPID"                 => I( 'sx_appid', '', 'htmlspecialchars' ), //APIID
                "PID"                   => I( 'sx_shid', '', 'htmlspecialchars' ),  //商户ID
                "sub_PID"               => I( 'sx_zshid', '', 'htmlspecialchars' ), //子商户ID
                "Merchant_private_key"  => $_POST['sx_shsy'], //商户私钥
                "Merchant_public_key"   => $_POST['sx_shgy'], //商户公钥
                "Alipay_public_key"     => $_POST['sx_algy'], //阿里公钥
                "Type"					=> $_POST['sh_type'], //阿里公钥
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
            $arrData = json_decode( $res, TRUE );
            $this->ajaxReturn( $arrData );
            exit();
        }else{
            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerAliPayConfig";
            $arr  = array(
                'CustomerServiceSysNo' => session( 'data' )['SysNo'],
            );
            $data = json_encode( $arr );
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
            $arrData = json_decode( $res, TRUE );
            $this->assign( 'data', $arrData );
        }

        $this->display( 'commercial_tenant_alipay' );
    }



// 商户资料读取修改
    public function MerchantDetail(){
            R("Base/getMenu");
            if( IS_AJAX ){
                $url = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerUpd";
                $arr = array(
                    "SysNo"         => session('SysNO'),
                    "CustomerName"  => I('CustomerName'),
                    "Phone"         => I('Phone'),
                    "CellPhone"     => I('CellPhone'),
                    "Email"         => I('Email'),
                    "Rate"          => I('Rate'),
                    "DwellAddress"  => I('DwellAddress'),
					"DwellAddressID"     => I( "AddressNum" )
                );
                $data = json_encode( $arr );
                $head = array(
                    "Content-Type:application/json;charset=UTF-8",
                    "Content-length:" . strlen( $data )
                );
                $res  = http_request( $url, $data, $head );
                $data = json_decode( $res, TRUE );
                $this->ajaxReturn( $data, 'json' );
                exit();
            }else{

                $SysNo = session( 'SysNO');
                $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerShopList";
                $arr  = array(
                    "SysNo" => $SysNo
                );
                $data = json_encode( $arr );
                $head = array(
                    "Content-Type:application/json;charset=UTF-8",
                    "Content-length:" . strlen( $data )
                );
                $res  = http_request( $url, $data, $head );
                $data = json_decode( $res, TRUE );
				$City=$this->GetAddress($data['model'][0]['Province']);
				$Country=$this->GetAddress($data['model'][0]['City']);
                $this->assign( 'data', $data['model'][0] );
                if($data['model'][0]['DwellAddressID']){
				$DetailAddress = explode("-",$data['model'][0]['DwellAddress']);
				$this->assign( 'DetailAddress', $DetailAddress[1] );
				$this->assign( 'Country', $Country );
				$this->assign( 'City', $City );
				}else{
				$DetailAddress = $data['model'][0]['DwellAddress'];
				$this->assign( 'DetailAddress', $DetailAddress );
				$this->assign( 'Country', array(0=>array("region_id"=>'','region_name'=>"请选择区")) );
				$this->assign( 'City',array(0=>array("region_id"=>'','region_name'=>"请选择市")) );
				}
               
            }

            $this->display();
        }

		private function GetAddress($parent_id,$type=0){
		
			if($type==1){
			}
			else{
				$data['Parent_id']=$parent_id;
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

			return $info;
	
	
		}



}
