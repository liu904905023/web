<?php

namespace Home\Controller;

use Common\Compose\Base;



class DownloadController extends Base{





    //导出交易订单

    public function downloadorder()

    {



        $storename = $_POST['storename'];//商户名称

        $Time_Start =empty($_POST['dtp_input1'])? $_POST['dtp_input1']:$_POST['dtp_input1']." 00:00:00";//开始时间

        $Time_end   =empty($_POST['dtp_input2'])? $_POST['dtp_input2']:$_POST['dtp_input2']." 23:59:59";//截止时间

        $out_trade_no = $_POST['ordernum'];//订单号

        $Customer = $_POST['Customer'];//跳转页商户名称

        $SystemUserSysNo = $_POST['SystemUserSysNo'];//员工主键

        $Customer = $_POST['Customer'];//跳转页商户名称

        $ordertype = $_POST['Ordertype'];

        $CustomerNames = $_POST['CustomerNames'];

        $ButtonType = I('input_hidden',"");

        // var_dump($ButtonType);exit;



        // var_dump($Customer);exit();

        //$CustomerSysNo = empty($_POST['CustomerSysNo'])? session(data)['SysNo']:$_POST['CustomerSysNo'];

        $PageNumber = $_POST['PageNumber'];

        $PageSize = $_POST['PageSize'];

        $data = array(

            "Time_Start"=>$Time_Start,

            "Time_end"=>$Time_end,

            "Out_trade_no"=>$out_trade_no,

            "CustomerName"=>$CustomerNames,

            "Customer"=>$storename,

            "Pay_Type"=>$ordertype

        );



      // var_export($_SESSION);exit;

        $flag = session('flag');                            //0-服务商登陆  1-员工登陆

        $type = session(data)['CustomersType'];             //0-服务商  1-商户

        $servicestoretype = session('servicestoretype');    //0-服务商员工 1-商户员工

        // echo $servicestoretype;exit;

        //服务商登录

        if ($flag == 0) {

            //服务商

            if ($type == 0) {

                if ($Customer != "NaN") {

                    $data['Customer'] = $Customer;

                }

                else

                {

                    $data['Customer']=$_POST['storename'];

                }



            }

            //商户

            else if ($type == 1) {

                if ($SystemUserSysNo != "NaN") {

                    $data['SystemUserSysNo'] = $SystemUserSysNo;

                    // $data['CustomerSysNo']=session(SysNO);

                }

                else

                {

                    $data['CustomerSysNo']=session(SysNO);



                }

            }

        }

        //员工登录

        else if($flag == 1) {



            //服务商下员工

            if ($servicestoretype == 0) {

                if ($Customer != "NaN") {

                    $data['Customer'] = $Customer;

					$data['SystemUserTopSysNo']=session('SysNO');

					$data['CustomersTopSysNo'] =session('servicestoreno');

                }

                else

                {

                    // $data['Customer']=$_POST['storename'];

                    $data['SystemUserTopSysNo']=session('SysNO');

					$data['CustomersTopSysNo'] =session('servicestoreno');

                }

            }

            //商户下员工

            if ($servicestoretype == 1) {

                if ($SystemUserSysNo != "NaN") {

                    $data['SystemUserSysNo'] = $SystemUserSysNo;

                    // $data['CustomerSysNo']=session(SysNO);

                }

                else

                {

                    // $data['CustomerSysNo']=staffquerystore(session(SysNO));

                    $data['SystemUserSysNo']=session(SysNO);//20160726



                }

            }

        }



if (($ButtonType=="a")||($ButtonType=="")) {



        if(session('data')['CustomersType']==0&$flag==0){//服务商登陆 传入主键

        $data['CustomersTopSysNo']=session('SysNO');

            if($out_trade_no){

            $url  = C('SERVER_HOST')."POS/POSOrderList"; 

            }else{

            $url  = C('SERVER_HOST')."IPP3Order/IPP3OrderListSP "; 

            }

        }else{

        $url  = C('SERVER_HOST')."POS/POSOrderList"; 

        }





}else if($ButtonType=="b"){



    if(session('data')['CustomersType']==0&$flag==0) {//服务商登录

        // echo 服务商;exit();

        $data['CustomersTopSysNo']=session('SysNO');

    }

    if(session('data')['CustomersType']==1&$flag==0) {//商户登录

        // echo 商户;exit();

        $data['CustomerSysNo']= session('SysNO');

    }

    if($servicestoretype==0&$flag==1) {//服务商下员工

        // echo 服务商下员工;exit;

        $data['CustomersTopSysNo']=session('servicestoreno');

    }

    if($servicestoretype==1&$flag==1) {

       $data['SystemUserSysNo']=session('SysNO');

    }

	

//    if(session('data')['CustomersType']==1){

//		$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListcollect";				

//	}else {

		$url = C('SERVER_HOST')."IPP3Order/IPP3OrderListcollect";	

//	}

//	echo $url;

//	dump($data);

//	exit;

}







        $data['PagingInfo']['PageSize'] = 10000;

        $data['PagingInfo']['PageNumber'] = 0;

        $data = json_encode( $data );

         //        var_dump("<pre>");

//          var_dump($data);exit();

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            "X-Ywkj-Authentication:" . strlen( $data )

        );



        $list = http_request($url,$data,$head);

        $list = json_decode($list,true);

        // var_dump("<pre>");

//         dump($list);exit();

if (($ButtonType=="a")||($ButtonType=="")) {

    # code...



        foreach ($list['model'] as $row=>$val){

        $info[$row]['SysNo']=$val['SysNo'];

		$info[$row]['LoginName']=$val['LoginName'];

        $info[$row]['DisplayName']=$val['DisplayName'];

        $info[$row]['CustomerName']=$val['CustomerName'];

        $info[$row]['Out_trade_no']="'".$val['Out_trade_no'];

		if($val['Pay_Type']==102){

			$Pay_Type="微信";

		}else if($val['Pay_Type']==103){

			$Pay_Type="支付宝";

		}

        $info[$row]['Pay_Type']=$Pay_Type;

        $info[$row]['Total_fee']=fee2yuan($val['Total_fee']);

        $info[$row]['Cash_fee_type']="人民币";

        $info[$row]['Time_Start']=$val['Time_Start'];

        }



        foreach ($info as $field=>$v){

            if($field == 'SysNo'){

                $headArr[]='序号';

            }

            if($field == 'LoginName'){

                $headArr[]='登录名';

            }

            if($field == 'DisplayName'){

                $headArr[]='真实姓名';

            }

            if($field == 'CustomerName'){

                $headArr[]='商户名称';

            }

            if($field == 'Out_trade_no'){

                $headArr[]='订单号';

            }

            if($field == 'Pay_Type'){

                $headArr[]='交易类型';

            }

            if($field == 'Total_fee'){

                $headArr[]='交易金额';

            }

            if($field == 'Cash_fee_type'){

                $headArr[]='交易币种';

            }

            if($field == 'Time_Start'){

                $headArr[]='交易时间';

            }

        }



        $filename="交易订单报表";

    }else if ($ButtonType=="b") {

                    

            foreach ($list['model'] as $row=>$val){

				if($type==0&$flag==0){

						$info[$row]['CustomerName']=$val['CustomerName'];  

				}else if($type==1&$flag==0){

					$info[$row]['DisplayName']=$val['DisplayName'];

				}else if($servicestoretype==0&$flag==1){

						$info[$row]['CustomerName']=$val['CustomerName'];  

				}else if($servicestoretype==1&$flag==1){

					$info[$row]['DisplayName']=$val['DisplayName'];

				}

                $info[$row]['Cash_fee']=fee2yuan($val['Cash_fee']);         //交易金额

                $info[$row]['fee']=fee2yuan($val['fee']);                   //实际交易金额

                $info[$row]['Cash_fee_type']="人民币";                      //交易币种

                $info[$row]['Tradecount']=$val['Tradecount'];               //交易笔数

            }

            foreach ($info as $field=>$v){

				if($type==0&$flag==0){

					if($field == 'CustomerName'){

						$headArr[]='商户名称';

					}

				}else if($type==1&$flag==0){

					if($field == 'CustomerName'){

						$headArr[]='员工名称';

					}

				}else if($servicestoretype==0&$flag==1){

					if($field == 'CustomerName'){

						$headArr[]='商户名称';

					}

				}else if($servicestoretype==1&$flag==1){

					if($field == 'CustomerName'){

						$headArr[]='员工名称';

					}

				}



                if($field == 'Cash_fee'){

                    $headArr[]='交易金额';

                }

                if($field == 'fee'){

                    $headArr[]='实际交易金额';

                }

                if($field == 'Cash_fee_type'){

                    $headArr[]='交易币种';

                }

                if($field == 'Tradecount'){

                    $headArr[]='交易笔数';

                }

            }



            $filename="交易订单汇总报表";

    }

        $this->getExcel($filename,$headArr,$info);

    }







    //导出退款

    public function downloadrefund()

    {



        $Time_Start =empty($_POST['Time_Start'])? $_POST['Time_Start']:$_POST['Time_Start']." 00:00:00";//开始时间

        $Time_end   =empty($_POST['Time_End'])? $_POST['Time_End']:$_POST['Time_End']." 23:59:59";      //截止时间

        $out_trade_no = $_POST['ordernum'];                                                             //订单号



        $data = array(

            "Time_Start"=>$Time_Start,

            "Time_end"=>$Time_end,

            "Out_trade_no"=>$out_trade_no

        );

        $flag =  session('flag');//服务商商户0 或员工1

        if($flag ==1){

        $type =  session('servicestoretype');//员工的服务商的类型 0为服务  1为商户

        }

        if(session('data')['CustomersType']==0||$flag==0){//服务商或者服务商员工 必须填写商户名进行查询，不需要传递CustomerSysNo

        $data['Customer']=I('Customer');

        }

        if(session('data')['CustomersType']==1){

        $data['CustomerSysNo']=session('SysNO');

        }

        if($type==1){

        $data['SystemUserSysNo']=session('SysNO');

        }



        $data['PagingInfo']['PageSize'] = 100000;

        $data['PagingInfo']['PageNumber'] = 0;

        $data = json_encode( $data );

//		 var_dump($data);exit;



        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            "X-Ywkj-Authentication:" . strlen( $data )

        );



        $list = http_request(C('SERVER_HOST')."IPP3Order/IPP3OrderFundListSP",$data,$head);

        $list = json_decode($list,true);



        foreach ($list['model'] as $row=>$val){

            $info[$row]['SysNo']=$val['SysNo'];                    //序号

            // $info[$row]['CustomerName']=$val['CustomerName'];

            $info[$row]['Out_trade_no']="'".$val['Out_trade_no'];  //订单号

            if($val['Pay_Type']==102){

			$Pay_Type="微信";

			}else if($val['Pay_Type']==103){

				$Pay_Type="支付宝";

			}

			$info[$row]['Pay_Type']=$Pay_Type;             //交易类型

            $info[$row]['Cash_fee_type']="人民币";                 //交易币种

            $info[$row]['Time_Start']=$val['Time_Start'];          //交易时间

            $info[$row]['Total_fee']=fee2yuan($val['Total_fee']);  //订单金额

            $info[$row]['refund_fee']=fee2yuan($val['refund_fee']);//已退金额

            $info[$row]['fee']=fee2yuan($val['fee']);              //可退金额

            $info[$row]['refundCount']=$val['refundCount'];        //退款笔数

        }



        foreach ($info as $field=>$v){

            if($field == 'SysNo'){

                $headArr[]='序号';

            }

            // if($field == 'CustomerName'){

            //     $headArr[]='商户名称';

            // }

            if($field == 'Out_trade_no'){

                $headArr[]='订单号';

            }

            if($field == 'Pay_Type'){

                $headArr[]='交易类型';

            }

            if($field == 'Cash_fee_type'){

                $headArr[]='交易币种';

            }

            if($field == 'Time_Start'){

                $headArr[]='交易时间';

            }

            if($field == 'Total_fee'){

                $headArr[]='订单金额';

            }

            if($field == 'refund_fee'){

                $headArr[]='已退金额';

            }

            if($field == 'fee'){

                $headArr[]='可退金额';

            }

            if($field == 'refundCount'){

                $headArr[]='退款笔数';

            }

        }



        $filename="退款";

        $this->getExcel($filename,$headArr,$info);

    }

    //导出退款查询

    public function downloadrefundsearch(){

        $Time_Start = empty($_POST['sx_start1'])? $_POST['sx_start1']:$_POST['sx_start1']." 00:00:00";      //订单开始时间

        $Time_end = empty($_POST['sx_end1'])? $_POST['sx_end1']:$_POST['sx_end1']." 23:59:59";              //订单结束时间

        $Create_Time_Start =empty($_POST['Create_Time_Start'])? $_POST['Create_Time_Start']:$_POST['Create_Time_Start']." 00:00:00";//退款开始时间

        $Create_Time_end = empty($_POST['Create_Time_end'])? $_POST['Create_Time_end']:$_POST['Create_Time_end']." 23:59:59";       //退款结束时间

        $Out_trade_no = $_POST['Out_trade_no'];                                                             //订单号

        $Transaction_id =  $_POST['Transaction_id'];

        $CustomerNames =  $_POST['CustomerNames'];

        $storename =  $_POST['storename'];

        $Ordertype =  $_POST['Ordertype'];


        $data = array(

            "Transaction_id"=>$Transaction_id,

            "Out_trade_no"=>$Out_trade_no,

            "Time_Start"=>$Time_Start,

            "Time_end"=>$Time_end,

            "Create_Time_Start"=>$Create_Time_Start,

            "Create_Time_end"=>$Create_Time_end,

            "CustomerName"=>$CustomerNames,

            "Customer"=>$storename,

            "Pay_Type"=>$Ordertype




        );



        $flag =  session(flag);
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
        $data['PagingInfo']['PageSize'] = 100000;

        $data['PagingInfo']['PageNumber'] = 0;

        $data = json_encode($data);


        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            "X-Ywkj-Authentication:" . strlen( $data ),

        );



        $list = http_request(  C('SERVER_HOST')."IPP3Order/IPP3RMA_RequestSP",$data,$head);

        $list = json_decode($list,true);



        foreach ($list['model'] as $row=>$val){

        $info[$row]['LoginName']=$val['LoginName'];

        $info[$row]['DisplayName']=$val['DisplayName'];

        $info[$row]['Out_trade_no']="'".$val['Out_trade_no'];       //订单号

        $info[$row]['customername']=$val['CustomerName'];


        if($val['Pay_Type']==102){

			$Pay_Type="微信";

		}else if($val['Pay_Type']==103){

			$Pay_Type="支付宝";

		}

        $info[$row]['Pay_Type']=$Pay_Type;                   //交易类型

        $info[$row]['Total_fee']=fee2yuan($val['Total_fee']);       //交易金额

        $info[$row]['refund_fee']=fee2yuan($val['Refund_fee']);     //退款金额

        $info[$row]['Cash_fee_type']="人民币";                      //交易币种

        $info[$row]['Time_Start']=$val['Time_Start'];               //交易时间

        $info[$row]['CreateTime']= $val['CreateTime'];  //退款时间CreateTime




        }



        foreach ($info as $field=>$v){


            if($field == 'LoginName'){

                $headArr[]='登录名称';

            }
            if($field == 'DisplayName'){

                $headArr[]='真实姓名';

            }

            if($field == 'Out_trade_no'){

                $headArr[]='订单号';

            }
            if($field == 'CustomerName'){

                $headArr[]='商户名称';

            }

            if($field == 'Pay_Type'){

                $headArr[]='交易类型';

            }

            if($field == 'total_fee'){

                $headArr[]='交易金额';

            }
            if($field == 'refund_fee'){

                $headArr[]='退款金额';

            }

            if($field == 'Cash_fee_type'){

                $headArr[]='交易币种';

            }

            if($field == 'Time_Start'){

                $headArr[]='交易时间';

            }

            if($field == 'CreateTime'){

                $headArr[]='退款时间';

            }



            // if($field == 'Status'){

            //     $headArr[]='退款状态';

            // }

        }

        $filename="退款查询";

        $this->getExcel($filename,$headArr,$info);

    }



//服务商员工订单汇总

    public function downloadsummary(){

        $Time_Start     = empty($_POST['dtp_input1'])? $_POST['dtp_input1']:$_POST['dtp_input1']." 00:00:00";

        $Time_end       = empty($_POST['dtp_input2'])? $_POST['dtp_input2']:$_POST['dtp_input2']." 23:59:59";

        $staffloginname = I('staffloginname',"");

        $realname       = I('realname',"");

        $phone          = I('phone',"");

        $PageNumber     = I('PageNumber',"");

        $PageSize       = I('PageSize',"");

        $Ordertype      = $_POST['Ordertype'];

        $serviceno      = session('SysNO');

        $servicestaffno = "";



        $data = array(

            "CustomersTopSysNo" => $serviceno,

            "SystemUserTopSysNo"=> $servicestaffno,

            "LoginName"         => $staffloginname,

            "DisplayName"       => $realname,

            "PhoneNumber"       => $phone,

            "Pay_Type"          => $Ordertype,

            "Time_Start"        => $Time_Start,

            "Time_end"          => $Time_end,



        );

        $data['PagingInfo']['PageSize'] = 100000;

        $data['PagingInfo']['PageNumber'] = 0;

        $data = json_encode( $data );



        $head = array(

           "Content-Type:application/json;charset=UTF-8",

           "Content-length:" . strlen( $data ),

           "X-Ywkj-Authentication:" . strlen( $data )

        );

        $url = C('SERVER_HOST')."IPP3Order/IPP3Order_Group_CustomerUserList";

        $list = http_request($url,$data,$head);

        $list = json_decode($list,true);

        foreach ($list['model'] as $row=>$val){

            $info[$row]['loginname']=$val['LoginName'];

            $info[$row]['displayname']=$val['DisplayName'];

            $info[$row]['phonenumber']=$val['PhoneNumber'];

            $info[$row]['totalfee']=fee2yuan($val['Total_fee']);

            $info[$row]['refundfee']=fee2yuan($val['refund_fee']);

            $info[$row]['fee']=fee2yuan($val['Fee']);

        }


        foreach ($info as $field=>$v){

            if($field == 'LoginName'){

                $headArr[]='员工登录名';

            }

            if($field == 'DisplayName'){

                $headArr[]='真实姓名';

            }

            if($field == 'PhoneNumber'){

                $headArr[]='电话';

            }

            if($field == 'Total_fee'){

                $headArr[]='交易金额';

            }

            if($field == 'refund_fee'){

                $headArr[]='退款金额';

            }

            if($field == 'Fee'){

                $headArr[]='实际金额';

            }

        }

        $filename="服务商员工订单汇总";

        $this->getExcel($filename,$headArr,$info);

    }






//大学-交易订单查询
    public function OrderExtendListDownload(){

        $Time_Start     = empty($_POST['dtp_input1'])? $_POST['dtp_input1']:$_POST['dtp_input1']." 00:00:00";

        $Time_end       = empty($_POST['dtp_input2'])? $_POST['dtp_input2']:$_POST['dtp_input2']." 23:59:59";

        $Out_trade_no   = $_POST['ordernum'];

        $Ordertype      = $_POST['Ordertype'];

        $data = array(


            "Out_trade_no"   =>$Out_trade_no,

            "Time_Start"     =>$Time_Start,

            "Time_end"       =>$Time_end,

            "Pay_Type"       =>$Ordertype

        );

        // $flag = session(flag);//服务商商户0 或员工1

        // $type = 3;

        // if($flag == 1){

        //     $type = staffstoreorservice(session(SysNO));

        // }

        // if(session(data)['CustomersType'] == 0&$flag == 0){

        //     $data['CustomersTopSysNo'] = session(SysNO);

        // }

        // if(session(data)['CustomersType'] == 1&$flag == 0){

        //     $data['CustomerSysNo'] = session(SysNO);

        //     // $data['CustomerName']  = $_POST['CustomerNames'];

        // }

        // if($type == 1){

        //     $data['SystemUserSysNo'] = session(SysNO);

        // }
        $flag = session('flag');                            //0-服务商登陆  1-员工登陆

        // $type = session(data)['CustomersType'];             //0-服务商  1-商户

        $servicestoretype = session('servicestoretype');    //0-服务商员工 1-商户员工

        if(session('data')['CustomersType']==1&$flag==0) {//商户登录


            $data['CustomerSysNo']= session('SysNO');

        }

        if($servicestoretype==1&$flag==1) {//商户员工

           $data['SystemUserSysNo']=session('SysNO');

        }

        $url = C('SERVER_HOST')."IPP3Order/IPP3So_Master_ExtendList";


        $data['PagingInfo']['PageSize']   = 100000;

        $data['PagingInfo']['PageNumber'] = 0;

        $data = json_encode( $data );

        // var_dump($data);echo "\n".$url ;exit;

        $head = array(

            "Content-Type:application/json;charset=UTF-8",

            "Content-length:" . strlen( $data ),

            "X-Ywkj-Authentication:" . strlen( $data ),

        );

        $list = http_request($url,$data,$head);

        $list = json_decode($list,true);

//      var_dump($list);exit;

        foreach ($list['model'] as $row=>$val){

        // $info['model'][$row]['SysNo'] =$val['SysNo'];

        $info[$row]['customername']   =$val['CustomerName'];

        $info[$row]['Out_trade_no']   ="'".$val['Out_trade_no'];

        // $info[$row]['loginname']      =$val['LoginName'];

        // $info[$row]['displayname']    =$val['DisplayName'];

        // $info[$row]['CustomerName']   =$val['CustomerName'];

        if($val['Pay_Type']==102){

            $Pay_Type="微信";

        }else if($val['Pay_Type']==103){

            $Pay_Type="支付宝";

        }

        $info[$row]['Pay_Type']      =$Pay_Type;

        $info[$row]['Total_fee']     =fee2yuan($val['Total_fee']);

        $info[$row]['name']          =$val['Name'];

        $info[$row]['phonenumber']   =$val['PhoneNumber'];

        // $info[$row]['refund_fee'] =fee2yuan($val['Refund_fee']);

        $info[$row]['type']          =$val['Type'];

        $info[$row]['studentId']     =$val['StudentID'];

        $info[$row]['remarks']       =$val['Remarks'];

        $info[$row]['num']           =$val['Num'];

        $info[$row]['Cash_fee_type'] ="人民币";

        // $info[$row]['CreateTime'] =$val['CreateTime'];

        $info[$row]['Time_Start']    =$val['Time_Start'];

        }

        foreach ($info as $field=>$v){

            if($field == 'CustomerName'){

                $headArr[]='商户名称';

            }

            if($field == 'Out_trade_no'){

                $headArr[]='订单号';

            }

            if($field == 'Pay_Type'){

                $headArr[]='交易类型';

            }

            if($field == 'Total_fee'){

                $headArr[]='交易金额';

            }

            if($field == 'Name'){

                $headArr[]='姓名';

            }

            if($field == 'PhoneNumber'){

                $headArr[]='电话';

            }

            if($field == 'Type'){

                $headArr[]='宽带类型';

            }

            if($field == 'StudentID'){

                $headArr[]='学号';

            }

            if($field == 'Remarks'){

                $headArr[]='备注';

            }

            if($field == 'Num'){

                $headArr[]='数量';

            }

            if($field == 'Cash_fee_type'){

                $headArr[]='交易币种';

            }

            if($field == 'Time_Start'){

                $headArr[]='交易时间';

            }

        }

        $filename="大学-交易订单查询报表";

        $this->getExcel($filename,$headArr,$info);
    }



    public  function getExcel($fileName,$headArr,$info){

        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入

        import("Org.Util.PHPExcel");

        import("Org.Util.PHPExcel.Writer.Excel5");

        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y-m-d",time());

        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\

        $objPHPExcel = new \PHPExcel();

        $objProps = $objPHPExcel->getProperties();

        //设置表头

        $key = ord("A");

        //print_r($headArr);exit;

        foreach($headArr as $v){

            $colum = chr($key);

            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);

            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);

            $key += 1;

        }



        $column = 2;

        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach($info as $key => $rows){ //行写入

            $span = ord("A");

            foreach($rows as $keyName=>$value){// 列写入

                $j = chr($span);

                $objActSheet->setCellValue($j.$column, $value);

                $span++;

            }

            $column++;

        }

		// $objActSheet->setCellValue("A".$column,"总计:111!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");

        $fileName = iconv("utf-8", "gb2312", $fileName);

        //重命名表

        //$objPHPExcel->getActiveSheet()->setTitle('test');

        //设置活动单指数到第一个表,所以Excel打开这是第一个表

        $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();//清除缓冲区,避免乱码

        header('Content-Type: application/vnd.ms-excel');

        header("Content-Disposition: attachment;filename=\"$fileName\"");

        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save('php://output'); //文件通过浏览器下载



        exit;

    }









}