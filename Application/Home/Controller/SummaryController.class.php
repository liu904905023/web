<?php
namespace Home\Controller;
//use Think\Controller;
use Common\Compose\Base;

class SummaryController extends Base {

    public function summary_search(){
		R("Base/getMenu");
        $this->display();
    }
	public function summarysearch(){

		$Time_Start 	= empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";
		$Time_end   	= empty($_POST['Time_end'])? $_POST['Time_end']:$_POST['Time_end']." 23:59:59";
		$staffloginname = I('staffloginname',"");
		$realname 		= I('realname',"");
		$phone 			= I('phone',"");
		$PageNumber 	= I('PageNumber',"");
		$PageSize 		= I('PageSize',"");
		$Ordertype 		= I('Ordertype',"");
		$serviceno 		= session('SysNO');
		$servicestaffno = "";

		$data = array(
			"CustomersTopSysNo"	=> $serviceno,
			"SystemUserTopSysNo"=> $servicestaffno,
			"LoginName"			=> $staffloginname,
			"DisplayName"		=> $realname,
			"PhoneNumber"		=> $phone,
			"Pay_Type"			=> $Ordertype,
			"Time_Start"		=> $Time_Start,
			"Time_end"			=> $Time_end,

		);
		$data['PagingInfo']['PageSize'] = $PageSize;
		$data['PagingInfo']['PageNumber'] = $PageNumber;
		$data = json_encode( $data );
		// var_dump($data);exit;

       	$head = array(
           "Content-Type:application/json;charset=UTF-8",
           "Content-length:" . strlen( $data ),
           "X-Ywkj-Authentication:" . strlen( $data )
       	);
		$url = C('SERVER_HOST')."IPP3Order/IPP3Order_Group_CustomerUserList";
		$list = http_request($url,$data,$head);

		$list = json_decode($list,true);
		// var_dump($list);exit;
		foreach ($list['model'] as $row=>$val){
			$info['model'][$row]['systemusertopsysno']=$val['SystemUserTopSysNo'];
			$info['model'][$row]['loginname']=$val['LoginName'];
			$info['model'][$row]['displayname']=$val['DisplayName'];
			$info['model'][$row]['phonenumber']=$val['PhoneNumber'];
			$info['model'][$row]['totalfee']=fee2yuan($val['Total_fee']);
			$info['model'][$row]['refundfee']=fee2yuan($val['refund_fee']);
			$info['model'][$row]['tradecount']=$val['Tradecount'];
			$info['model'][$row]['fee']=fee2yuan($val['Fee']);
			// $info['model'][$row]['paytype']=$val['Pay_Type'];


		}
		// var_dump($info);exit;

		$info['totalCount'] =$list['totalCount'];
        $this->ajaxReturn($info);
	}


}