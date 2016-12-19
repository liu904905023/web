<?php

namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller{

    public function index(){
        //echo 'Login';
    }

    public function login(){
        if( IS_AJAX ){
			cookie('staff',null);
			cookie('store',null);
			cookie('service',null);
            $RememberService = I('RememberService');
            $ServicePassword = I( 'password' );
            $value = cookie('servicepassword');

            if (($value != null)&&($value == $ServicePassword)) {
                //解密读取cookie
                $pass = $this->decrypt($value,'Ywkj2016');
            }else{
                $pass = $ServicePassword;
            }

            $url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerLogin";
            $arr  = array(
                "UserName" => I( 'username' ),
                "PassWord" => $pass,
//                "UserName" => '15526873365',
//                "PassWord" => '123456',
            );
            $data = json_encode( $arr );
//			var_dump($data);exit;
//          echo $url;exit;
            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );

            $res = http_request($url,$data,$head);

            $result = json_decode( $res, TRUE );
            if( $result['Code'] == 0 ){
                if ($RememberService == 1) {
                    cookie( 'serviceusername', $arr["UserName"] );
                    if ($value == null) {
                        //加密存入cookie
                        cookie( 'servicepassword',$this->encrypt(I('password'),'Ywkj2016'));
                    }

                }else if ($RememberService == 0) {
                    // cookie( 'serviceusername', null );
                    // cookie( 'servicepassword', null );
                }



                // cookie( 'username', $arr["UserName"] );
                cookie( 'status', 1);
                session( 'status', 1);
                session( 'data', $result['Data'] );
                session( 'SysNO', $result['Data']['SysNo'] );
                session( 'password', $result['Data']['Pwd'] );
                session( 'flag', 0 );//服务商登录flag = 0
               
            }
//        dump( $result );
            $this->ajaxReturn( $result );
            exit();
        }

        $this->display( 'login' );
    }

    public function login_staff(){
        
        if( IS_AJAX ){
			cookie('staff',null);
			cookie('store',null);
			cookie('service',null);
            $RememberStaff = I('RememberStaff');
            $value = cookie('staffpassword');
            if ($value != null) {
                //解密读取cookie
                $pass = $this->decrypt($value,'Ywkj2016');
            }else{
                $pass = I( 'password' );
            }


            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3Login";
            $arr  = array(
                "UserName" => I( 'username' ),
                "PassWord" => $pass,
//                "UserName" => "15526873365",
//                "PassWord" => "123456"
            );
            $data = json_encode( $arr );

            $head = array(
                "Content-Type:application/json;charset=UTF-8",
                "Content-length:" . strlen( $data )
            );

            $res = http_request( $url, $data, $head );

            $result = json_decode( $res, TRUE );

            if( $result['Code'] == 0 ){
                if ($RememberStaff == 1) {
                    cookie( 'staffusername', $arr["UserName"] );
                    if ($value == null) {
                        //加密存入cookie
                        cookie( 'staffpassword',$this->encrypt(I('password'),'Ywkj2016'),3600);
                    }
                }else if ($RememberStaff == 0) {
                    // cookie( 'staffusername', null );
                    // cookie( 'staffpassword', null );
                }
                // cookie( 'username', $arr["UserName"] );
                cookie( 'status', 1);
				session( 'status', 1);
                session( 'data', $result['Data'] );
				session( 'SysNO', $result['Data']['SysNO'] );
				session('servicestoreno',staffquerystore($result['Data']['SysNO']));
				session('servicestoretype',staffstoreorservice($result['Data']['SysNO'])); //员工类型  服务商为0 或 商户为1
				session( 'password', $result['Data']['Password'] );
                session( 'flag', 1 );
               
            }
//            dump( $result );
            $this->ajaxReturn( $result );
            exit();
        }
        $this->display( 'login_staff' );
        // $this->display( 'Base/header' );
    }

    public function logout(){
        cookie( 'status', null );
//        cookie( null );
        session( null );
		cookie('staff',null);
		cookie('store',null);
		cookie('service',null);
        header( 'Location:login' );
    }



    ////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 加密函数
     * @param string $txt 需要加密的字符串
     * @param string $key 密钥
     * @return string 返回加密结果
     */
    public  function encrypt($txt, $key = ''){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5(MD5_KEY);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $nh1 = rand(0,64);
        $nh2 = rand(0,64);
        $nh3 = rand(0,64);
        $ch1 = $chars{$nh1};
        $ch2 = $chars{$nh2};
        $ch3 = $chars{$nh3};
        $nhnum = $nh1 + $nh2 + $nh3;
        $knum = 0;$i = 0;
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
        $txt = base64_encode(time().'_'.$txt);
        $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
        $tmp = '';
        $j=0;$k = 0;
        $tlen = strlen($txt);
        $klen = strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
            $tmp .= $chars{$j};
        }
        $tmplen = strlen($tmp);
        $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
        $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
        return $tmp;
    }

    /**
     * 解密函数
     * @param string $txt 需要解密的字符串
     * @param string $key 密匙
     * @return string 字符串类型的返回结果
     */
    public function decrypt($txt, $key = '', $ttl = 0){
        if (empty($txt)) return $txt;
        if (empty($key)) $key = md5(MD5_KEY);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
        $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
        $knum = 0;$i = 0;
        $tlen = @strlen($txt);
        while(isset($key{$i})) $knum +=ord($key{$i++});
        $ch1 = @$txt{$knum % $tlen};
        $nh1 = strpos($chars,$ch1);
        $txt = @substr_replace($txt,'',$knum % $tlen--,1);
        $ch2 = @$txt{$nh1 % $tlen};
        $nh2 = @strpos($chars,$ch2);
        $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
        $ch3 = @$txt{$nh2 % $tlen};
        $nh3 = @strpos($chars,$ch3);
        $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
        $nhnum = $nh1 + $nh2 + $nh3;
        $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
        $tmp = '';
        $j=0; $k = 0;
        $tlen = @strlen($txt);
        $klen = @strlen($mdKey);
        for ($i=0; $i<$tlen; $i++) {
            $k = $k == $klen ? 0 : $k;
            $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
            while ($j<0) $j+=64;
            $tmp .= $chars{$j};
        }
        $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
        $tmp = trim(base64_decode($tmp));
        if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
            if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
                $tmp = null;
            }else{
                $tmp = substr($tmp,11);
            }
        }
        return $tmp;
    }
////////////////////////////////////////////////////////////////////////////////////////////////

}
