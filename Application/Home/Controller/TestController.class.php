<?php

namespace Home\Controller;

use Think\Controller;

class TestController extends Controller{
	
	public function index2(){
		
		$SysNo = I('SysNo');
		Vendor('WxpayV3.WxAccount');
		$Customer = SESSION('SysNO');
		$object = new \WxAccount();
		$str = "CusetomeSysNo_".$Customer."_SystemSysNo_".$SysNo;
				if(I('DeletClear')==1){
					$str = "CusetomeSysNo_".$Customer."_SystemSysNo_".$SysNo."_DeletClear_1";
				}
		$coco =$this->QueryToken($Customer);
		
		$result = $this->getTicket( $str ,$coco);
		
		$info['ticket']   = $result['ticket'];
        $info['codeurl'] = $result['codeurl'];
		$codeurl          = urlencode( $info['codeurl'] );
		
		//$data['url']=SITE_URL."/index.php/Qrcode/qrcode/qrcode?url=".$codeurl;
		$this -> ajaxreturn($codeurl);
		//$this ->display("index");
	}

	public function index3(){
		
		$SysNo = SESSION('SysNO');
		Vendor('WxpayV3.WxAccount');
		$Customer = session('servicestoreno');
		$str = "CusetomeSysNo_".$Customer."_SystemSysNo_".$SysNo;
				if(I('DeletClear')==1){
					$str = "CusetomeSysNo_".$Customer."_SystemSysNo_".$SysNo."_DeletClear_1";
				}
		$coco =$this->QueryToken($Customer);
		
		$result = $this->getTicket( $str ,$coco);
		
		$info['ticket']   = $result['ticket'];
        $info['codeurl'] = $result['codeurl'];
		$codeurl          = urlencode( $info['codeurl'] );
		
		//$data['url']=SITE_URL."/index.php/Qrcode/qrcode/qrcode?url=".$codeurl;
		$this -> ajaxreturn($codeurl);
		//$this ->display("index");
	}


	public function getTicket( $mch_id = NULL,$token ){
	
        if( empty( $mch_id ) ){
            return FALSE;
            exit();
        }

        $account['appid']  = 'wx261671a6d70c4db5';
        $account['secret'] = 'b52734baa5c3093a35b4f18b05455f21';
        $wx                = new \WxAccount( $account );

        $barcode['action_info']['scene']['scene_str'] = $mch_id;
        $barcode['action_name']                       = 'QR_LIMIT_STR_SCENE';
		
        $res = $wx->barCodeCreateFixed( $barcode,$token );


        $data['ticket']  = $res['ticket'];
        $data['codeurl'] = $res['url'];

        return $data;
    }
   public function index(){

        if( isset( $_GET['echostr'] ) ){
            $this->valid();
        }else{
            $this->responseMsg();
        }
    }

    public function valid(){
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if( $this->checkSignature() ){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	

        //extract post data
        if( !empty( $postStr ) ){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
              the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader( true );
            $postObj = simplexml_load_string( $postStr, 'SimpleXMLElement',
                LIBXML_NOCDATA );
            $data['fromUsername'] = $postObj->FromUserName;
            $data['toUsername']   = $postObj->ToUserName;
            switch( $postObj->MsgType ){
                case "text" :
                    $data['content'] = trim($postObj->Content);
				if($data['content']=="绑定"){
					$content = array();
					$content[] = array("Title"=>"中信－员工绑定", "Description"=>"", "PicUrl"=>"http://web.yunlaohu.cn/WxpayAPI/ok.jpg", "Url" =>"http://wft.yunlaohu.cn/index.php/Binding/index?openid=".$postObj->FromUserName);
                    $this->transmitNews( $postObj,$content );
				}else{
					$this->reText( $data );
				}
                    break;
					
				
                case "event":
                    switch( $postObj->Event ){
                        case "subscribe":
							$arr = explode( '_', $postObj->EventKey );
							$CustomerServiceSysNO		  = $arr[2];
							$SystemSysNO				  = $arr[4];
							$Customer = GetCustomerServiceSysNo($SystemSysNO);
							$SystemName = QueryStaffInfo($SystemSysNO);
//							$CustomerName				  = $arr[5];
//							$json = '{"str":"'.$CustomerName.'"}';
//							$info = json_decode($json,true);
//							$SystemName				  = $arr[6];2016/7/14
							$Openid = $postObj->FromUserName;
							//查询商户号是否存在，如果商户号存在则添加收银员
							if($SystemSysNO){
							$arrData = $this->CheckClear($SystemSysNO,$Openid);
							
							if($arrData){

								$data['content'] = "您已经是核销员,请勿重复填加!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
//								$data['content'] = "您已经是核销员,请勿重复填加!\n商户名称: ".$info['str']."\n员工名称: ".$SystemName;
							}else{
								$res = $this->AddClear($CustomerServiceSysNO,$Openid,$SystemSysNO);
								if($res['Code']==0){
								$data['content'] = "核销员填加成功!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								}else{
								$data['content'] = "核销员填加失败!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								}
							}
							$this->reText( $data );
							}
                            break;
                        case "unsubscribe":
                            break;
                        case "SCAN":

                            $arr = explode( '_', $postObj->EventKey );
//							\Think\Log::record("0:".$arr[0]." --1:".$arr[1]." --2:".$arr[2]." --3:".$arr[3]." --4:".$arr[4]." --5:".$arr[5]." --6:".$arr[6]);
							$CustomerServiceSysNO = $arr[1];
							$SystemSysNO = $arr[3];
							$Customer = GetCustomerServiceSysNo($SystemSysNO);
							$SystemName = QueryStaffInfo($SystemSysNO);
							$DeletFlag = $arr[5];
							$Openid = $postObj->FromUserName;


								if($DeletFlag==1){
								
								$ResultCode = $this ->DeleteClear($Openid,$SystemSysNO);
								if((string)$ResultCode=="true"){
								
								$data['content']="核销员删除成功!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								
								}else{
								
								$data['content']="核销员已删除!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								}
								
								$this->reText( $data );
								exit;
								}
							
							
							
							//查询商户号是否存在，如果商户号存在则添加收银员
							$arrData = $this->CheckClear($SystemSysNO,$Openid);
							
							if(count($arrData)>0){
								$data['content'] = "您已经是核销员,请勿重复填加!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
//								$data['content'] = "您已经是核销员,请勿重复填加!";
							}else{
								/*------填加核销员-------*/

								$res = $this->AddClear($CustomerServiceSysNO,$Openid,$SystemSysNO);
								if($res['Code']==0){
								$data['content'] = "核销员填加成功!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								}else{
								$data['content'] = "核销员填加失败!\n商户名称: ".$Customer."\n员工姓名: ".$SystemName;
								}
							}
//                            $this->reText( $data );
                            $this->reText( $data );
                            break;
                        case "LOCATION":
                            break;
                        case "CLICK":
                            if( $postObj->EventKey == 'binding' ){
                                $content = array();
								$content[] = array("Title"=>"云老虎通道", "Description"=>"", "PicUrl"=>"http://web.yunlaohu.cn/WxpayAPI/ok1.jpg", "Url" =>"http://mobile.yunlaohu.cn/index.php/Binding/index?openid=".$postObj->FromUserName);
								$this->transmitNews( $postObj,$content );
                            }
							if( $postObj->EventKey == 'zhongxin' ){
                                $content = array();
								$content[] = array("Title"=>"中信银行通道", "Description"=>"", "PicUrl"=>"http://web.yunlaohu.cn/WxpayAPI/ok1.jpg", "Url" =>"http://wft.yunlaohu.cn/index.php/Binding/index?openid=".$postObj->FromUserName);
								$this->transmitNews( $postObj,$content );
                            }
							
							
                            break;
                        case "VIEW":
                            break;
                    }
                    break;
                default:
            }
        }else{
            echo "";
            exit;
        }
    }

    private function reText( $data ){
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        $msgType = "text";
        $contentStr = $data['content'];
        $resultStr = sprintf( $textTpl, $data['fromUsername'],$data['toUsername'], NOW_TIME, $msgType, $contentStr);
        echo $resultStr;
    }

  

    private function checkSignature(){
        // you must define TOKEN by yourself
       /* if( !defined( "TOKEN" ) ){
            throw new Exception( 'TOKEN is not defined!' );
        }*/

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = "D8g14207Q2q83Z2go3B1Bf332GgOo8Q3";
        $tmpArr = array( $token,$timestamp,$nonce );
        // use SORT_STRING rule
        sort( $tmpArr, SORT_STRING );
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

	private function CheckClear($SystemUserSysNO,$Openid){
		$arr1  = array(
		 "SystemUserSysNO" => $SystemUserSysNO,
		 "Openid" => (string)$Openid,
			
		);

		$url = C( 'SERVER_HOST' ) . "IPP3Order/IPP3TemplateMessageList";
		$info = json_encode( $arr1 );
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $info )
		);
		$res  = http_request( $url, $info, $head );
		$arrData = json_decode($res,TRUE);

		return $arrData;
	
	
	}

	private function AddClear($CustomerServiceSysNO,$Openid,$SystemSysNO){
	
		$clearinfo = array(
			
		"CustomerServiceSysNO" => $CustomerServiceSysNO,
		"Openid" => (string)$Openid,
		"SystemUserSysNO" => $SystemSysNO,
		
		);
		$url = C( 'SERVER_HOST' ) . "IPP3Order/IPP3TemplateMessageInsert";
		$info = json_encode( $clearinfo );
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $info )
		);
		$res  = http_request( $url, $info, $head );

		return $res;
	
	}

	private function DeleteClear($openid,$SystemSysNO){

		$data['Openid'] = (string)$openid;
		$data['SystemUserSysNO'] = $SystemSysNO;
		$url = C( 'SERVER_HOST' ) . "IPP3Order/IPP3TemplateMessageDel";
		$info = json_encode( $data );
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $info )
		);
		$res  = http_request( $url, $info, $head );

		return $res;
	
	
	}

	private function QueryToken($CustomerServiceSysNO){
	
		$data['CustomerServiceSysNO']=$CustomerServiceSysNO;
		$url = C( 'SERVER_HOST' ) . "Payment/Payments/GetToken";
		$info = json_encode( $data );
		$head = array(
			"Content-Type:application/json;charset=UTF-8",
			"Content-length:" . strlen( $info )
		);
		$res = http_request( $url, $info, $head );
		$res = json_decode($res,true); 

		return $res['Description'];
	
	
	}
	//回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
		</item>
		";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>%s</ArticleCount>
		<Articles>
		$item_str</Articles>
		</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        echo $result;
    }



}
