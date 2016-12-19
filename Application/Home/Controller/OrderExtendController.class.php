<?php
namespace Home\Controller;
//use Think\Controller;
use Common\Compose\Base;

class OrderExtendController extends Base {

    public function order_extend(){
		R("Base/getMenu");
        $this->display();
    }

	public function OrderExtendList(){
		$Time_Start = empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end = empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$Transaction_id =  $_POST['Transaction_id'];
		$Out_trade_no = $_POST['out_trade_no'];
		$Ordertype = $_POST['Ordertype'];
		
		
		$data = array(
			"Transaction_id"=>$Transaction_id,
			"Out_trade_no"=>$Out_trade_no,
			"Time_Start"=>$Time_Start,
			"Time_end"=>$Time_end,
			"Pay_Type"=>$Ordertype

		);


		$flag =  session(flag);//服务商商户0 或员工1
		$type=3;
		if($flag ==1){
		$type =  staffstoreorservice(session(SysNO));
		}
		

		if(session(data)['CustomersType']==0&$flag==0){
		$data['CustomersTopSysNo']=session(SysNO);
		}
		if(session(data)['CustomersType']==1&$flag==0){
		$data['CustomerSysNo']=session(SysNO);
		$data['CustomerName']=$_POST['CustomerNames'];
		}
		if($type==1){
		$data['SystemUserSysNo']=session(SysNO);
		}
		
		$url=C('SERVER_HOST')."IPP3Order/IPP3So_Master_ExtendList";


		$data['PagingInfo']['PageSize'] = $_POST['PageSize'];
		$data['PagingInfo']['PageNumber'] = $_POST['PageNumber'];
        $data = json_encode( $data );
//		var_dump($data);echo "\n".$url ;exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data ),
        );
        $list = http_request($url,$data,$head);
		$list = json_decode($list,true);
//		var_dump($list);exit;
		foreach ($list['model'] as $row=>$val){
//		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['Out_trade_no']=$val['Out_trade_no'];
		$info['model'][$row]['loginname']=$val['LoginName'];
		$info['model'][$row]['displayname']=$val['DisplayName'];
		$info['model'][$row]['CustomerName']=$val['CustomerName'];
		$info['model'][$row]['Pay_Type']=$val['Pay_Type'];
		$info['model'][$row]['Time_Start']=$val['Time_Start'];
		$info['model'][$row]['customername']=$val['CustomerName'];
		$info['model'][$row]['Total_fee']=fee2yuan($val['Total_fee']);
		$info['model'][$row]['refund_fee']=fee2yuan($val['Refund_fee']);
		$info['model'][$row]['CreateTime']=$val['CreateTime'];

		$info['model'][$row]['name']=$val['Name'];
		$info['model'][$row]['phonenumber']=$val['PhoneNumber'];
		$info['model'][$row]['type']=$val['Type'];
		$info['model'][$row]['studentId']=$val['StudentID'];
		$info['model'][$row]['remarks']=$val['Remarks'];
		$info['model'][$row]['num']=$val['Num'];
		
		}
		$info['totalCount'] =$list['totalCount'];

        $this->ajaxReturn($info,json);
	
	}
}