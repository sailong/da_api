<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename tools.mod.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-30 15:07:35 884423293 291353115 9607 $
 *******************************************************************/




if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $Member;
	
	var $ID = '';
	
	var $TopicLogic;


	function ModuleObject($config)
	{
		$this->MasterObject($config);

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
		
		$this->Member = $this->_member();
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();		
		switch ($this->Code) {
			case 'do_notice':
				$this->DoNotice();
				break;	
			case 'user_share':
				$this->DoUserShare();
				break;	
			case 'invite_by_email':
				$this->InviteByEmail();
				break;
            case 'qqrobot':
                $this->Messager(null,"index.php?mod=tools&code=imjiqiren");
                break;
				
			default:
				$this->Main();
		}				
		$body=ob_get_clean();
		
		$this->ShowBody($body);
	}

	function Main()
	{
				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			$this->Messager($this->MemberHandler->GetError(),null);
		}
		
		$act_list = array('share'=>'分享到微博','qmd'=>'签名档',);
		
		$act_list['show'] = array('name'=>'微博秀','link_mod'=>'show','link_code'=>'show',);    

        if($this->Config['qqwb_enable'] && qqwb_init($this->Config))
        {
            $act_list['qqwb'] = 'QQ微博';
        }                
        $act_list['imjiqiren'] = 'QQ机器人';        
		if ($this->Config['sina_enable'] && sina_weibo_init($this->Config))
		{
			$act_list['sina'] = '新浪微博';
		}
        if('qqrobot'==$this->Code && !isset($act_list['qqrobot']) && isset($act_list['imjiqiren']))
        {
            $this->Code = 'imjiqiren';
        }
        $act_list['medal'] = array('name'=>'勋章','link_mod'=>'other','link_code'=>'medal',);  
        $act_list['sms'] = '短信';       
		$act = isset($act_list[$this->Code]) ? $this->Code : 'share';


		$member = $this->Member;
		if (!$member) {
				$this->Messager("请先登录",'index.php?mod=login');
		}
		
				if ($member['medal_id']) {
			$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
		}
		$member_nickname = $member['nickname'];

		Load::lib('form');
		$FormHandler = new FormHandler();


		
		if ('qqrobot' == $act)
		{
			if( empty($member['qq'])) {
				$qq_code = $member['uid']."j".md5($member['password'].$member['username']);
			}

		}
        
	   elseif ('qmd' == $act) 
	   {
	        $uid = MEMBER_ID;
    		
	        if($this->Config['is_qmd'])
	        {
			    $member_qmd_img = $this->Config['ftp_on'] ? $member['qmd_url'] : $this->Config['site_url'].'/'.$member['qmd_url'];
	      		
			    $member_qmd = $member['qmd_img'] ? $member['qmd_img'] : 'images/qmd.jpg';
			    
	            Load::logic('other');
	    		$OtherLogic = new OtherLogic();
	    		$qmd_return = $OtherLogic->qmd_list($uid,$member_qmd);	   
	        }   

		}
		    
		
		elseif ('extcredits'==$act)
		{
			if (!$this->Config['extcredits_enable'])
			{
				$this->Messager("积分功能未启用",null);
			}

			$credits_config = $this->Config['credits'];

			$_default_credits = array();
			$_enable = false;
			if(is_array($credits_config) && count($credits_config))
			{
				foreach ($credits_config['ext'] as $_k=>$_v)
				{
					if ($_v['enable'])
					{
						$_enable = true;

						if ($_v['default'])
						{
							$_default_credits[$_k]=$_v['default'];
						}
					}
				}
			}
			if (!$_enable)
			{
				$this->Messager("积分未设置",null);
			}

			$op = $this->Get['op'];
			$op_lists = array(
				'base'=>'我的积分',
				'log'=>'积分记录',
				'rule'=>'积分规则',
			);
			$op = (isset($op_lists[$op]) ? $op : 'base');


			if ('base'==$op)
			{
				$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."members where `uid`='".MEMBER_ID."'");

				$member = $query->GetRow();

				$_search = $_replace = array();
				for ($i=1;$i<=8;$i++)
				{
					$k = 'extcredits'.$i;
					$_search[$k] = '$member['.$k.']';
					$_replace[$k] = ' 0 ';
					if (isset($credits_config['ext'][$k]) && $credits_config['ext'][$k]['enable'])
					{
						$_replace[$k] = $credits_config['ext'][$k]['name'];
					}
				}
				$_search['topic_count'] = '$member[topic_count]';
				$_replace['topic_count'] = '发微博数量';

				$credits_config_formula = str_replace($_search,$_replace,$credits_config['formula']);

				;
			}
			elseif('log'==$op)
			{
				$query = $this->DatabaseHandler->Query("select R.rulename,RL.* from ".TABLE_PREFIX."credits_rule_log RL left join ".TABLE_PREFIX."credits_rule R on R.rid=RL.rid where RL.`uid`='".MEMBER_ID."'");
				$log_list = array();
				while ($row=$query->GetRow())
				{
					$log_list[] = $row;
				}

				if ($_default_credits)
				{
					$log_list['default_credits'] = $_default_credits;
					$log_list['default_credits']['rulename'] = '注册时的初始积分';
					$log_list['default_credits']['total'] = $log_list['default_credits']['cyclenum'] = 1;
				}

				$_counts = array();
				foreach ($log_list as $k=>$row)
				{
					$row['dateline'] = ($row['dateline'] ? my_date_format($row['dateline'],'m-d H:i') : ' - ');

					foreach ($credits_config['ext'] as $_k=>$_v)
					{
						$row[$_k] = $row[$_k] * $row['total'];

						$_counts[$_k] += $row[$_k];
					}

					$log_list[$k] = $row;
				}
			}
			elseif ('rule'==$op)
			{
				if(!($credits_rule = ConfigHandler::get('credits_rule')))
				{
					$sql = "select * from ".TABLE_PREFIX."credits_rule order by rid";
					$query = $this->DatabaseHandler->Query($sql);
					$credits_rule = array();
					while ($row = $query->GetRow())
					{
						$v = false;
						foreach ($credits_config['ext'] as $_k=>$_v)
						{
							if ($row[$_k])
							{
								$v = true;
								break;
							}
						}

						if($v)
						{
							foreach ($row as $k=>$v)
							{
								if (!$v)
								{
									unset($row[$k]);
								}
							}

							$credits_rule[$row['action']] = $row;
						}
					}
				}

				$_cycletypes = array
				(
					0 => '一次性',
					1 => '每天',
					2 => '整点',
					3 => '间隔分钟',
					4 => '不限周期',
				);
				if ($_default_credits)
				{
					$credits_rule['default_credits'] = $_default_credits;
					$credits_rule['default_credits']['rulename'] = '注册时的初始积分';
					$credits_rule['default_credits']['cycletype'] = 0;
					$credits_rule['default_credits']['rewardnum'] = 1;
				}
				foreach ($credits_rule as $k=>$v)
				{
					$v['cycletype'] = $_cycletypes[(int) $v['cycletype']];
					if (!$v['rewardnum'])
					{
						$v['rewardnum'] = '不限次数';
					}

					$credits_rule[$k] = $v;
				}

				;
			}
		   	
			else
			{
				$this->Messager("未定义的操作");
			}
		}


		
		elseif ('imjiqiren' == $act)
		{
            define('IN_IMJIQIREN_MOD',      true);

            include(ROOT_PATH . 'modules/imjiqiren.mod.php');
		}
        
        elseif('sms' == $act)
        {
            define('IN_SMS_MOD',      true);

            include(ROOT_PATH . 'modules/sms.mod.php');
        }
        
        
        elseif('qqwb' == $act)
        {
            if(!qqwb_init($this->Config))
            {
                $this->Messager('QQ微博功能未启用，请联系管理员',null);
            }
            
            Load::lib('form');
            $FormHandler = new FormHandler();
            
            $qqwb = ConfigHandler::get('qqwb');
            
            $qqwb_bind_info = qqwb_bind_info(MEMBER_ID);
            
            if($qqwb_bind_info)
            {
                if($qqwb['is_synctopic_toweibo'])
                {
                    $synctoqq_radio = $FormHandler->YesNoRadio('synctoqq',(int) $qqwb_bind_info['synctoqq']);
                }                
            }
                        
            ;
        }

		
		elseif ('sina' == $act)
		{
			$profile_bind_message = '';

			$xwb_start_file = ROOT_PATH . 'include/xwb/sina.php';

			if (!is_file($xwb_start_file))
			{
				$profile_bind_message = '&#25554;&#20214;&#25991;&#20214;&#20002;&#22833;&#65292;&#26080;&#27861;&#21551;&#21160;&#65281;';
			}
			else
			{
				require($xwb_start_file);

				$profile_bind_message = '<a href="javascript:XWBcontrol.bind()">&#22914;&#26524;&#30475;&#19981;&#21040;&#26032;&#28010;&#24494;&#21338;&#32465;&#23450;&#35774;&#32622;&#31383;&#21475;&#65292;&#35831;&#28857;&#20987;&#36825;&#37324;&#21551;&#21160;&#12290;</a>';

				$GLOBALS['xwb_tips_type'] = 'bind';

				$profile_bind_message .= jsg_sina_footer();
			}

			;
		}
		elseif ('email' == $act)
		{
            ;
		}
		elseif('share' == $act)
		{  
			$PostShareName = $this->Post['share_name'];
		    $shareName =  $PostShareName ? $PostShareName : $this->Config['site_name'];
		}

		$this->Title = $act_list[$act];
		include($this->TemplateHandler->Template('tools_main'));
	}
	
	
	function _member()
	{
		if (MEMBER_ID < 1) {
			$this->Messager(null,$this->Config['site_url'] . "/index.php?mod=login");
		}		
		
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
		return $member;
	}

	
}


?>
