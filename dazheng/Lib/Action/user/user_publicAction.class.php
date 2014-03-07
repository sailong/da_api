<?php
/**
 *    #Case		bwvip.com
 *    #Page		user_publicAction.class.php (首页)
 *
 *    @author		Jack
 *    @e-mail		zhanglong@bwvip.com
 *    @copyright	www.bwvip.com
 */
class user_publicAction extends Action
{
	
	public function _initialize()
	{

		if(!$_SESSION['user_id'])
		{
			$this->error('请登录',U('user/public/login'));
			echo "请登录";
		}
	
	}
	
	/*
	//自动登录
    public function autologin()
	{
        if(isset($_COOKIE[C('DISCUZ_COOKIE_PRE').'auth']) && !empty($_COOKIE[C('DISCUZ_COOKIE_PRE').'auth']))
		{
			import("@.ORG.UcService");
			$ucService = new UcService;
            $key = md5(C('DISCUZ_AUTH_KEY').$_COOKIE[C('DISCUZ_COOKIE_PRE').'saltkey']); //解密钥匙		
            $userMsg = explode("\t", uc_authcode($_COOKIE[C('DISCUZ_COOKIE_PRE').'auth'], 'DECODE', $key)); //得到加了密的password和uid
            $userInfo = uc_get_user($userMsg[1], 1);   //获取用户信息 (通过ID ，获取name)         
            
			$_SESSION['user_id'] = $userMsg[1];
            $_SESSION['user_name'] = $userInfo[1];
			
            //dump($_SESSION);
        }
		else
		{   
			//论坛没有登录
			unset($_SESSION['user_id']);
			unset($_SESSION['user_name']);
			unset($_SESSION);
            //Session::destroy();
        }
    }
	*/
	
}
?>