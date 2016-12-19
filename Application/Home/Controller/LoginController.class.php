<?php



namespace Home\Controller;



use Think\Controller;
use Think\Model;


class LoginController extends Controller{
 public function autoCheckToken($data) {
        // 支持使用token(false) 关闭令牌验证
        if(isset($this->options['token']) && !$this->options['token']) return true;
        if(C('TOKEN_ON')){
            $name   = C('TOKEN_NAME', null, '__hash__');
            if(!isset($data[$name]) || !isset($_SESSION[$name])) { // 令牌数据无效
                return false;
            }

            // 令牌验证
            list($key,$value)  =  explode('_',$data[$name]);
            if(isset($_SESSION[$name][$key]) && $value && $_SESSION[$name][$key] === $value) { // 防止重复提交
                unset($_SESSION[$name][$key]); // 验证完成销毁session
                return true;
            }
            // 开启TOKEN重置
            if(C('TOKEN_RESET')) unset($_SESSION[$name][$key]);
            return false;
        }
        return true;
    }


    public function index(){

        //echo 'Login';

    }



    public function login(){
		$__hash__ = I('__hash__');
//		 $hash = explode('_',$__hash__);
//var_dump($_SESSION);
//echo "\n".$__hash__;

//		$User = M('aa');
        if( IS_AJAX ){
		
            $RememberService = I('RememberService');
			$ServiceUsername = I('username');
			$ServicePassword = I('password');
			$__hash__ = I('__hash__');

			echo $__hash__;
									 unset($_SESSION['__hash__']);
exit;

            if ($RememberService == 1) {

	            if($ServiceUsername == cookie('serviceusername')&&$ServicePassword==cookie('servicepassword'))
	            {
	           
	            	$ServicePassword = $this->decrypt(cookie('servicepassword'),'Ywkj2016');//解密当前cookie密码

	            }else{

	            }

            }else if ($RememberService == 0) {

				if($ServiceUsername == cookie('serviceusername')&&$ServicePassword==cookie('servicepassword'))
	            {
	            
	            	$ServicePassword = $this->decrypt(cookie('servicepassword'),'Ywkj2016');//解密当前cookie密码

	            }else{
	            	$ServiceUsername = I('username');
					$ServicePassword = I('password');
	            }

            }

            $url  = C('SERVER_HOST')."IPP3Customers/IPP3CustomerLogin";

            $arr  = array(

                "UserName" => $ServiceUsername,

                "PassWord" => $ServicePassword,


            );

            $data = json_encode( $arr );

            $head = array(

                "Content-Type:application/json;charset=UTF-8",

                "Content-length:" . strlen( $data )

            );



            $res = http_request($url,$data,$head);



            $result = json_decode( $res, TRUE );

            if( $result['Code'] == 0 ){

                cookie( 'status', 1);

                session( 'status', 1);

                session( 'data', $result['Data'] );

                session( 'SysNO', $result['Data']['SysNo'] );

                session( 'password', $result['Data']['Pwd'] );

                session( 'flag', 0 );//服务商登录flag = 0
				if ($RememberService == 1) {
				
	            cookie('serviceusername',$ServiceUsername );
				
				cookie('servicepassword',$this->encrypt($ServicePassword,'Ywkj2016'));
				}else if($RememberService == 0){
				
				
				cookie('servicepassword',null);
				}



            }

            $this->ajaxReturn( $result );

            exit();

        }
//B('action_begin');
$aaa = "你妈";
\Think\Hook::listen('action_begin',$aaa);
	
        $this->display( 'login' );

    }



    public function login_staff(){


        if( IS_AJAX ){

            $RememberStaff = I('RememberStaff');
			$StaffUsername = I('username');
			$StaffPassword = I('password');

            if ($RememberStaff == 1) {

	            if($StaffUsername == cookie('staffusername')&&$StaffPassword==cookie('staffpassword'))
	            {
	            	
	            	$StaffPassword = $this->decrypt(cookie('staffpassword'),'Ywkj2016');//解密当前cookie密码

	            }else{

	            	
	            }

            }else if ($RememberStaff == 0) {

	            if($StaffUsername == cookie('staffusername')&&$StaffPassword==cookie('staffpassword'))
	            {

	            	$StaffPassword = $this->decrypt(cookie('staffpassword'),'Ywkj2016');//解密当前cookie密码

	            }else{
					$StaffPassword = I('password');
	            }
            }


            $url  = C( 'SERVER_HOST' ) . "IPP3Customers/IPP3Login";

            $arr  = array(

                "UserName" => $StaffUsername,

                "PassWord" => $StaffPassword,

            );

            $data = json_encode( $arr );

            $head = array(

                "Content-Type:application/json;charset=UTF-8",

                "Content-length:" . strlen( $data )

            );



            $res = http_request( $url, $data, $head );



            $result = json_decode( $res, TRUE );



            if( $result['Code'] == 0 ){

                // cookie( 'username', $arr["UserName"] );

                cookie( 'status', 1);

				session( 'status', 1);

                session( 'data', $result['Data'] );

				session( 'SysNO', $result['Data']['SysNO'] );

				session('servicestoreno',staffquerystore($result['Data']['SysNO']));

				session('servicestoretype',staffstoreorservice($result['Data']['SysNO'])); //员工类型  服务商为0 或 商户为1

				session( 'password', $result['Data']['Password'] );

                session( 'flag', 1 );
				if ($RememberStaff == 1) {
				
				cookie('staffusername',$StaffUsername );
				
				cookie('staffpassword',$this->encrypt($StaffPassword,'Ywkj2016'));
				
				}else if($RememberStaff == 0){
				
				cookie('staffpassword',null);
				
				}

               

            }


            $this->ajaxReturn( $result );

            exit();

        }

        $this->display( 'login_staff' );


    }



    public function logout(){

        cookie( 'status', null );

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


}

