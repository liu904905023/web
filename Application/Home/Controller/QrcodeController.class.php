<?php
namespace Home\Controller;
use Think\Controller;
Vendor('phpqrcode.phpqrcode');
//import( '@.Common.WxAccount' );

// 本类由系统自动生成，仅供测试用途
class QrcodeController extends Controller{

	public function code(){
		$this->display();
	}
	
     public function qrcode($url='http://www.baidu.com',$level=3,$size=6){

      $errorCorrectionLevel =intval($level) ;//容错级别 
      $matrixPointSize = intval($size);//生成图片大小 
      $object = new \QRcode();
      $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   

     }

	 public function qrcode1($url='http://www.baidu.com',$level=3,$size=6){
		$url1="https://openauth.alipay.com/oauth2/appToAppAuth.htm?app_id=2016072101648286&redirect_uri=http://web.yunlaohu.cn/index.php/Isv/index?systemUserSysNo=";
		$url1.=$url;
		$errorCorrectionLevel =intval($level) ;//容错级别 
		$matrixPointSize = intval($size);//生成图片大小 
		$object = new \QRcode();
		$object->png($url1, false, $errorCorrectionLevel, $matrixPointSize, 2);

	}
	public function aliqrcode($url='http://www.baidu.com',$level=3,$size=6){
		$url1="https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=2016072101648286&scope=auth_base&redirect_uri=http://web.yunlaohu.cn/index.php/Isv/index?systemUserSysNo=";
		$url1.=$url;
		$errorCorrectionLevel =intval($level) ;//容错级别 
		$matrixPointSize = intval($size);//生成图片大小 
		$object = new \QRcode();
		$object->png($url1, false, $errorCorrectionLevel, $matrixPointSize, 2);

	}
	
	public function intelqrcode($url='http://www.baidu.com',$level=3,$size=6){
		$url1="http://web.yunlaohu.cn/index.php/Intel/index?systemUserSysNo=";
		$url1.=$url;
//		echo $url1;exit;
		$errorCorrectionLevel =intval($level) ;//容错级别 
		$matrixPointSize = intval($size);//生成图片大小 
		$object = new \QRcode();
		$object->png($url1, false, $errorCorrectionLevel, $matrixPointSize, 2);

	}
	
	
	public function index(){
//		var_dump(session(data));
		R("Base/getMenu");
	/* if( !empty( $_POST['syskeyno'] ) ){
            $data['syskeyno'] = trim( $_POST['syskeyno'] );
			$data['url']=SITE_URL."/index.php/Qrcode/qrcode/qrcode?url=http://web.yunlaohu.cn/index.php/Wxpay/newpay/?systemUserSysNo=".$data['syskeyno'];
//			echo $data['url'];
			$data['payurl']="http://web.yunlaohu.cn/index.php/Wxpay/newpay/?systemUserSysNo=".$data['syskeyno'];
           
 
        }
		$this->assign('data',$data);*/
        $this->display();
    }
	public function index2(){
	
		R("Base/getMenu");
	 $this->display();
	}
	public function scan(){

		 if( !empty( $_POST['syskeyno'] ) ){
            $data['syskeyno'] = trim( $_POST['syskeyno'] );
            $data['payfee'] = trim( yuan2fee($_POST['payfee']) );
			
			$data['url']=SITE_URL."/index.php/Qrcode/qrcode/qrcode?url=http://web.yunlaohu.cn/index.php/Wxpay/newpay/?systemUserSysNo=".$data['syskeyno']."&payfee=".$data['payfee'];
			$data['payurl']="http://web.yunlaohu.cn/index.php/Wxpay/newpay/?systemUserSysNo=".$data['syskeyno']."&payfee=".$data['payfee'];
           
 
        }
		$this->assign('data',$data);
        $this->display();
	
	}
  
//    public function index(){
//        if( !empty( $_POST['mer_base_name'] ) ){
//            $data['mer_base_name'] = trim( $_POST['mer_base_name'] );
//          
//			
//
//            echo '<hr/><br/>商户名称：' . $data['mer_base_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;<br/>';
//            echo '<br/><hr/>付款链接地址：<br/>' . $data['codeurl1'] . '<br/><hr/>';
//            echo '上面URL地址用于生成收款二维码！！';
//            echo '<br/><hr/>付款二维码：<br/><br/>';
//            echo '<img alt="付款二维码" src="' . SITE_URL . '/index.php/Qrcode/qrcode/qrcode?url=http://web.yunlaohu.cn/index.php/Wxpay/newpay/?systemUserSysNo='.$data['mer_base_name'].'" style="width:300px;height:300px;"/>';
// 
//        }
//        $this->display();
//    }


}
