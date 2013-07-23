<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename member.han.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 1069092240 773980059 24302 $

 *******************************************************************/




/**
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 用户操作类，用户存在，权限判断等
 *
 * @author 狐狸<foxis@qq.com>
 * @last 2010年6月12日
 * @package www.jishigou.net
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}
class MemberHandler
{
	var $ID;
	var $sid=0;

	var $session=array();

	var $SessionExists=false;

	var $MemberName;
	var $MemberPassword;
	var $MemberFields;
	var $Actions;
	var $CurrentAction;	var $_Error;
	var $_System;

	var $CookieHandler=null;
	var $DatabaseHandler=null;
	var $Config=array();
	function MemberHandler($config)
	{
		$this->Config			=&$config;
		$this->DatabaseHandler	=&Obj::registry('DatabaseHandler');
		$this->CookieHandler	=&Obj::registry('CookieHandler');

		$this->ID				=0;
		$this->MemberName		='';
		$this->MemberPassword	='';
		$this->ActionList		='';
		$this->CurrentActions	='';

		$this->setSessionId();
	}
	function setSessionId($sid=null)
	{
		if($sid!==null)
		{
			$this->sid=$sid;
			$this->CookieHandler->SetVar('sid',$sid,86400*365);
		}
		else
		{
			$cookie_sid=$this->CookieHandler->GetVar('sid');
			$this->sid=(isset($_GET['sid']) || isset($_POST['sid'])) ?
					(isset($_GET['sid']) ? $_GET['sid'] : $_POST['sid']) :
					($cookie_sid ? $cookie_sid : '');
		}
		return $this->sid;
	}
	
	function SetMember($user)
	{
		if(trim($user)!='')
		{
			$this->MemberName=$user;
		}
		else
		{
			Return false;
		}

	}

	
	function SetPassword($pass)
	{
		if(trim($pass)!='')
		{
			$this->MemberPassword=$pass;
		}
		else
		{
			Return false;
		}

	}
	function FetchMember($id, $pass)
	{
        $this->ID   = max(0, (int) $id);
        $this->MemberPassword = trim($pass);
		$this->MemberFields=$this->GetMember();
		if($this->MemberFields)
		{			
			define("MEMBER_ID",(int) $this->MemberFields['uid']);
			define("MEMBER_UCUID",(int) $this->MemberFields['ucuid']);
			define("MEMBER_NAME",$this->MemberFields['username']);
			define("MEMBER_NICKNAME",$this->MemberFields['nickname']);
			define("MEMBER_ROLE_TYPE",$this->MemberFields['role_type']);
			define("MEMBER_FOLLOW",(int) $this->MemberFields['follow_count']);
			define("MEMBER_FANS",(int) $this->MemberFields['fans_count']);
			define("MEMBER_TOPIC",(int) $this->MemberFields['topic_count']);
			define("MEMBER_STYLE_THREE_TOL", (int) (1 == $this->MemberFields['style_three_tol'] ? 1 : 
				(-1 == $this->MemberFields['style_three_tol'] ? 0 : $this->Config['style_three_tol'])));

			if($this->Config['invite_enable'] && (!$this->Config['invite_count_max'] || $this->Config['invite_count_max'] > $this->MemberFields['invite_count']))
            {
				define("MEMBER_INVITE_CODE",$this->MemberFields['invitecode']);
			}
			define('AIJUHE_FOUNDER',(boolean) (MEMBER_ID > 0 && isset($this->Config['aijuhe_founder']) && false!==strpos(",{$this->Config['aijuhe_founder']},",",".MEMBER_ID.",")));
		}

        return $this->MemberFields;
	}

	function UpdateSessions()
	{
		$onlinehold		=900;		$onlinespan		=$this->Config['onlinespan']=5;		$pvfrequence	=60;
		$session 		=$this->session;
		$timestamp		=time();
		if (is_array($session))
		{
			extract($session);
		}
		$uid			= max(0,(int) $this->MemberFields['uid']);
		$username		= $uid > 0 ? $this->MemberFields['username'] : "游客";
		$groupid		= (int) $session['groupid'];

				if ($uid && $onlinespan && ($timestamp-($session['lastolupdate']?$session['lastolupdate']:$session['lastactivity']))>$onlinespan*60)
		{
			$session['lastolupdate']=$timestamp;
			$sql="
			UPDATE ".TABLE_PREFIX.'onlinetime'."
			SET
				thismonth=thismonth+{$onlinespan},
				total=total+{$onlinespan},
				lastupdate={$timestamp}
			WHERE
				uid=".$uid."
				AND lastupdate<='".($timestamp-$onlinespan*60)."'";
			$this->DatabaseHandler->Query($sql,"UNBUFFERED");
			if (!$this->DatabaseHandler->AffectedRows())
			{
				$sql="
				INSERT INTO ".TABLE_PREFIX.'onlinetime'."
					(thismonth,total,lastupdate,uid)
				values
					({$onlinespan},{$onlinespan},{$timestamp},".$uid.")";
				$this->DatabaseHandler->Query($sql,'SKIP_ERROR');
			}
		}
		$session['action']=$this->CurrentAction['id'];
		if ($this->CookieHandler->GetVar('sid')=='' || $this->sid!=$this->CookieHandler->GetVar('sid'))
		{
			$this->setSessionId($this->sid);
		}

				if($this->SessionExists)
		{
						if($pvfrequence && $uid)
			{
				if($session['spageviews']>=$pvfrequence)
				{
					$sql="
					UPDATE
						".TABLE_PREFIX.'members'."
					SET
						pageviews=pageviews+{$session['spageviews']}
					WHERE
						uid=".$uid;
					$this->DatabaseHandler->Query($sql);
					$pageviewsadd = ', pageviews=\'0\'';
				}
				else
				{
					$pageviewsadd = ', pageviews=pageviews+1';
				}
			}
			else
			{
				$pageviewsadd = '';
			}
			$sql="UPDATE ".TABLE_PREFIX.'sessions'." SET uid='$uid', username='$username', groupid='$groupid', styleid='{$session['styleid']}', invisible='{$session['invisible']}', action='{$session['action']}', lastactivity='$timestamp', lastolupdate='{$session['lastolupdate']}', seccode='{$session['seccode']}', fid='{$session['fid']}', tid='{$session['tid']}', bloguid='{$session['blogid']}' $pageviewsadd WHERE sid='{$this->sid}'";
			$this->DatabaseHandler->Query($sql);
		}
		else
		{
			$ip= client_ip();
			$ips=explode('.',$ip);
			$sql="
			DELETE FROM ".TABLE_PREFIX.'sessions'."
			WHERE
				sid='{$this->sid}'
				OR lastactivity<($timestamp-$onlinehold)
				OR 	('".$uid."'<>'0' AND uid='".$uid."')
				OR 	(uid='0' AND ip1='$ips[0]' AND ip2='$ips[1]' AND ip3='$ips[2]' AND ip4='$ips[3]' AND lastactivity>$timestamp-60)";
			$this->DatabaseHandler->Query($sql);

			$sql="
			INSERT INTO ".TABLE_PREFIX.'sessions'." (sid, ip1, ip2, ip3, ip4, uid, username, groupid, styleid, invisible, action, lastactivity, lastolupdate, seccode, fid, tid, bloguid)
			VALUES ('{$this->sid}', '$ips[0]', '$ips[1]', '$ips[2]', '$ips[3]', '$uid', '$username', '$groupid', '{$session['styleid']}', '{$session['invisible']}', '{$session['action']}', '$timestamp', '{$session['lastolupdate']}', '{$session['seccode']}', '{$session['fid']}', '{$session['tid']}', '{$session['bloguid']}')";
			$this->DatabaseHandler->Query($sql,"SKIP_ERROR");

						if($uid && $timestamp - $session['lastactivity'] > 21600)
			{
				$sql="
				UPDATE
					".TABLE_PREFIX.'members'."
				SET
					lastip='$ip',
					lastvisit=lastactivity,
					lastactivity='$timestamp'
				WHERE
					uid='".$uid."'";
				$this->DatabaseHandler->Query($sql,'mysql_unbuffered_query');
			}
		}
		return true;
	}

	
	function HasPermission($mod,$act,$is_admin=0,$uid=0)
	{
				        
        $MemberFields = array();
        
        if($uid)
        {
            if(is_array($uid))
            {
                $MemberFields = $uid;
            }
            elseif(($uid = max(0, (int) $uid)) > 0)
            {
                $MemberFields = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='$uid'");
            }
            
            if($MemberFields && ($_tmps = cache("role/role_{$MemberFields['role_id']}",-1)) && is_array($_tmps) && count($_tmps))
            {
                $MemberFields = array_merge($MemberFields,$_tmps);
            }                
        }
        
        if(!$MemberFields || $MemberFields['uid'] < 1)
        {
            $MemberFields = $this->MemberFields;
        }

		$mod=trim($mod);
		$action=trim($act);
		$role_id=$MemberFields['role_id'];
		$role_name=$MemberFields['role_name'];
		$role_privilege=$MemberFields['role_privilege'];

		if(!$role_id)
		{
			$this->_SetError("角色编号不能为空,或者该编号在服务器上已经删除");

			clearcache();

			Return false;
		}

		$is_admin=$is_admin?1:0;
		if(!($this->ActionList[$mod]))
		{
			$cache_add=$is_admin?"admin__":"";
			if(($cache_data=cache("role_action/$cache_add{$mod}",-1))===false)
			{
				$sql="
				SELECT
					*
				FROM
					".TABLE_PREFIX.'role_action'."
				where
					module='$mod' and is_admin=$is_admin
				ORDER BY
					module,`action`";
				$query = $this->DatabaseHandler->Query($sql);
				$action_list=array();
				while(false != ($row=$query->GetRow()))
				{
					$action_id=$row['id'];
					unset($row['id']);
					unset($row['module']);
					unset($row['is_admin']);
		      		if($row['describe']=='')unset($row['describe']);
		      		if($row['message']=='')unset($row['message']);
		      		if($row['allow_all']==0)unset($row['allow_all']);
		      		if($row['credit_require']=='')unset($row['credit_require']);
		      		if($row['credit_update']=='')unset($row['credit_update']);
		      		if($row['log']==0)unset($row['log']);
					if(strpos($row['action'],'|')!==false)
					{
						$act_list=explode('|',$row['action']);
						foreach($act_list as $_action)
						{
							$action_list[(string)$_action]=$action_id;
						}
					}
					else
					{
						$action_list[(string)$row['action']]=$action_id;
					}
					unset($row['action']);
					$ActionList[$action_id]=$row;
				}

				cache(array($action_list,$ActionList));
			}
			else
			{
				list($action_list,$ActionList)=$cache_data;
			}

			$this->ActionList[$mod]=array('index'=>$action_list,'info'=>$ActionList);
		}
        

		if((($current_action_id=$this->ActionList[$mod]['index'][$action])!==null) || (($current_action_id=$this->ActionList[$mod]['index']["*"])!==null))
		{
			$current_action=$this->ActionList[$mod]['info'][$current_action_id];
			$this->_SetCurrentAction($current_action);
						if($current_action['credit_require']!='' and MEMBER_ID!=0)
			{
								
				if(($error_list=$this->_compare_num($current_action['credit_require'],$MemberFields))!=0)
				{
					$_error_count=0;
					foreach($error_list as $key=>$error)
					{
						$credit_name=$this->Config[$error['var_name']];
						if(!empty($credit_name))
						{
							$_error_count++;
							$operator=$error['operator'];
							$this->_SetError("您当前{$credit_name}为({$error['you_num']}),{$current_action['name']}要求{$credit_name}<B>{$operator}</B>{$error['require_num']}。");
						}
					}
					if($_error_count>0)
					{
						$this->_SetError("相更多的了解积分信息，请<A HREF='index.php?mod=member&code=credit_info'>点这里</A>");
						Return false;
					}
				}

			}
						if(
				$current_action['credit_update']!='' and
				MEMBER_ID!=0 and substr_count($this->CurrentAction['action'],$this->Active['action'])<2
			)
			{
								if($MemberFields['role_type']!='admin')
				{
					preg_match('~credits([+-][\d]+)~',$current_action['credit_update'],$match);
					$new_credits=$MemberFields['credits']+$match[1];
					if($MemberFields['role_creditshigher']<=$new_credits and
						$new_credits<=$MemberFields['role_creditslower']

					)
					{
											}
					else
					{
						$sql="
						SELECT
							creditslower-{$new_credits} `offset`,
							id,
							name,
							creditshigher,
							creditslower
						FROM
							".TABLE_PREFIX.'role'."
						WHERE
							creditshigher<={$new_credits} and
							type='{$MemberFields['role_type']}'
						ORDER BY `offset` desc
						LIMIT 1";
						$query = $this->DatabaseHandler->Query($sql);

						$new_role=$query->GetRow();
						$update_role=",role_id={$new_role['id']}";
					}
				}
								$sql="
				UPDATE
					".TABLE_PREFIX.'members'."
				SET
					{$current_action['credit_update']}{$update_role}
				WHERE
					uid=".MEMBER_ID;
				$this->DatabaseHandler->Query($sql);
			}
			if($current_action['allow_all']==1)Return true;
			if($current_action['allow_all']=='-1')
			{
				$this->_SetError("系统已经禁止<B>{$current_action['name']}</B>的任何操作");
				Return false;
			}
						if($MemberFields['role_privilege']=="*") Return true;
						if(strpos(",".$role_privilege.",",",".$current_action_id.",")===false)
			{
				if($ActionList[$current_action_id]['message']!="")
				{
					$message=$ActionList[$current_action_id]['message'];
				}
				else
				{
					$message="您的角色({$role_name})没有{$current_action['name']}权限";
				}
				$this->_SetError($message);
				Return false;
			}
		}
				else
		{
			  		      if(0 && $mod && $action && !is_numeric($action))
              {
                $row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."role_action where `module`='$mod' and `action`='$action' and `is_admin`='$is_admin'");
                if(!$row)
                {
                    $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."role_action (`name`,`module`,`action`,`is_admin`) values ('{$_SERVER['REQUEST_METHOD']}_{$mod}_{$action}','$mod','$action','$is_admin')");
                    $role_action_id = $this->DatabaseHandler->Insert_ID();
                                    
                    if(!($this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."role_module where `module`='$mod'")))
                    {
                        $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."role_module (`module`,`name`) values ('$mod','$mod')");
                    }
                    
                    $row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."role where `id`=2");                
                    $this->DatabaseHandler->Query("update ".TABLE_PREFIX."role set `privilege`='".$this->_iddstrs($row,$role_action_id)."' where `id`={$row['id']}");               
                    if(!$is_admin)
                    {
                        $row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."role where `id`=3");                
                        $this->DatabaseHandler->Query("update ".TABLE_PREFIX."role set `privilege`='".$this->_iddstrs($row,$role_action_id)."' where `id`={$row['id']}");
                    }
                    
                    clearcache();
                }
            }
          
			if(!$this->Config['safe_mode']) return true; 			if(!$is_admin) return true; 			if('POST' != $_SERVER['REQUEST_METHOD']) return true; 			if((int) $this->Config['aijuhe_founder'] < 1) return true; 
			$error="操作模块:{$mod}<br>操作指令:{$act}<br><br>";
			$error.="由于此操作在系统中没有权限控制,您暂时无法执行该操作,请联系网站的超级管理员。";
			$this->_SetError($error);
			Return false;
		}

		Return true;
	}
    function _iddstrs($row,$id=0)
    {
        $ids = array();
        $_ids = explode(",",$row['privilege']);
        foreach($_ids as $_id)
        {
            $_id = (is_numeric($_id) ? $_id : 0);
            if($_id > 0)
            {
                $ids[$_id] = $_id;
            }
        }
        if($id > 0) $ids[$id] = $id;
        sort($ids);
        
        return implode(",",$ids);
    }
	function SetLogItemId($id)
	{
		$this->Active['item_id']=(int)$id;
	}
	function SetLogItemTitle($title)
	{
		$this->Active['item_title']=$title;
	}
		function SetLogCredits($field,$credit=0)
	{
		$this->Active[$field]=$credit;
	}
	
	function SetLogURI($uri)
	{
		$this->Active['uri'] = $uri;
	}
	function SetLogUserId($user_id)
	{
		$this->Active['uid'] = $user_id;
	}
	function SetLogUsername($username)
	{
		$this->Active['username'] = $username;
	}

	function _SetCurrentAction($action)
	{
		$this->CurrentAction=$action;
	}
	function GetCurrentAction()
	{
		Return $this->CurrentAction;
	}
	function GetMemberFields()
	{
		return $this->MemberFields;
	}
	
	function CheckMember($user,$password)
	{
		$this->SetMember($user);
		$password_hash=$this->MakePasswordHash($password);
		if(trim($user)!='')
		{
			$sql="
			Select
				*
			FROM
				".TABLE_PREFIX.'members'."
			WHERE
				username='{$this->MemberName}'";
			$query = $this->DatabaseHandler->Query($sql);
			$this->MemberFields=$query->GetRow();
			if($this->MemberFields!=false)
			{
				if($this->MemberFields['password']==$password_hash)
				{
					Return 1;
				}
				else
				{
					$this->MemberFields=array();
					Return -1;
				}
			}
			else
			{
				Return 0;
			}
		}
	}
	function MakePasswordHash($password)
	{
			Return md5($password);
	}
    function GetMember()
    {
		$membertablefields = 'M.uid,M.ucuid,M.username,M.nickname,M.password,M.secques,M.face_url,M.face,M.role_id,M.role_type,
		M.email,M.oltime,M.pageviews,M.credits,M.lastactivity,M.lastpost, M.newpm,
		M.theme_id,M.theme_bg_image,M.theme_bg_color, M.theme_text_color,M.theme_link_color,M.theme_bg_image_type,M.theme_bg_repeat,M.theme_bg_fixed,
		M.invitecode,M.invite_count,M.follow_count,M.fans_count,M.topic_count,M.style_three_tol';
		$membertablefields.=",M.at_new,M.comment_new,M.fans_new,M.favoritemy_new,M.vote_new,M.qun_new,M.event_new,M.topic_new,M.event_post_new,M.fenlei_post_new";

        $ip = client_ip();
        $this->session = array();

        if($this->sid)
        {
        	if($this->ID)
        	{
				$sql="
		        SELECT
					$membertablefields,
					S.sid,
					S.styleid,
					S.lastactivity,
					S.lastolupdate,
					S.pageviews as spageviews,
					S.uid AS sessionuid,
					S.seccode
		        FROM
					".TABLE_PREFIX.'members'." `M`
					LEFT JOIN ".TABLE_PREFIX.'sessions'." S ON(M.uid=S.uid)
		        WHERE
					M.uid       = {$this->ID} AND
					M.password = '".$this->MemberPassword."' AND
					S.sid='{$this->sid}' AND
					CONCAT_WS('.',S.ip1,S.ip2,S.ip3,S.ip4)='{$ip}';
				";
        	}
        	else
        	{
				$sql="
				SELECT
					sid, groupid, pageviews as spageviews,uid AS sessionuid, lastolupdate,lastactivity,seccode
				FROM
					".TABLE_PREFIX.'sessions'."
				WHERE
					sid='{$this->sid}' AND CONCAT_WS('.',ip1,ip2,ip3,ip4)='{$ip}'";
        	}

            	        $query = $this->DatabaseHandler->query($sql);
            $this->session = array();
            if(false!==$query)
            {
                $this->session=$query->GetRow();
            }

            if($this->session)
            {
                if(!$this->ID && ($this->session['sessionuid'] = (int) $this->session['sessionuid']) > 0)
                {
                    $sql="
            		SELECT
            			$membertablefields
    		        FROM
    					".TABLE_PREFIX.'members'." `M`
    				WHERE M.uid='{$this->session['sessionuid']}'";
    				$query = $this->DatabaseHandler->Query($sql);
                    $row = array();
                    if(false!==$query)
                    {
                        $row = $query->GetRow();
                    }
    				if($row)
    				{
    					$this->session = array_merge($this->session, $row);
    				}
                }
            }

        }
        $this->SessionExists = (($this->session && $this->session['uid']==$this->ID) ? true : false);


		if(!$this->SessionExists)
		{
            $this->CookieHandler->DeleteVar('sid');

			if($this->ID)
		 	{
				$sql="
		        SELECT
					$membertablefields
		        FROM
					".TABLE_PREFIX.'members'." `M`
		        WHERE
					M.uid       = {$this->ID} AND
					M.password = '".$this->MemberPassword."'";
		        $query = $this->DatabaseHandler->query($sql);
                $this->session = array();
                if(false!==$query)
                {
                    $this->session=$query->getRow();
                }

                if(!$this->session)
                {
                    $this->CookieHandler->DeleteVar('auth');
                }
			}
            else
            {
                $this->CookieHandler->DeleteVar('auth');
            }

	        $this->sid=$this->session['sid']=random(6);
	        $this->session['seccode']=random(6,1);
		}


		$this->session['role_id'] = (int) $this->session['role_id'];
		if($this->session['role_id'] < 1)
		{
			$this->session=array_merge((array) $this->session,(array) $this->_getGuestRole());
		}
		else
		{
			$cache_name="role/role_".$this->session['role_id'];
			if(($role=cache($cache_name,-1))===false)
			{
				$sql="
				SELECT
					`id` role_id,
					`name` role_name,
					`type` role_type,
					`creditshigher` role_creditshigher,
					`creditslower` role_creditslower,
					`privilege` role_privilege
				FROM
					".TABLE_PREFIX.'role'."
				WHERE `id`='{$this->session['role_id']}'";
				$query = $this->DatabaseHandler->Query($sql);
				$role = array();
				if(false!==$query)
				{
					$role=$query->getRow();
				}

				cache($role);
			}

			if($role && is_array($role) && count($role))
			{
				$this->session=array_merge($this->session,$role);
			}
		}

        return $this->session;
    }

	function _getGuestRole()
    {

    	if(($fields=cache('role/role_1',-1))===false)
    	{
			$sql="
			SELECT
				R.id role_id,
				R.name role_name,
				R.type role_type,
				R.privilege role_privilege
	        FROM
				".TABLE_PREFIX.'role'." R
	        WHERE
	            R.id = 1";
			$query = $this->DatabaseHandler->Query($sql);
            $fields = array();
            if(false!==$query)
            {
                 $fields = $query->GetRow();
            }

			$fields['role_id'] = 1;
			$fields['uid']=0;
			$fields['username']="游客";

			cache($fields);
    	}
        return $fields;
    }
	function _SetError($error)
	{
		$this->_Error[]=$error;
	}
	function GetError()
	{
		Return $this->_Error;
	}
	
	function _compare_num($condition_str,$array_num)
	{
		if (is_array($array_num))
		{
			extract($array_num);
		}

		$compare_str=preg_replace("~([a-z0-9]+)([><])~","\$\\1\\2",$condition_str);
		preg_match_all("~([a-z0-9]+)([><=]{1,2})([0-9]+)~",$condition_str,$compare_list,PREG_SET_ORDER);
		$compare="
			if($compare_str)
			{
				\$error=0;
			}
			else
			{
				foreach(\$compare_list as \$key=>\$val)
				{
										if(version_compare(\$\$val[1],\$val[3],\$val[2])==false)
					{
						\$error[]=array(
									'var_name'=>\$val[1],
									'operator'=>\$val[2],
									'require_num'=>\$val[3],
									'you_num'=>\$\$val[1]);

					};

				}
			}
			";
		eval($compare);
		Return $error;
	}
}
?>