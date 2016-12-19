<?php
// +----------------------------------------------------------------------
// | 设计开发：Webster  Tel:17095135002 邮箱：312549912@qq.com
// | 此版本为微信官方最新微信支付V3版本
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
class WxController extends Controller {
	protected function _initialize(){
		//全局引入微信支付类
    	Vendor('WxpayV3.WxPayPubHelper');
	}
	//JSAPI支付 V3版本微信支付
    public function jsapi(){
    	//①、获取用户openid
		$tools = new \JsApiPay();
		$openId = $tools->GetOpenid();
		//②、统一下单
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		dump($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		
		//获取共享收货地址js函数参数
		$editAddress = $tools->GetEditAddressParameters();
		$this->assign('jsApiParameters',$jsApiParameters);
		$this->display();	
    }
    //扫码支付 模式一 模式二 V3版本微信支付
    public function native(){
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
    	$url1 = $notify->GetPrePayUrl("123456789");
    	
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
    	$input->SetAttach("test");
    	$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
    	$input->SetTotal_fee("1");
    	$input->SetTime_start(date("YmdHis"));
    	$input->SetTime_expire(date("YmdHis", time() + 600));
    	$input->SetGoods_tag("test");
    	$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
    	$input->SetTrade_type("NATIVE");
    	$input->SetProduct_id("123456789");
    	$result = $notify->GetPayUrl($input);
    	$url2 = $result["code_url"];
    	$this->assign('url1',$url1);//扫码支付一
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
    		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
    	
    		$microPay = new \MicroPay();
    		dump($microPay->pay($input));
    	}
    	$this->display();
    }
}