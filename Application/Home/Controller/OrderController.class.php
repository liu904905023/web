<?php
namespace Home\Controller;
//use Think\Controller;
use Common\Compose\Base;

class OrderController extends Base {

    public function order_search(){
		R("Base/getMenu");
        $this->display();
    }
	
	public function ordersearch(){//交易订单查询
		$Time_Start =empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end   =empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$out_trade_no = I('out_trade_no',"");
		
		$SystemUserSysNo = I('SystemUserSysNo',"");
		$PageNumber = $_POST['PageNumber'];
		$PageSize = $_POST['PageSize'];
		$CustomerNames = $_POST['CustomerNames'];
		$CustomerNames = $_POST['CustomerNames'];
		$Ordertype = $_POST['Ordertype'];
		$ButtonType = $_POST['ButtonType'];
		$data = array(
			"Time_Start"=>$Time_Start,
			"Time_end"=>$Time_end,
			"Out_trade_no"=>$out_trade_no,
			"CustomerName"=>$CustomerNames,
			"Pay_Type"=>$Ordertype,

		);
		$flag =  session('flag');//服务商商户0 或员工1
		$type =  session('servicestoretype');//员工的服务商的类型 0为服务  1为商户
		if(session('data')['CustomersType']==0&$type==0){
		$stafftype = 0;
		}

		if(session('data')['CustomersType']==0||$stafftype==0){//服务商或者服务商员工登陆 必须填写商户名进行查询，不需要传递CustomerSysNo
		$data['Customer']=$_POST['Customer'];
		}

		if(session('data')['CustomersType']==0&$flag==0){//服务商登陆 传入主键
		$data['CustomersTopSysNo']=session('SysNO');
		}
		if($ButtonType ==0){
			if(session('data')['CustomersType']==0&$flag==0){//服务商登陆 传入主键
				$url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListCustomerSP"; 
			}else if(session('data')['CustomersType']==1&$flag==0){//商户
				if($SystemUserSysNo!="null"){
					$url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopUserSP"; 
					
				}else{
					$url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopSP"; 
				}

			}else if($type==0&$flag==1){//服务商员工
				$url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListCustomerUserSP"; 
			}else if($type==1&$flag==1){//商户员工
				$url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopUserSP"; 
			}
			if($type==0&$flag==1){//服务商员工登陆 传入主键
			$data['SystemUserTopSysNo']=session('SysNO');
			$data['CustomersTopSysNo']=session('servicestoreno');
			}


		}else if($ButtonType==1){
		
				$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListcollect";	
			if($type==0&$flag==1){//服务商员工登陆 传入主键
				$data['SystemUserTopSysNo']=session('SysNO');
				$data['CustomersTopSysNo']=session('servicestoreno');
			}
		}

		
		if(session('data')['CustomersType']==1&$flag==0){//商户登陆 传入主键
		$data['CustomerSysNo']=session('SysNO');
		}
		if($SystemUserSysNo!='null'){           //商户登陆  按员工查询订单,只查员工的传$SystemUserSysNo

			$data['SystemUserSysNo']=$SystemUserSysNo;

		}else{

			if(session('data')['CustomersType']==1){  //商户登陆 查询全部员工的 传$CustomerSysNo
			$data['CustomerSysNo']=session('SysNO');
			}
			if($flag==1&$type==1){
			$data['SystemUserSysNo']=session('SysNO');//员工登陆 查全部 传$CustomerSysNo

			}
		}
		$data['PagingInfo']['PageSize'] = $PageSize;
		$data['PagingInfo']['PageNumber'] = $PageNumber;
//        $data = json_encode( $data );
//        $head = array(
//            "Content-Type:application/json;charset=UTF-8",
//            "Content-length:" . strlen( $data ),
//            "X-Ywkj-Authentication:" . strlen( $data )
//        );
//		
//		$list = http_request($url,$data,$head);
//
//		$list = json_decode($list,true);
		$list = $this->http($data,$url);
		
		if($ButtonType==0){
		foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['loginname']=$val['LoginName'];
		$info['model'][$row]['displayname']=$val['DisplayName'];
		$info['model'][$row]['Out_trade_no']=$val['Out_trade_no'];
		$info['model'][$row]['Pay_Type']=$val['Pay_Type'];
		$info['model'][$row]['Total_fee']=fee2yuan($val['Total_fee']);
		$info['model'][$row]['Time_Start']=$val['Time_Start'];
		$info['model'][$row]['CustomerName']=$val['CustomerName'];
		}
		$info['totalCount'] =$list['totalCount'];
		$info['ButtonType'] =$ButtonType;
		if(session(flag)==1){
		$list['flag']= session('servicestoretype');
		}
		}else if($ButtonType==1){
		foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['customername']=$val['CustomerName'];
		$info['model'][$row]['loginname']=$val['LoginName'];
		$info['model'][$row]['cash_fee']=fee2yuan($val['Cash_fee']);
		$info['model'][$row]['fee']=fee2yuan($val['fee']);
		$info['model'][$row]['cash_fee_type']=$val['Cash_fee_type'];
		$info['model'][$row]['tradecount']=$val['Tradecount'];
		$info['model'][$row]['displayname']=$val['DisplayName'];
		}
		$info['totalCount'] =$list['totalCount'];
		$info['ButtonType'] =$ButtonType;
		if(session(flag)==1){
		$list['flag']= session('servicestoretype');
		}
		
		}
        $this->ajaxReturn($info);
	
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function platform_order_search(){
		R("Base/getMenu");
        $this->display();
    }

	public function platformsearch(){//平台交易订单查询


		$out_trade_no = $_POST['out_trade_no'];
		$transactionid = $_POST['transactionid'];
		$data = array(
			"out_trade_no"=>$out_trade_no, 
			"Transaction_id"=>$transactionid, 
			"SystemUserSysNo"=>session('SysNO')
		);
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request(C('SERVER_HOST')."Payment/Payments/QueryWxOrder",$data,$head);
		$list = json_decode($list,true);

		$info['out_trade_no']= $list['Data']['WxPayData']['m_values']['out_trade_no'];//订单号

		$trade_state= $list['Data']['WxPayData']['m_values']['trade_state'];//订单状态


		switch ($trade_state){
		case SUCCESS:
		  $status = "支付成功";
		  break;  
		case REFUND:
		  $status = "转入退款";
		  break;
		case NOTPAY:
		  $status = "未支付";
		  break;
		case CLOSED:
		  $status = "已关闭";
		  break;
		case REVOKED:
		  $status = "已撤销（刷卡支付）";
		  break;
		case USERPAYING:
		  $status = "用户支付中";
		  break;
		case PAYERROR:
		  $status = "支付失败";
		  break;
		
		}
		$info['trade_state'] = $status;
		$info['total_fee']= fee2yuan($list['Data']['WxPayData']['m_values']['total_fee']);//总额
		if($list['Data']['WxPayData']['m_values']['time_end']){
		$info['time']= date("Y-m-d H:i:s",strtotime( $list['Data']['WxPayData']['m_values']['time_end']));//时间
		}else{
		$info['time']="无";
		}
        $this->ajaxReturn( $info);

	}

	public function order_search_alipay(){
		R("Base/getMenu");
        $this->display();
    }

	public function ali_search(){
		$out_trade_no = $_POST['out_trade_no'];
		$transactionid = $_POST['transactionid'];
		$data = array(
			"out_trade_no"=>$out_trade_no,
			"Transaction_id"=>$transactionid,
			"SystemUserSysNo"=>session('SysNO')

		);
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );

        $list = http_request(C('SERVER_HOST')."IPP3AliPay/AliPayquery",$data,$head);
		$list = json_decode($list,true);

		$list = json_decode( $list['Data']['WxPayData'],true);
		$info['out_trade_no'] = $list['alipay_trade_query_response']['out_trade_no'];
		$info['amount'] = $list['alipay_trade_query_response']['total_amount'];
		$info['send_pay_date'] = $list['alipay_trade_query_response']['send_pay_date'];
		$trade_status = $list['alipay_trade_query_response']['trade_status'];

		switch ($trade_status){
		case WAIT_BUYER_PAY:
		  $status = "交易创建，等待买家付款";
		  break;
		case TRADE_CLOSED:
		  $status = "未付款交易超时关闭，或支付完成后全额退款";
		  break;
		case TRADE_SUCCESS:
		  $status = "交易支付成功";
		  break;
		case TRADE_FINISHED:
		  $status = "交易结束，不可退款";
		  break;
		}
		$info['trade_status'] = $status;
		//var_dump($list);
		$this-> ajaxReturn($info);
	}




	
	

	public function SupplyOrder(){
		$out_trade_no = trim($_POST['out_trade_no']);
		$trade_status = $this -> AliQueryOrder($out_trade_no);
		$trade_count = $this -> StaffQueryOrder($out_trade_no);
		if($trade_count==0){

			if($trade_status=='TRADE_SUCCESS'){

				$ActStatus = $this ->AddAliOrder($out_trade_no);
					if($ActStatus){
						$Description = "补单成功!";
					}else{
						$Description = "补单失败!";
					}

			}
		}else{
			$Description = "订单已存在,请勿重复补单!";
		}
		
	
		$this->ajaxReturn($Description);
	
	
	
	
	}
	private function AliQueryOrder($out_trade_no){
	
		$data = array(
			"out_trade_no"=>$out_trade_no,
			"SystemUserSysNo"=>session('SysNO')

		);
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request(C('SERVER_HOST')."IPP3AliPay/AliPayquery",$data,$head);
		$list = json_decode($list,true);
		$list = json_decode( $list['Data']['WxPayData'],true);
		$trade_status = $list['alipay_trade_query_response']['trade_status'];
		return $trade_status;
	}

	private function StaffQueryOrder($out_trade_no){
	
		$data = array(
			"out_trade_no"=>$out_trade_no,
			"SystemUserSysNo"=>session('SysNO')

		);
		$data['PagingInfo']['PageSize'] = 10;
		$data['PagingInfo']['PageNumber'] = 0;
		$url  = C('SERVER_HOST')."POS/POSOrderList"; 
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request($url,$data,$head);
		$list = json_decode($list,true);
		return $list['totalCount'];
	}
	private function AddAliOrder($out_trade_no){
	
		$data = array(
			"Out_trade_no"=>$out_trade_no,
			"SystemUserSysNo"=>session('SysNO')

		);
		$url  = C('SERVER_HOST')."IPP3AliPay/AliPaySupplyOrder"; 
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
        $list = http_request($url,$data,$head);
		$list = json_decode($list,true);
		return $list;
	}

	public function showtotalfee(){
		$Time_Start =empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end   =empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$out_trade_no = I('out_trade_no',"");
		
		$SystemUserSysNo = I('SystemUserSysNo',"");
		$PageNumber = $_POST['PageNumber'];
		$PageSize = $_POST['PageSize'];
		$CustomerNames = $_POST['CustomerNames'];
		$Customer = $_POST['Customer'];
		$Ordertype = $_POST['Ordertype'];
		$ButtonType = $_POST['ButtonType'];
		$data = array(
			"Time_Start"=>$Time_Start,
			"Time_end"=>$Time_end,
			"Out_trade_no"=>$out_trade_no,
			"CustomerName"=>$CustomerNames,
			"Pay_Type"=>$Ordertype,
			"Customer"=>$Customer

		);
		$flag=session('flag');
		if(session('data')['CustomersType']==0&$flag==0){//服务商登陆 传入主键
		$data['CustomersTopSysNo']=session('SysNO');
		}
		if(session('data')['CustomersType']==1&$flag==0){//商户登陆 传入主键
		$data['CustomerSysNo']=session('SysNO');
		}
		if(session('servicestoretype')==0&$flag==1){
		$data['CustomersTopSysNo']=session('servicestoreno');
		$data['SystemUserTopSysNo']=session('SysNO');
		}
		if(session('data')['CustomersType']==1&$flag==0){
			$url  = C('SERVER_HOST')."IPP3Order/IPP3Order_Group_Shop"; 
			$totalcount = $this->http($data,$url);
			$info['total']['Total_fee']=fee2yuan($totalcount['Total_fee']);
			$info['total']['Cash_fee']=fee2yuan($totalcount['Cash_fee']);
			$info['total']['Tradecount']=$totalcount['Tradecount'];
		}else if(session('data')['CustomersType']==0&$flag==0){
			$url  = C('SERVER_HOST')."IPP3Order/IPP3Order_Group_Customer";
			$totalcount = $this->http($data,$url);
			$info['total']['Total_fee']=fee2yuan($totalcount['Total_fee']);
			$info['total']['Cash_fee']=fee2yuan($totalcount['Cash_fee']);
			$info['total']['Tradecount']=$totalcount['Tradecount'];
//			var_dump($info);exit;
		}else if(session('servicestoretype')==0&session('flag')==1){
			$url  = C('SERVER_HOST')."IPP3Order/IPP3Order_Group_CustomerUser";
			$totalcount = $this->http($data,$url);
			$info['total']['Total_fee']=fee2yuan($totalcount['Total_fee']);
			$info['total']['Cash_fee']=fee2yuan($totalcount['Cash_fee']);
			$info['total']['Tradecount']=$totalcount['Tradecount'];
		
		}
		$this->ajaxreturn($info);
	
	
	
	}

	private function http($data,$url){
	
		$data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
		
		$list = http_request($url,$data,$head);

		$list = json_decode($list,true);

		return $list;
	
	
	}




















}