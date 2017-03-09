<?php
namespace Home\Controller;
use Think\Controller;
//use Common\Compose\Base;

class BaseController extends Controller{

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

        $type = session('data')['CustomersType'];             //0-服务商  1-商户
        $flag = session('flag');                            //0-服务商登陆  1-员工登陆
        $servicestoretype = session('servicestoretype');    //0-服务商员工 1-商户员工
        // var_dump($servicestoretype);exit;
        //员工登录
        if ($flag == 1) {
        	$data['SystemUserSysNo'] =  session('SysNO');   //员工主键
        	$data = json_encode($data);
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data ),
            );
			
			$list =cookie('staff');
			if(!$list)
			{
            $list = ( C('SERVER_HOST')."IPP3Customers/IPP3UserRoleList",$data, $head );
			$list = json_decode($list,true);
			   foreach ($list as $row=>$val){
                
                    $info[$row]['SysNo']=$val['SysNo'];
                    $info[$row]['RoleName']=$val['RoleName'];
                    $info[$row]['Description']=$val['Description'];
                    $info[$row]['URL']=$val['URL'];
                
            }
            $aaa = json_encode($info);
			cookie("staff",$aaa);
			$list = json_decode($aaa,true);
			}
			else
			{
			$aaa=cookie('staff');
			$list = json_decode($aaa,true);
			}
			


            foreach ($list as $row=>$val)
            {
                $list2['Description'][] = $val['Description'];
                $list2['RoleName'][] = $val['RoleName'];
                $list2['URL'][] = $val['URL'];
                $list2['SysNo'][] = $val['SysNo'];
            }

            $list3 = array_unique($list2['Description']);
            // var_dump($list3);exit;
            // var_dump($data);exit;
            $this->assign('list',$list);
            $this->assign('list3',$list3);
           // $this->display('Base/header');
			
        }
        //服务商登陆
        else if($type == 0) {
            $data['PagingInfo']['PageSize'] = 200;
            $data['PagingInfo']['PageNumber'] = 0;
            $data = json_encode($data);
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data ),
            );
			$list =cookie('service');
			if(!$list)
			{
            $list = ( C('SERVER_HOST')."IPP3Customers/IPP3SystemRoleList",$data, $head );
            $list = json_decode($list,true);
			   foreach ($list['model'] as $row=>$val){
                if(($val['URL'] != "/Order/platform_order_search")&&($val['URL'] != "/Order/order_search_alipay")&&($val['URL'] != "/OrderFund/orderfund?Top=1")&&($val['URL'] != "/OrderFund/orderfund")&&($val['URL'] != "/OrderExtend/order_extend")&&($val['URL'] != "/Refund/refund"))
                {
                    $info[$row]['SysNo']=$val['SysNo'];
                    $info[$row]['RoleName']=$val['RoleName'];
                    $info[$row]['Description']=$val['Description'];
                    $info[$row]['URL']=$val['URL'];
                }
                
            }
			$aaa = json_encode($info);
			cookie("service",$aaa);
			$list = json_decode($aaa,true);
			}
			else
			{
			$aaa=cookie('service');
			$list = json_decode($aaa,true);
			}
			
         

            // var_dump("<pre>");
            // var_dump($list);exit();
            $list3= array(0=>("商户配置"),1=>("员工管理"),2=>("交易订单"),3=>("商户列表"),4=>("权限分配"),5=>("退款"));
            $this->assign('list',$list);
            $this->assign('list3',$list3);
            //$this->display('Base/header');
        }
        //商户登录
        else if($type == 1){
			$data['CustomerServiceSysNo'] =  session('SysNO');   //商户主键
            $data = json_encode($data);
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data ),
            );
			$status =cookie('store');
			if(!$status)
			{
            $list = ( C('SERVER_HOST')."IPP3Customers/IPP3CustomerRoleList",$data, $head );
            $list = json_decode($list,true);
			foreach ($list as $row=>$val){
            
                $info[$row]['SysNo']=$val['SystemRoleSysNo'];
                $info[$row]['RoleName']=$val['RoleName'];
                $info[$row]['Description']=$val['Description'];
                $info[$row]['URL']=$val['URL'];
            }
			$aaa = json_encode($info);
			cookie("store",$aaa);
			$aaa=cookie('store');
			$list = json_decode($aaa,true);
			}
			else
			{
			$aaa=cookie('store');
			$list = json_decode($aaa,true);
			}
			foreach ($list as $row=>$val)
            {
                $list2['Description'][] = $val['Description'];
                $list2['RoleName'][] = $val['RoleName'];
                $list2['URL'][] = $val['URL'];
                $list2['SysNo'][] = $val['SysNo'];
            }
			
            $list3=  array_unique($list2['Description']);
            $this->assign('list',$list);
            $this->assign('list3',$list3);
        }

    }

}
