<?php
/*******************************************************************
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename xwbAuth.mod.php $
 *
 * @Author 狐狸<foxis@qq.com> $
 *
 * @Date 2010-12-06 04:58:24 $
 *******************************************************************/ 

class xwbAuth {
	function xwbAuth (){
	}
	
	function default_action(){$this->login();}
	
	
	function login(){
		if( !XWB_plugin::pCfg('is_account_binding') ){
			XWB_plugin::showError('新浪微博绑定功能已经关闭！');
		}
		
		
		$aurl = $this->_getOAuthUrl();		$sess = XWB_plugin::getUser();
				$sess->setInfo('waiting_site_bind',	1);
		XWB_plugin::redirect($aurl, 3);
	}
	
		function show(){
		exit('DEBUG IS DISABLED');
		$wbApi		= XWB_plugin::getWB();
		echo '<pre>';
		print_r($wbApi->verifyCredentials());
		echo '</pre>';
	}
	
		function authCallBack(){
		if( !XWB_plugin::pCfg('is_account_binding') ){
			XWB_plugin::showError('新浪微博绑定功能已经关闭！');
		}
		
				$sess = XWB_plugin::getUser();
		$waiting_site_bind = $sess->getInfo('waiting_site_bind');
		if (empty($waiting_site_bind)){
						$siteUrl = XWB_plugin::siteUrl(0);
			XWB_plugin::redirect($siteUrl, 3);
		}
				$wbApi		= XWB_plugin::getWB();
		$db			= XWB_plugin::getDB();
		$last_key	= $wbApi->getAccessToken(XWB_plugin::V('r:oauth_verifier')) ;
		
		if( !isset($last_key['oauth_token']) || !isset($last_key['oauth_token_secret']) ){
			$api_error_origin = isset($last_key['error']) ? $last_key['error'] : 'UNKNOWN ERROR. MAYBE SERVER CAN NOT CONNECT TO SINA API SERVER';
			$api_error = ( isset($last_key['error_CN']) && !empty($last_key['error_CN']) && 'null' != $last_key['error_CN'] ) ? $last_key['error_CN'] : '';
			
			XWB_plugin::LOG("[WEIBO CLASS]\t[ERROR]\t#{$wbApi->req_error_count}\t{$api_error}\t{$wbApi->last_req_url}\tERROR ARRAY:\r\n".print_r($last_key, 1));
			XWB_plugin::showError("服务器获取Access Token失败；请稍候再试。<br />错误原因：{$api_error}[{$api_error_origin}]");
		}
		
		$sess->setOAuthKey($last_key, true);
		$wbApi->setConfig();
		$uInfo = $wbApi->verifyCredentials();
		$sess->setInfo('sina_uid', $uInfo['id']);
		$sess->setInfo('sina_name', $uInfo['screen_name']);
								$sinaHasBinded = false;
		$bInfo = $db->fetch_first("SELECT * FROM ".XWB_S_TBPRE."xwb_bind_info  WHERE  sina_uid='".$uInfo['id']."'");
		if ( !empty($bInfo) && is_array($bInfo) ){
			$sinaHasBinded = true; 
						if( $bInfo['token'] != $last_key['oauth_token'] || $bInfo['tsecret'] != $last_key['oauth_token_secret'] ){
				$db->query("UPDATE ". XWB_S_TBPRE. "xwb_bind_info SET token='". (string)$last_key['oauth_token']. "', tsecret='". (string)$last_key['oauth_token_secret']. "' WHERE sina_uid='".$uInfo['id']."'");
			}
		}
		
						$tipsType = '';
						
		if (defined('XWB_S_UID') &&  XWB_S_UID ){
			if ($sinaHasBinded){
				$tipsType = 'hasBinded';
				$sess->clearToken();
			}else{
				
				$inData = array();
				$inData['uid'] 		= XWB_S_UID;
				$inData['sina_uid'] = $uInfo['id'];
				$inData['token']	= $last_key['oauth_token'];
				$inData['tsecret']	= $last_key['oauth_token_secret'];
				$inData['profile']	= '[]';
				$sqlF = array();
				$sqlV = array();
				foreach ($inData as $k=>$v){
					$sqlF[] = "`".$k."`";
					$sqlV[] = "'".mysql_escape_string($v)."'";
				}
				$sql = "INSERT INTO ".XWB_S_TBPRE."xwb_bind_info  (".implode(",",$sqlF).") VALUES (".implode(",",$sqlV).") ;";
				$rst = $db->query($sql, 'UNBUFFERED');
				
				if(!$rst){echo "DB ERROR";exit;return false;}
				$tipsType = 'bind';
				
								$sess->appendStat('bind', array( 'uid' => $uInfo['id'], 'type' => 1 ));
				
			}
			
		}else{
						if ($sinaHasBinded){
				require_once XWB_P_ROOT. '/lib/xwbSite.inc.php';
				$result = xwb_setSiteUserLogin((int)$bInfo['uid']);
				if( false == $result ){
					$db->query("DELETE FROM ". XWB_S_TBPRE. "xwb_bind_info WHERE sina_uid='".$uInfo['id']."'");
					$tipsType = 'siteuserNotExist';
				}else{
					$tipsType = 'autoLogin';
				}
				
			}else{
								$sess->setInfo('waiting_site_reg', '1');
				$tipsType = 'reg';
			}
		}
				
				if( $tipsType == 'bind' ){
			setcookie('xwb_tips_type', $tipsType, 0);
		}
				$sess->setInfo('waiting_site_bind',	0);
		
				$sess->appendStat('login', array( 'uid' => $uInfo['id'] ));
		
				$this->_showBinging( $tipsType );
		
	}
	
	
	
	function _showBinging( $tipsType ){		
		
				$GLOBALS['xwb_tips_type'] = $tipsType;
		
				if('UTF-8' != strtoupper($GLOBALS['jsg_sys_config']['charset'])){
	    	@header("Content-type: text/html; charset=utf-8");
	    }
		
		if( 'autoLogin' == $tipsType ){
			
			if (true===UCENTER) 
			{
								include_once(ROOT_PATH . 'uc_client/client.php');

				$ucuid = (int) $GLOBALS['jsg_sys_config']['login_user']['ucuid'];	
				if ($ucuid > 0) 
				{
					
					$synlogin_result = uc_user_synlogin($ucuid);
					
					jsg_showmessage("登录成功，正在为您跳转到首页。{$synlogin_result}",'index.php',5);
				}
			}
            
			            XWB_plugin::redirect(XWB_plugin::siteUrl(0).'index.php', 3);
            
		}elseif( 'siteuserNotExist' == $tipsType ){
			jsg_showmessage( XWB_plugin::L('xwb_site_user_not_exist'));
			
		}elseif( 'reg' == $tipsType ){
			jsg_showmessage(XWB_plugin::L('xwb_process_binding', 'openReg4jsg' ));
			
		}elseif( 'hasBinded' == $tipsType ){
			jsg_showmessage(XWB_plugin::L('xwb_process_binding', 'hasBind' ));
		
				}else{
			XWB_plugin::redirect(XWB_plugin::siteUrl(0).'index.php?mod=tools&code=sina', 3);
		}
		
	}
	
	
		function _getOAuthUrl(){
		static $aurl = null;
		if (!empty($aurl)) {return $aurl; }
		
		$sess = XWB_plugin::getUser();
		$sess->clearToken();
		
		$wbApi = XWB_plugin::getWB();
		$keys = $wbApi->getRequestToken();
		
		if( !isset($keys['oauth_token']) || !isset($keys['oauth_token_secret']) ){
			$api_error_origin = isset($keys['error']) ? $keys['error'] : 'UNKNOWN ERROR. MAYBE SERVER CAN NOT CONNECT TO SINA API SERVER';
			$api_error = ( isset($keys['error_CN']) && !empty($keys['error_CN']) && 'null' != $keys['error_CN'] ) ? $keys['error_CN'] : '';
			
			XWB_plugin::LOG("[WEIBO CLASS]\t[ERROR]\t#{$wbApi->req_error_count}\t{$api_error}\t{$wbApi->last_req_url}\tERROR ARRAY:\r\n".print_r($keys, 1));
			XWB_plugin::showError("服务器获取Request Token失败；请稍候再试。<br />错误原因：{$api_error}[{$api_error_origin}]");
		}
		
				$aurl = $wbApi->getAuthorizeURL($keys['oauth_token'] ,false , XWB_plugin::baseUrl(). XWB_plugin::URL('xwbAuth.authCallBack'));
		
				$sess->setOAuthKey($keys, false);
		return rtrim($aurl, '&');	}
}
?>
