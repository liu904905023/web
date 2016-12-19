<?php

namespace Home\Controller;

//use Think\Controller;
use Common\Compose\Base;

class RefundSearchController extends Base{
   
	public function refund_search(){
  		R("Base/getMenu");
		//var_dump(session(data));
        $this->display();
    }

  
	public function refundsearch(){
		$Time_Start = empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end = empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$CustomerNames =  $_POST['CustomerNames'];
		$Storename =  $_POST['Storename'];
		$Out_trade_no = $_POST['Out_trade_no'];
		$Ordertype = $_POST['Ordertype'];
		//$refund_fee = yuan2fee($_POST['refund_fee']);
		$Create_Time_Start =empty($_POST['Create_Time_Start'])? $_POST['Create_Time_Start']:$_POST['Create_Time_Start']." 00:00:00";
		$Create_Time_end = empty($_POST['Create_Time_end'])? $_POST['Create_Time_end']:$_POST['Create_Time_end']." 23:59:59";
		
		$data = array(
			"Transaction_id"=>$Transaction_id,
			"Out_trade_no"=>$Out_trade_no,
			"Time_Start"=>$Time_Start,
			"Time_end"=>$Time_end,
			"Create_Time_Start"=>$Create_Time_Start,
			"Create_Time_end"=>$Create_Time_end,
			"CustomerName"=>$CustomerNames,
			"Customer"=>$Storename,
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
		}
		if($type==1){
		$data['SystemUserSysNo']=session(SysNO);
		}
		if($type==0){
		$data['Customer']=$_POST['Customer'];
		}
				$url=C('SERVER_HOST')."IPP3Order/IPP3RMA_RequestSP";


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
//		var_dump($list);
		foreach ($list['model'] as $row=>$val){
//		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['LoginName']=$val['LoginName'];
		$info['model'][$row]['DisplayName']=$val['DisplayName'];
		$info['model'][$row]['Out_trade_no']=$val['Out_trade_no'];
		$info['model'][$row]['Pay_Type']=$val['Pay_Type'];
		$info['model'][$row]['Time_Start']=$val['Time_Start'];
		$info['model'][$row]['customername']=$val['CustomerName'];
		$info['model'][$row]['Total_fee']=fee2yuan($val['Total_fee']);
		$info['model'][$row]['refund_fee']=fee2yuan($val['Refund_fee']);
		//$info['model'][$row]['CreateTime']=date("y-m-d H:i:s","1465721624910");
		$info['model'][$row]['CreateTime']=$val['CreateTime'];
		$totalfee =$totalfee+ fee2yuan($val['refund_fee']);
		
		}
		$info['totalCount'] =$list['totalCount'];
		$info['totalfee'] =$totalfee;


        $this->ajaxReturn($info,json);
	
	
	
	}

}
