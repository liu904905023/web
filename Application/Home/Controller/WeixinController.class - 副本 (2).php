<?php
// +----------------------------------------------------------------------
// | 设计开发：Webster  Tel:17095135002 邮箱：312549912@qq.com
// | 此版本为微信官方最新微信支付V3版本
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
class WeixinController extends Controller {
	protected function _initialize(){
		//全局引入微信支付类

    	Vendor('WxpayV3.WxPayPubHelper');
		GLOBAL $appid;
	}
	
	public function weixin(){
		if(IS_POST ){
			echo $appid;exit;
			//①、获取用户openid
		$fee = yuan2fee($_POST['money']);
		$openId = $GLOBALS['openId'];
		$systemUserSysNo=(string)cookie('systemUserSysNo');
		$Customname = GetCustomerServiceSysNo($systemUserSysNo);
		$tools = new \JsApiPay();
		
		//$openId = $tools->GetOpenid();

		//②、统一下单
		$input = new \WxPayUnifiedOrder();

		$input->SetBody("$Customname");
		$input->SetAttach("$systemUserSysNo");
		$input->SetOut_trade_no(cookie('mchid').date("YmdHis").rand(100,999));
		$input->SetTotal_fee("$fee");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://payapi.yunlaohu.cn/IPP3Order/Notify");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		
		$order = \WxPayApi::unifiedOrder($input);
		
		//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		$jsApiParameters = $tools->GetJsApiParameters($order);
		//获取共享收货地址js函数参数
		//$editAddress = $tools->GetEditAddressParameters();
		$this->assign('jsApiParameters',$jsApiParameters);

		
		}else{
			$tools = new \JsApiPay();
			$systemUserSysNo =2;
			$data['systemUserSysNo'] =$systemUserSysNo;
			$url  = C('SERVER_HOST')."IPP3Customers/IPP3WxconfigBySUsysNo";
			$data = json_encode( $data );
			$head = array(
				"Content-Type:application/json;charset=UTF-8",
				"Content-length:".strlen( $data ),
				//"X-Ywkj-Authentication:" . strlen( $data ),
			);
			$Customer = GetCustomerServiceSysNo($systemUserSysNo);
			$systemUserName= QueryStaffInfo($systemUserSysNo);
			$list = http_request( $url, $data, $head );
			$list = json_decode( $list ,true);
	//		var_dump($list);exit;
			echo $GLOBALS['APPID']=$list['APPID'];
			$GLOBALS['NCHID']=$list['NCHID'];
			$GLOBALS['KEY']=$list['KEY'];
			$GLOBALS['APPSECRET']=$list['APPSECRET'];
			$GLOBALS['sub_mch_id']=$list['sub_mch_id'];
			$GLOBALS['systemUserSysNo']=$systemUserSysNo;
			$GLOBALS['openId']=$tools->GetOpenid();
		
			//\Think\Log::record("日志".$GLOBALS['openId'] );
			$this->assign('Customer',$Customer);
			$this->assign('systemUserSysNo',$systemUserName);
		
		
		}
		
		$this->display();	
	
	}
	//JSAPI支付 V3版本微信支付
    public function jsapi(){
    	//①、获取用户openid
		$fee = yuan2fee($_POST['money']);
		$openId = $GLOBALS['openId'];
		$systemUserSysNo=(string)cookie('systemUserSysNo');
		$Customname = GetCustomerServiceSysNo($systemUserSysNo);
		$tools = new \JsApiPay();
		
		//$openId = $tools->GetOpenid();

		//②、统一下单
		$input = new \WxPayUnifiedOrder();

		$input->SetBody("$Customname");
		$input->SetAttach("$systemUserSysNo");
		$input->SetOut_trade_no(cookie('mchid').date("YmdHis").rand(100,999));
		$input->SetTotal_fee("$fee");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://payapi.yunlaohu.cn/IPP3Order/Notify");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		
		$order = \WxPayApi::unifiedOrder($input);
		
		//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		$jsApiParameters = $tools->GetJsApiParameters($order);
		//获取共享收货地址js函数参数
		//$editAddress = $tools->GetEditAddressParameters();
		$this->assign('jsApiParameters',$jsApiParameters);
		$this->display();	
    }
    //扫码支付 模式一 模式二 V3版本微信支付
    public function native(){
		R("Base/getMenu");
		$systemUserSysNo =$_POST['syskeyno'];
		$fee = yuan2fee($_POST['payfee']);
		$data['systemUserSysNo'] =$systemUserSysNo;
		$url  = C('SERVER_HOST')."IPP3Customers/IPP3WxconfigBySUsysNo";
        $data = json_encode( $data );
        $head = array(
            "Content-Type:application/json;charset=UTF-8",
            "Content-length:".strlen( $data ),
            //"X-Ywkj-Authentication:" . strlen( $data ),
        );

        $list = http_request( $url, $data, $head );
        $list = json_decode( $list ,true);
		cookie('appid',$list['APPID']);
		cookie('mchid',$list['NCHID']);
		cookie('key',$list['KEY']);
		cookie('appsecret',$list['APPSECRET']);
		cookie('sub_mch_id',$list['sub_mch_id']);
		cookie('systemUserSysNo',$systemUserSysNo);
		$systemUserSysNo=(string)cookie('systemUserSysNo');
    	//模式一
    	/**
    	 * 流程：
    	 * 1、组装包含支付信息的url，生成二维码
    	 * 2、用户扫描二维码，进行支付
    	 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
    	 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
    	 * 5、支付完成之后，微信服务器会通知支付成功
    	 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
    	 */
    	$notify = new \NativePay();
    	
    	//模式二
    	/**
    	 * 流程：
    	 * 1、调用统一下单，取得code_url，生成二维码
    	 * 2、用户扫描二维码，进行支付
    	 * 3、支付完成之后，微信服务器会通知支付成功
    	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
    	*/
    	$input = new \WxPayUnifiedOrder();
    	$input->SetBody("test");
    	$input->SetAttach("$systemUserSysNo");
    	$input->SetOut_trade_no(cookie('mchid').date("YmdHis").rand(100,999));
    	$input->SetTotal_fee("$fee");
    	$input->SetTime_start(date("YmdHis"));
    	$input->SetTime_expire(date("YmdHis", time() + 600));
    	$input->SetGoods_tag("test");
    	$input->SetNotify_url("http://payapi.yunlaohu.cn/IPP3Order/Notify");
    	$input->SetTrade_type("NATIVE");
    	$input->SetProduct_id("123456789");
    	$result = $notify->GetPayUrl($input);
//		var_dump($result);
    	$url2 = $result["code_url"];
    	$this->assign('url2',$url2);//扫码支付二
    	$this->display();
    }
    //刷卡支付 V3版本微信支付
    public function micropay(){
    	if(isset($_REQUEST["auth_code"]) && $_REQUEST["auth_code"] != ""){
    		$auth_code = $_REQUEST["auth_code"];
    		$input = new \WxPayMicroPay();
    		$input->SetAuth_code($auth_code);
    		$input->SetBody("刷卡测试样例-支付");
    		$input->SetTotal_fee("1");
    		$input->SetOut_trade_no(cookie('mchid').date("YmdHis"));
    	
    		$microPay = new \MicroPay();
    		dump($microPay->pay($input));
    	}
    	$this->display();
    }
}