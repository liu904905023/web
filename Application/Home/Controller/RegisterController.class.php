<?php

namespace Home\Controller;

use Think\Controller;

class RegisterController extends Controller{

    public function index(){
        
    }

    public function staff(){
        if( cookie( 'status' ) != 'ok' ){
            header( 'Location:' . SITE_URL . '/index.php/Login/login' );
        }
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
                "inuser"               => session( 'data' )['SysNo'],
                "edituser"             => session( 'data' )['SysNo'],
            );
            $data = json_encode( $arr );
//           var_dump( $data );exit;
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );

            $res = http_request( $url, $data, $head );

            $result = json_decode( $res, TRUE );
//            dump( $result );

            $this->ajaxReturn( $result );
            exit();
        }
        $this->display( 'staff_register' );
    }

    public function service(){

        if( IS_AJAX ){
            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3CustomerInsert";
            $arr  = array(
                "Customer"         => I( "username" ),
                "Pwd"              => I( "passwd" ),
                "CustomerName"     => I( "realname" ),
                "Email"            => I( "email" ),
                "Phone"            => I( "phone" ),
                "Fax"              => I( "fax" ),
                "DwellAddress"     => I( "address" ),
                "DwellZip"         => I( "zipcode" ),
                "Note"             => I( "note" ),
                "CustomersType"    => I( "CustomersType" ),
                "Status"           => 0,
                "Vip_CustomerType" => "1",

            );
            $data = json_encode( $arr );
// var_dump($data);exit();
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );
            $res  = http_request( $url, $data, $head );
//            dump( $res );
            $data = json_decode( $res, TRUE );

            // var_dump($data);exit;
            $this->ajaxReturn( $data );
        }

//        if( $data['Code'] == 0 ){
//            
//            exit();
//        }
        $this->display( 'register' );
    }

	public function customershopinsert (){
	
	
	$arr  = array(
                "Customer"         => I( "username" ),
                "Pwd"              => I( "passwd" ),
                "CustomerName"     => I( "realname" ),
                "Email"            => I( "email" ),
                "Phone"            => I( "phone" ),
                "Fax"              => I( "fax" ),
                "DwellAddress"     => I( "address" ),
                "DwellZip"         => I( "zipcode" ),
                "CustomersType"    => I( "CustomersType" ),
                "Note"             => I( "note" ),
                //"Status"           => 0,
                "Vip_CustomerType" => "1",
                "Customer_field_one" => I("Customer_Wft"),
                "SystemUserSysNo" => I("SystemUserSysNo")
				);
	$data = json_encode( $arr );
//	var_dump($data);exit;
	$head = array(
		"Content-Type:application/json;charset=UTF-8",
		"Content-length:" . strlen( $data )
	);
	$url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerShopInsert";
	$res  = http_request( $url, $data, $head );
	$data = json_decode( $res, TRUE );
	$this->ajaxReturn( $data );
	
	
	}

}
