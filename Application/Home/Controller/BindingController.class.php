<?php
// +----------------------------------------------------------------------
// | 设计开发：Webster  Tel:17095135002 邮箱：312549912@qq.com
// | 此版本为微信官方最新微信支付V3版本
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
class BindingController extends Controller {
	
	public function index(){
		$appid = "wx261671a6d70c4db5";  
		$secret = "0559d78cf2a556b1d7b46988f026114a";  
		$code = $_GET["code"]; 
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
//		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx261671a6d70c4db5&secret=0559d78cf2a556b1d7b46988f026114a&code=".$code."&grant_type=authorization_code";
		
		$data = $this->httpGet($url);//包括openid info
			
		$data = json_decode($data,true); 
		$urls = "http://mobile.yunlaohu.cn/index.php/Binding/index?openid=".$data['openid'];
		header('Location:'.$urls.'');	
		  
	}
	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		// 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
		// 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}