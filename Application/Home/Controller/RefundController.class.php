<?php

namespace Home\Controller;

//use Think\Controller;
use Common\Compose\Base;

class RefundController extends Base{

    public function refund()
	{
		R("Base/getMenu");
//		var_dump(session(data));
        $this->display();
    }

	public function refund1()
	{
		R("Base/getMenu");
//		var_dump(session(data));
        $this->display();
    }

		public function refund2()
	{
		R("Base/getMenu");
//		var_dump(session(data));
        $this->display();
    }
	public function order_search()
	{
     
        $this->display();
    }
	public function refundsearch(){

		$Time_Start = empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end = empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$out_refund_no = $_POST['out_refund_no'];
		$data = array(
			"starttime"=>$starttime,
			"endtime"=>$endtime,
			"out_refund_no"=>$out_refund_no

		);
        $data = json_encode( $data );
//		var_dump($data);exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );
       $list = http_request( C('SERVER_HOST')."POS/POSRefundList",$data, $head );
        $this->ajaxReturn( $list ,json);
	
	}
	public function refundinsert(){//退款新增
		$out_trade_no = $_POST['out_trade_no'];
		$total_fee = yuan2fee($_POST['total_fee']);
		$refund_fee = yuan2fee($_POST['refund_fee']);
		$SOSysNo = $_POST['SOSysNo'];
		$paytype = $_POST['paytype'];
		$timestart = $_POST['timestart'];
		$time=explode(" ",$timestart);
		$Ymd = $time[0];
		
//		$Ymd = "2016-07-09";
//		echo $hms;exit;
		//$Day=  (int)$hms[2];
		$NowDay = date("Y-m-d",time());
		if(strtotime($Ymd)==strtotime($NowDay)){
		$data = array(
			"out_trade_no"=>$out_trade_no,
			"refund_fee"=>$refund_fee,
			"total_fee"=>$total_fee,
			"SOSysNo"=>$SOSysNo

		);
		if(session(flag)==0){
		$data['YwMch_id2']=session(SysNO);
		}else{
		$data['YwMch_id2']=session(SysNO);
		}
        $data = json_encode( $data );
//		var_dump($data);exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );
		
		if($paytype=='102'){
			$url = C('SERVER_HOST')."POS/POSRefundInsert";
		}
		if($paytype=='103'){
			$url = C('SERVER_HOST')."IPP3AliPay/AliPayRefundUnion";
		}
		$list = http_request( $url,$data, $head );

		$list = json_decode($list,true);
		}else{
		$list['Description']="非当天交易不允许退款";
		}

        $this->ajaxReturn( $list ,json);
	}

	public function checkuserpass(){
	
		$password = I("password");
		$data = 0;
		if(session(password)==$password){
			$data = 1;
		}
		 $this->ajaxReturn( $data ,json);
	
	
	}
	public function refundlist(){

		$Time_Start = empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end = empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$out_trade_no = $_POST['out_trade_no'];
		//$CustomerSysNo = empty($_POST['CustomerSysNo'])? session(SysNO):$_POST['CustomerSysNo'];
		$PageNumber = $_POST['PageNumber'];
		$PageSize = $_POST['PageSize'];
		
		$data = array(
			"Time_Start"=>$Time_Start,
			"Time_end"=>$Time_end,
			"Out_trade_no"=>$out_trade_no

		);

		$flag =  session(flag);//服务商商户0 或员工1
		if($flag ==1){
		$type =  session(servicestoretype);//员工的服务商的类型 0为服务  1为商户
		}
		

		if(session(data)['CustomersType']==0||$flag==0){//服务商或者服务商员工 必须填写商户名进行查询，不需要传递CustomerSysNo
		$data['Customer']=$_POST['Customer'];
		}
		if(session(data)['CustomersType']==1){
		$data['CustomerSysNo']=session('SysNO');
		}
		if($type==1){
		$data['SystemUserSysNo']=session('SysNO');
		}


		$data['PagingInfo']['PageSize'] = $PageSize;
		$data['PagingInfo']['PageNumber'] = $PageNumber;
        $data = json_encode( $data );
//		var_dump($data);exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
		$list = http_request(C('SERVER_HOST')."IPP3Order/IPP3OrderFundListSP",$data,$head);
		$list = json_decode($list,true);
		foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['Out_trade_no']=$val['Out_trade_no'];
		$info['model'][$row]['Pay_Type']=$val['Pay_Type'];
		//$info['model'][$row]['Status']=$val['Status'];
		$info['model'][$row]['Total_fee']=fee2yuan($val['Total_fee']);
		$info['model'][$row]['Time_Start']=$val['Time_Start'];
		$info['model'][$row]['Cash_fee']=fee2yuan($val['Cash_fee']);
		$info['model'][$row]['refund_fee']=fee2yuan($val['refund_fee']);
		$info['model'][$row]['fee']=fee2yuan($val['fee']);
		$info['model'][$row]['refundCount']=$val['refundCount'];
		
		}
		$info['totalCount'] =$list['totalCount'];
        $this->ajaxReturn( $info ,json);
	
	
	
	}
   
}
