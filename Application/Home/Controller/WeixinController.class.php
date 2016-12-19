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

    	Vendor('Wxpay.WxPayPubHelper');
	}
	
	public function weixin(){
		if(IS_POST ){
		$fee = yuan2fee(I('money'));
		$Mobile = I('Mobile');
		$Product = I('Proudct');
		$Remark = I('Remark');
		$Number = I('Number');
		$ProductCount = I('ProductCount');
		$Name = I('Name');
		$openId = cookie('openId');
		$systemUserSysNo=(string)cookie('systemUserSysNo');
		if(empty($fee)||empty($Mobile)||empty($Product)||empty($systemUserSysNo)||empty($Number)||empty($Name)||empty($ProductCount)){
			return false;
		}else{
		
		}
//		$Product_Info = "{'goods_detail':[ { 'goods_id':'iphone6s_16G', 'wxpay_goods_id':'1001', 'goods_name':'iPhone6s 16G', 'quantity':1, 'price':528800, 'goods_category':'123456', 'body':'苹果手机' }]}";
//		$systemUserSysNo（主键）|$Name(姓名)|$Mobile（电话）|$Product（宽带）|$Number（学号）|$Remark（备注）|01（标记）
//		$Customname = GetCustomerServiceSysNo($systemUserSysNo);
		$tools = new \JsApiPay();
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("$Product");
		$input->SetAttach("$systemUserSysNo|01|$Name|$Mobile|$Product|$Number|$Remark|$ProductCount");
//		$input->SetDetail("$Product_Info");
		$input->SetOut_trade_no(cookie('mchid').date("YmdHis").rand(100,999));
		$input->SetTotal_fee("$fee");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://payapi.yunlaohu.cn/IPP3Order/Notify");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		$info = json_encode( $input );
//		\Think\Log::record($info );
		$jsApiParameters = $tools->GetJsApiParameters($order);
		$this->assign('jsApiParameters',$jsApiParameters);

		
		}else{
			$tools = new \JsApiPay();
			$systemUserSysNo =3714;
			$data['systemUserSysNo'] =$systemUserSysNo;
			$url  = C('SERVER_HOST')."IPP3Customers/IPP3WxconfigBySUsysNo";
			$data = json_encode( $data );
			$head = array(
				"Content-Type:application/json;charset=UTF-8",
				"Content-length:".strlen( $data ),
				//"X-Ywkj-Authentication:" . strlen( $data ),
			);
//			$Customer = GetCustomerServiceSysNo($systemUserSysNo);
//			$systemUserName= QueryStaffInfo($systemUserSysNo);
			$list = http_request( $url, $data, $head );
			$list = json_decode( $list ,true);
			cookie('appid',$list['APPID']);
			cookie('mchid',$list['NCHID']);
			cookie('key',$list['KEY']);
			cookie('appsecret',$list['APPSECRET']);
			cookie('sub_mch_id',$list['sub_mch_id']);
			cookie('systemUserSysNo',$systemUserSysNo);
			cookie('openId',$tools->GetOpenid());
//			$this->assign('Customer',$Customer);
//			$this->assign('systemUserSysNo',$systemUserName);
		}
		
		$this->display();	
	
	}

}