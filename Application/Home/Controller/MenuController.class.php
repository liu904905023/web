<?php
namespace Home\Controller;

//use Think\Controller;
use Common\Compose\Base;

class MenuController extends Base{

    public function refund()
	{
        $this->display();
    }
	public function order_search()
	{
        $this->display();
    }
	public function getMenu()
    {
    	$data['SystemUserSysNo'] =  session('SysNO');  //员工主键
        
    	$data = json_encode($data);
        // var_dump($data);exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
        );

        $list = http_request( C('SERVER_HOST')."IPP3Customers/IPP3UserRoleList",$data, $head );
        // var_dump("<pre>");
        // var_dump($list);
        $arrData = json_decode( $list, TRUE );
        $arrData[0][RoleName] = "一";
        $arrData[1][RoleName] = "二";
        $arrData[2][RoleName] = "三";
        $arrData[3][RoleName] = "四";
        $arrData[4][RoleName] = "五";
        $arrData[5][RoleName] = "六";
        $arrData[6][RoleName] = "七";
  //       var_dump("<pre>");
		// var_dump($arrData);
// exit;
        // S('arrData',$value);
        $this->assign( 'data', $arrData );
        $this->display( 'menu' );
	}

}
