<?php
namespace Home\Controller;
//use Think\Controller;
use Common\Compose\Base;

class OrderFundController extends Base {

    public function orderfund(){
		R("Base/getMenu");
        $this->display();
    }
	
	public function order_fund(){//交易订单查询
	
		$Time_Start =empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end   =empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$out_trade_no = I('out_trade_no',"");
		
		$SysNo = I('SysNo',"");
		$TopSysNo = I('TopSysNo',"");
		$Top = I('Top',"");
		$CustomerSysNo = I('CustomerSysNo',"");
		$PageNumber = $_POST['PageNumber'];
		$PageSize = $_POST['PageSize'];
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

		if($ButtonType ==0){
			if(session('data')['CustomersType']==0&$flag==0){//服务商登陆 
				if($SysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopSPRate";
					$data['CustomerSysNo'] = $SysNo;
				}
				if($TopSysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListCustomerUserRateSP";
					$data['SystemUserTopSysNo'] = $TopSysNo;
					$data['CustomersTopSysNo'] = session('SysNO');
				
				}
				if($CustomerSysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListCustomerUserRateSP";
					$data['SystemUserTopSysNo'] = $CustomerSysNo;
					$data['CustomersTopSysNo'] = session('SysNO');
				
				}

			}else if(session('data')['CustomersType']==1&$flag==0){//商户
					if($TopSysNo!='null'){
						$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopUserRateSP";
						$data['SystemUserSysNo'] = $TopSysNo;
					}else{
						$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopSPRate";
						$data['CustomerSysNo'] = session('SysNO');
					}

			}else if($type==0&$flag==1){//服务商员工
				if($SysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopSPRate";
					$data['CustomerSysNo'] = $SysNo;
				}else{
					$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListCustomerUserRateSP";
					$data['SystemUserTopSysNo'] = session('SysNO');
					$data['CustomersTopSysNo'] = session('servicestoreno');
				}
				
			}else if($type==1&$flag==1){//商户员工

				$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListShopUserRateSP";
				$data['SystemUserSysNo'] = session('SysNO');
				
			}
			

		}else if($ButtonType==1){
				
			if(session('data')['CustomersType']==0&$flag==0){//服务商登陆
				if($SysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopSPRate";
					$data['CustomerSysNo'] = $SysNo;
					
				}
				if($TopSysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
					$data['SystemUserTopSysNo'] = $TopSysNo;
					$data['CustomersTopSysNo'] = session('SysNO');
				
				}
				if($CustomerSysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
					$data['SystemUserTopSysNo'] = $CustomerSysNo;
					$data['CustomersTopSysNo'] = session('SysNO');
				
				}

			}else if(session('data')['CustomersType']==1&$flag==0){//商户
				if($TopSysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopUserRateSP";
					$data['SystemUserSysNo'] = $TopSysNo;
					
				}else{
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopSPRate";
					$data['CustomerSysNo'] = session('SysNO');
					 
				}
			}else if($type==0&$flag==1){//服务商员工
				if($SysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopSPRate";
					$data['CustomerSysNo'] = $SysNo;
				}else{
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
					$data['SystemUserTopSysNo'] = session('SysNO');
					$data['CustomersTopSysNo'] = session('servicestoreno');
					 
				}
				
			}else if($type==1&$flag==1){//商户员工
				if($SysNo!='null'){
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopUserRateSP";
					$data['SystemUserSysNo'] = $SysNo;
				}else{
					$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Fund_ShopUserRateSP";
					$data['SystemUserSysNo'] = session('SysNO');
					 
				}
				
			}
		}
		$data['PagingInfo']['PageSize'] = $PageSize;
		$data['PagingInfo']['PageNumber'] = $PageNumber;
        $data = json_encode( $data );
//		var_dump($data)."\n";echo $url;exit;
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:" . strlen( $data ),
            "X-Ywkj-Authentication:" . strlen( $data )
        );
		$list = http_request($url,$data,$head);
		$list = json_decode($list,true);
//		var_dump($list);exit;
		if($ButtonType==0){
		foreach ($list['model'] as $row=>$val){
		$info['model'][$row]['SysNo']=$val['SysNo'];
		$info['model'][$row]['loginname']=$val['LoginName'];
		$info['model'][$row]['displayname']=$val['DisplayName'];
		$info['model'][$row]['Out_trade_no']=$val['Out_trade_no'];
		$info['model'][$row]['Pay_Type']=$val['Pay_Type'];
			if($TopSysNo!='null'){
			if(session('data')['CustomersType']==0&$flag==0){
			$info['model'][$row]['rate']=$val['UserRate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($type==1&$flag==1){
			$info['model'][$row]['rate']=$val['Rate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if(session('data')['CustomersType']==1&$flag==0){
			$info['model'][$row]['rate']=$val['Rate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}
			}else if($_POST['Customer']==""&$TopSysNo=="null"&$Top=='null'){
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if($SysNo!='null'){
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if($Top!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($Customer!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($CustomerSysNo!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else{
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}
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
		$info['model'][$row]['cash_fee']=fee2yuan($val['Cash_fee']);
		$info['model'][$row]['fee']=fee2yuan($val['fee']);
		$info['model'][$row]['cash_fee_type']=$val['Cash_fee_type'];
			if($TopSysNo!='null'){
			if(session('data')['CustomersType']==0&$flag==0){
			$info['model'][$row]['rate']=$val['UserRate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($type==1&$flag==1){
			$info['model'][$row]['rate']=$val['Rate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if(session('data')['CustomersType']==1&$flag==0){
			$info['model'][$row]['rate']=$val['Rate'];
			$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}
			}else if($_POST['Customer']==""&$TopSysNo=="null"&$Top=='null'){
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if($SysNo!='null'){
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}else if($Top!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($Customer!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else if($CustomerSysNo!='null'){
				$info['model'][$row]['rate']=$val['UserRate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['UserRate_fee']);
			}else{
				$info['model'][$row]['rate']=$val['Rate'];
				$info['model'][$row]['ratefee']=fee2yuan($val['Rate_fee']);
			}
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





















}