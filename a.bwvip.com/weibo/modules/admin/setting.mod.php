<?php
/**
 * 文件名：setting.mod.php
 * 版本号：1.0
 * 最后修改时间：2006年8月14日　3:55:18
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：系统设置
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	var $FormHandler = null;

	var $IoHandler=null;



	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		Load::lib('form');
		$this->FormHandler = new FormHandler;

		Load::lib('io');
		$this->IoHandler = new IoHandler;

		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			case 'modify_normal':
				$this->ModifyNormal();
				break;
			case 'domodify_normal':
				$this->DoModifyNormal();
				break;

			case 'modify_credits':
				$this->ModifyCredits();
				break;
			case 'do_modify_credits':
				$this->DoModifyCredits();
				break;
			case 'list_credits_rule':
			case 'modify_credits_rule':
				$this->ModifyCreditsRule();
				break;
			case 'do_modify_credits_rule':
				$this->DoModifyCreditsRule();
				break;

			case 'modify_sina':
				$this->ModifySina();
				break;
			case 'do_modify_sina':
				$this->DoModifySina();
				break;


			case 'modify_qqwb':
				$this->ModifyQQWB();
				break;
			case 'do_modify_qqwb':
				$this->DoModifyQQWB();
				break;


			case 'modify_header_menu':
				$this->ModifyHeaderMenu();
				break;
			case 'domodify_header_menu':
				$this->DoModifyHeaderMenu();
				break;

			case 'modify_header_sub_menu':
				$this->ModifyHeaderSubMenu();
				break;
			case 'modify_header_sub_menu':
				$this->DoModifyHeaderSubMenu();
				break;

			case 'modify_rewrite':
				$this->ModifyRewrite();
				break;
			case 'domodify_rewrite':
				$this->DoModifyRewrite();
				break;

			case 'modify_remote':
				$this->ModifyRemote();
				break;
			case 'domodify_remote':
				$this->DoModifyRemote();
				break;

			case 'modify_filter':
				$this->ModifyFilter();
				break;
			case 'domodify_filter':
				$this->DoModifyFilter();
				break;

			case 'modify_access':
				$this->ModifyAccess();
				break;
			case 'domodify_access':
				$this->DoModifyAccess();
				break;

			case 'modify_seccode':
				$this->ModifySeccode();
				break;
			case 'do_modify_seccode':
				$this->DoModifySeccode();
				break;

			case 'modify_smtp':
				$this->ModifySmtp();
				break;
			case 'do_modify_smtp':
				$this->DoModifySmtp();
				break;

			case 'modify_shortcut':
				$this->ModifyShortcut();
				break;
			case 'do_modify_shortcut':
				$this->DoModifyShortcut();
				break;

			case 'email_notice':
				$this->Email_notice();
				break;
			case 'do_email_notice':
				$this->DoEmail_notice();
				break;
                
            case 'modify_slide':
                $this->ModifySlide();
                break;
            case 'do_modify_slide':
                $this->DoModifySlide();
                break;
            case 'modify_slide_index':
                $this->ModifySlideIndex();
                break;
            case 'do_modify_slide_index':
                $this->DoModifySlideIndex();
                break;
            
                
            case 'modify_hot_tag_recommend':
                $this->ModifyHotTagRecommend();
                break;
            case 'do_modify_hot_tag_recommend':
                $this->DoModifyHotTagRecommend();
                break;
                
            case 'modify_ftp':
                $this->ModifyFtp();
                break;
            case 'do_modify_ftp':
                $this->DoModifyFtp();
                break;
                
            			case 'follow':
                $this->Follow();
                break;
			case 'do_follow':
				$this->Do_Follow();
				break;
			
						case 'regfollow':
				$this->regfollow();
				break;
			case 'do_regfollow':
				$this->do_regfollow();
				break;
			
						case 'experience':
				$this->experience();
				break;
						case 'do_experience':
				$this->do_Experience();
				break;

						case 'do_navigation':
				$this->Do_Navigation();
				break;
			case 'navigation':
				$this->Navigation();
				break;
			
						case 'invite':
				$this->invite();
				break;
			case 'do_invite':
				$this->do_invite();
				break;
					
			default:
				$this->ModifyNormal();
				break;
		}

		$body = ob_get_clean();

		$this->ShowBody($body);

	}

	
	function ModifyNormal()
	{
		$action="admin.php?mod=setting&code=domodify_normal";

		if(false === ($role_list = cache("misc/role/admin_role_list",-1))) {
			$sql="
			SELECT
				id,name,`type`
			FROM
				".TABLE_PREFIX.'role';
			$query = $this->DatabaseHandler->Query($sql);
			while($row=$query->getRow())
			{
				$role_list[$row['type']][]=array('name'=>$row['name'],'value'=>$row['id']);
			}

			cache($role_list);
		}


		$_config = array(
			"-12" => array("value"=>"-12","name"=>"(GMT -12:00) Eniwetok, Kwajalein"),
			"-11" => array("value"=>"-11","name"=>"(GMT -11:00) Midway Island, Samoa"),
			"-10" => array("value"=>"-10","name"=>"(GMT -10:00) Hawaii"),
			"-9" => array("value"=>"-9","name"=>"(GMT -09:00) Alaska"),
			"-8" => array("value"=>"-8","name"=>"(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana"),
			"-7" => array("value"=>"-7","name"=>"(GMT -07:00) Mountain Time (US &amp; Canada), Arizona"),
			"-6" => array("value"=>"-6","name"=>"(GMT -06:00) Central Time (US &amp; Canada), Mexico City"),
			"-5" => array("value"=>"-5","name"=>"(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito"),
			"-4" => array("value"=>"-4","name"=>"(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz"),
			"-3.5" => array("value"=>"-3.5","name"=>"(GMT -03:30) Newfoundland"),
			"-3" => array("value"=>"-3","name"=>"(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is"),
			"-2" => array("value"=>"-2","name"=>"(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena"),
			"-1" => array("value"=>"-1","name"=>"(GMT -01:00) Azores, Cape Verde Islands"),
			"0"  =>array("value"=>"0","name"=>"(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia"),
			"1" => array("value"=>"1","name"=>"(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome"),
			"2" => array("value"=>"2","name"=>"(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa"),
			"3" => array("value"=>"3","name"=>"(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi"),
			"3.5" => array("value"=>"3.5","name"=>"(GMT +03:30) Tehran"),
			"4" => array("value"=>"4","name"=>"(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi"),
			"4.5" => array("value"=>"4.5","name"=>"(GMT +04:30) Kabul"),
			"5" => array("value"=>"5","name"=>"(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent"),
			"5.5" => array("value"=>"5.5","name"=>"(GMT +05:30) Bombay, Calcutta, Madras, New Delhi"),
			"5.75" => array("value"=>"5.75","name"=>"(GMT +05:45) Katmandu"),
			"6" => array("value"=>"6","name"=>"(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk"),
			"6.5" => array("value"=>"6.5","name"=>"(GMT +06:30) Rangoon"),
			"7" => array("value"=>"7","name"=>"(GMT +07:00) Bangkok, Hanoi, Jakarta"),
			"8" => array("value"=>"8","name"=>"(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei"),
			"9" => array("value"=>"9","name"=>"(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk"),
			"9.5" => array("value"=>"9.5","name"=>"(GMT +09:30) Adelaide, Darwin"),
			"10" => array("value"=>"10","name"=>"(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok"),
			"11" => array("value"=>"11","name"=>"(GMT +11:00) Magadan, New Caledonia, Solomon Islands"),
			"12" => array("value"=>"12","name"=>"(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island"),
		);
		$timezone_select = $this->FormHandler->Select("config[timezone]",$_config,(int) $this->Config['timezone']);

		$normal_role_select=$this->FormHandler->Select('config[normal_default_role_id]',
		$role_list['normal'],
		$this->Config['normal_default_role_id']);

		$no_verify_email_role_select=$this->FormHandler->Select('config[no_verify_email_role_id]',
		$role_list['normal'],
		$this->Config['no_verify_email_role_id']);

		$email_verify_radio=$this->FormHandler->YesNoRadio('config[reg_email_verify]',$this->Config['reg_email_verify']);
		$email_doublee_radio=$this->FormHandler->YesNoRadio('config[reg_email_doublee]',$this->Config['reg_email_doublee']);
		$gzip_radio=$this->FormHandler->YesNoRadio("config[gzip]",(int)$this->Config['gzip']);
		$wap_radio=$this->FormHandler->YesNoRadio("config[wap]",(int)$this->Config['wap']);
		
				$qmd_radio=$this->FormHandler->YesNoRadio("config[is_qmd]",(int)$this->Config['is_qmd']);
		
				$video_radio = $this->FormHandler->YesNoRadio("config[video_status]",(int)$this->Config['video_status']);

				$open_city_radio = $this->FormHandler->YesNoRadio("config[city_status]",(int)$this->Config['city_status']);

				$open_report = $this->FormHandler->Radio('config[is_report]',array(0=>array('name'=>'开启','value'=> 1,),1=>array('name'=>'关闭','value'=> 0,),),(int)$this->Config['is_report']);
		
				$open_signature_radio = $this->FormHandler->YesNoRadio("config[is_signature]",(int)$this->Config['is_signature']);
		
				$open_level_radio = $this->FormHandler->YesNoRadio("config[level_radio]",(int)$this->Config['level_radio']);
		
				$open_topic_level_radio = $this->FormHandler->YesNoRadio("config[topic_level_radio]",(int)$this->Config['topic_level_radio']);
		
	    
		
		$watermark_enable_radio = $this->FormHandler->YesNoRadio("config[watermark_enable])",(boolean) $this->Config['watermark_enable']);

		$_config = array(
			1=>array('name'=>'左上角','value'=>1,),
			2=>array('name'=>'左下角','value'=>2,),
			3=>array('name'=>'右上角','value'=>3,),
			4=>array('name'=>'右下角','value'=>4,),
			-1=>array('name'=>'随机位置','value'=>-1,),
		);
		$watermark_position_radio = $this->FormHandler->Radio("config[watermark_position]",$_config,(int) $this->Config['watermark_position']);
		$close_second_verify_enable_radio = $this->FormHandler->YesNoRadio("config[close_second_verify_enable]",(boolean) $this->Config['close_second_verify_enable']);
		$jump_to_enable_radio = $this->FormHandler->YesNoRadio("config[jump_to_enable]",(boolean) $this->Config['jump_to_enable']);
		
		$invite_by_admin_radio = $this->FormHandler->YesNoRadio("config[invite_by_admin]",(boolean) $this->Config['invite_by_admin']);
		
		$checked = array();
		$checked['seccode_login'] = $this->Config['seccode_login'] ? 'checked="checked"' : '';
		$checked['seccode_register'] = $this->Config['seccode_register'] ? 'checked="checked"' : '';
		
		$user_forbid = ConfigHandler::get('user','forbid');

		@$site_enable = file_get_contents(ROOT_PATH . './cache/site_enable.php');

		include $this->TemplateHandler->Template('admin/setting_normal');
	}

	
	function DoModifyNormal()
	{
		if($this->Post['site_enable']) {
			$this->Post['config']['site_closed'] = time();
			$this->IoHandler->WriteFile(ROOT_PATH . './cache/site_enable.php',$this->Post['site_enable']);
		} else {
			$this->Post['config']['site_closed'] = '0';
			$this->IoHandler->DeleteFile(ROOT_PATH . './cache/site_enable.php');
		}
		unset($this->Post['site_enable']);

		if($this->Post['user_forbid']) {
			$forbid_list = explode("\r\n",$this->Post['user_forbid']);
			$forbid_list = array_unique($forbid_list);
			$forbid = implode("\r\n",$forbid_list);
			ConfigHandler::set('user',array('forbid'=>$forbid));
		}
		unset($this->Post['user_forbid']);

		extract($this->Post['config']);
		if($site_name=="")
		{
			$this->Messager("修改出现错误,站点名称不能为空");
		}
		if(default_role_id=='')
		{
			$this->Messager("修改出现错误,请先选择一个角色");
		}
		if (!$_FILES['config']['error']['site_logo']) {
			$this->Post['config']['site_logo'] = RELATIVE_ROOT_PATH . 'images/site_logo.gif';
			if(!move_uploaded_file($_FILES['config']['tmp_name']['site_logo'],$this->Post['config']['site_logo'])) {
				@copy($_FILES['config']['tmp_name']['site_logo'],$this->Post['config']['site_logo']);
			}
			if (!is_file($this->Post['config']['site_logo'])) {
				unset($this->Post['config']['site_logo']);
			}
		}

		$this->Post['config']['thumbwidth'] = min(300,max(30,(int) $this->Post['config']['thumbwidth']));
		$this->Post['config']['thumbheight'] = min(300,max(30,(int) $this->Post['config']['thumbheight']));
		$this->Post['config']['watermark_position'] = (int) $this->Post['config']['watermark_position'];
		
		if (!isset($this->Post['config']['seccode_login'])) {
			$this->Post['config']['seccode_login'] = 0;
		}
		
		if (!isset($this->Post['config']['seccode_register'])) {
			$this->Post['config']['seccode_register'] = 0;
		}
		
		include(ROOT_PATH . 'setting/settings.php');


		$new_config=array_merge($config,$this->Post['config']);
        $new_config['topic_length'] = (int) $new_config['topic_length'];
        if($new_config['topic_length'] > 200 || $new_config['topic_length'] < 10)
        {
            $new_config['topic_length'] = 140;
        }
		ksort($new_config);
		$new_config['copyright']=jstripslashes($new_config['copyright']);
		$new_config['tongji']=jstripslashes($new_config['tongji']);
		$result=ConfigHandler::set($new_config);
								if($result!=false)
		{
			$this->Messager("配置修改成功");
		}
		else
		{
			$this->Messager("配置修改失败");
		}

	}

	
	function ModifyCredits()
	{
		$action="admin.php?mod=setting&code=domodify_credits";

				$_extcredits = array
		(
			'extcredits1'=>1,
			'extcredits2'=>1,
			'extcredits3'=>1,
			'extcredits4'=>1,
			'extcredits5'=>1,
			'extcredits6'=>1,
			'extcredits7'=>1,
			'extcredits8'=>1,
		);

		$credits = ConfigHandler::get('credits');

		if (!$credits['ext'])
		{
			$credits['ext']['extcredits1']['name'] = '威望';
			$credits['ext']['extcredits2']['name'] = '金钱';
			$credits['ext']['extcredits3']['name'] = '贡献';
		}


				if (!$credits['formula'])
		{
			$credits['formula'] = 'topic_count+extcredits1*2+extcredits2+extcredits3';
		}
		else
		{
			$credits['formula'] = str_replace(array('$member[topic_count]','$member[extcredits1]','$member[extcredits2]','$member[extcredits3]','$member[extcredits4]','$member[extcredits5]','$member[extcredits6]','$member[extcredits7]','$member[extcredits8]',),array('topic_count','extcredits1','extcredits2','extcredits3','extcredits4','extcredits5','extcredits6','extcredits7','extcredits8',),$credits['formula']);
		}

				include $this->TemplateHandler->Template('admin/setting_credits');
	}
	
	function DoModifyCredits()
	{
		$_credits = $this->Post['credits'];

		$credits = array();

				$_extcredits = array
		(
			'extcredits1'=>1,
			'extcredits2'=>1,
			'extcredits3'=>1,
			'extcredits4'=>1,
			'extcredits5'=>1,
			'extcredits6'=>1,
			'extcredits7'=>1,
			'extcredits8'=>1,
		);

		$extcredits_enable = 0;

		$credits['ext'] = array();

		if ($_credits['ext'])
		{
			foreach ($_credits['ext'] as $_k=>$_v)
			{
				if (isset($_extcredits[$_k]))
				{
					$_ico = $_v['ico'];
					$_name = trim($_v['name']);
					$_unit = $_v['unit'];
					$_default = (int) $_v['default'];
					$_enable = (($_v['enable'] && $_name) ? 1 : 0);

					if ($_enable)
					{
						$row = array
						(
							'enable' => $_enable,
							'ico' => $_ico,
							'name' => $_name,
							'unit' => $_unit,
							'default' => $_default,
						);

						$credits['ext'][$_k] = $row;
					}

					$extcredits_enable += $_enable;
				}
			}
		}

		$extcredits_enable = ($extcredits_enable ? 1 : 0);


		$credits['formula'] = str_replace(array('topic_count','extcredits1','extcredits2','extcredits3','extcredits4','extcredits5','extcredits6','extcredits7','extcredits8',),array('$member[topic_count]','$member[extcredits1]','$member[extcredits2]','$member[extcredits3]','$member[extcredits4]','$member[extcredits5]','$member[extcredits6]','$member[extcredits7]','$member[extcredits8]',),$_credits['formula']);


		ConfigHandler::set('credits',$credits);


				if ($extcredits_enable!=$this->Config['extcredits_enable'])
		{
			unset($config);
			include(ROOT_PATH . 'setting/settings.php');
			$config['extcredits_enable'] = $extcredits_enable;

			ConfigHandler::set($config);
		}


		$this->Messager("修改成功");

	}

	function ModifyCreditsRule()
	{
		if (!$this->Config['extcredits_enable'])
		{
			$this->Messager("请先开启扩展积分功能","admin.php?mod=setting&code=modify_credits");
		}

		$credits = ConfigHandler::get('credits');

		$_extcredits = array();
		foreach ($credits['ext'] as $_k=>$_v)
		{
			if($_v['enable'] && $_v['name'])
			{
				$_extcredits[$_k] = $_v;
			}
		}
		$_extcredits_count = count($_extcredits);

		$_cycletypes = array
		(
			0 => '一次性',
			1 => '每天',
			2 => '整点',
			3 => '间隔分钟',
			4 => '不限周期',
		);

		if ('list_credits_rule'==$this->Code)
		{
			$sql = "select * from ".TABLE_PREFIX."credits_rule order by rid";
			$query = $this->DatabaseHandler->Query($sql);
			while ($row = $query->GetRow())
			{
				$row['cycletype'] = $_cycletypes[$row['cycletype']];

				if(!$row['rewardnum'])
				{
					$row['rewardnum'] = '不限次数';
				}

				$rules[$row['rid']] = $row;
			}
		}
		elseif ('modify_credits_rule'==$this->Code)
		{
			$rid = max(0,(int) $this->Get['rid']);
			$sql = "select * from ".TABLE_PREFIX."credits_rule where rid=$rid";
			$query = $this->DatabaseHandler->Query($sql);
			$rule_info = $query->GetRow();

			$_related = substr($rule_info['action'],0,2);

			$rule_info["cycletype_{$rule_info['cycletype']}_checked"] = " checked ";
		}

		include($this->TemplateHandler->Template('admin/setting_credits_rule'));
	}
	function DoModifyCreditsRule()
	{
		if (!$this->Config['extcredits_enable'])
		{
			$this->Messager("请先开启扩展积分功能","admin.php?mod=setting&code=modify_credits");
		}

		$credits = ConfigHandler::get('credits');

		$_extcredits = array();
		foreach ($credits['ext'] as $_k=>$_v)
		{
			if($_v['enable'] && $_v['name'])
			{
				$_extcredits[$_k] = $_v;
			}
		}

		$rid = max(0,(int) $this->Request['rid']);
		if ($rid>0)
		{
			$sql = "select * from ".TABLE_PREFIX."credits_rule where `rid`=$rid";
			$query = $this->DatabaseHandler->Query($sql);
			$rule_info = $query->GetRow();
			if (!$rule_info)
			{
				$this->Messager("积分规则已经不存在了");
			}
            
            if($this->Post['rulesubmit'])
            {
    			$_rule = $this->Post['rule'];
    
    			$rule = array();
    			$rule['cycletype'] = min(4,max(0,(int) $_rule['cycletype']));
    			$rule['cycletime'] = (int) $_rule['cycletime'];
    			$rule['rewardnum'] = (int) $_rule['rewardnum'];
    			foreach ($_extcredits as $__k=>$__v)
    			{
    				$rule[$__k] = min(999,max(-999,(int) $_rule[$__k]));
    			}
    
    			    			if ('_'==$rule_info['action']{0})
    			{
    				$rule['related'] = '';
    				$rule['action'] = "_{$rule_info['action']{1}}";
    
    				if($_rule['related'])
    				{
    					$sql = false;
    					if ('T'==$rule_info['action']{1})
    					{
    						$sql = "select `id`,`name` from `".TABLE_PREFIX."tag` where `name`='{$_rule['related']}'";
    					}
    					elseif ('U'==$rule_info['action']{1})
    					{
    						$sql = "select `uid`,`username` from `".TABLE_PREFIX."members` where `username`='{$_rule['related']}'";
    					}
    					if($sql && ($query = $this->DatabaseHandler->Query($sql)) && ($query->GetRow()))
    					{
    						$rule['related'] = $_rule['related'];
    						$rule['action'] = "_{$rule_info['action']{1}}".crc32($rule['related']);
    					}
    				}
    			}
    
    			$sets = array();
    			foreach ($rule as $_k=>$_v)
    			{
    				if (isset($rule_info[$_k]) && $_v!=$rule_info[$_k])
    				{
    					$sets[$_k] = "`$_k`='$_v'";
    				}
    			}
    
    			if ($sets)
    			{
    				$sql = "update ".TABLE_PREFIX."credits_rule set ".implode(" , ",$sets)." where `rid`=$rid";
    				$this->DatabaseHandler->Query($sql);
    			}
            }
            elseif('del'==$this->Request['act'])
            {
                if('_'!=$rule_info['action']{0})
                {
                    $this->Messager("不允许删除此规则",null);
                }
                
                $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."credits_rule where `rid`='{$rid}'");
            }
		}
		else
		{
            $_new_rule_var = $this->Post['new_rule_var'];
            $_accpet_rules = array('_T'=>'发布指定话题','_U'=>'关注指定用户',);
            if($this->Post['newrulesubmit'] && isset($_accpet_rules[$_new_rule_var]))
            {
                $sql = "select * from ".TABLE_PREFIX."credits_rule where `action`='{$_new_rule_var}' limit 1";
                $query = $this->DatabaseHandler->Query($sql);
                $row = $query->GetRow();
                if(!$row)
                {
                    $sql = "insert into ".TABLE_PREFIX."credits_rule (`action`,`rulename`,`cycletype`,`rewardnum`) values ('{$_new_rule_var}','{$_accpet_rules[$_new_rule_var]}','1','1')";
                    $this->DatabaseHandler->Query($sql);
                    $_new_rule_id = $this->DatabaseHandler->Insert_ID();
                    if($_new_rule_id < 1)
                    {
                        $this->Messager("新规则增加失败");
                    }
                }
                else
                {
                    $_new_rule_id = $row['rid'];
                }


                $this->Messager(null,"admin.php?mod=setting&code=modify_credits_rule&rid={$_new_rule_id}");
            }


			$_rule = $this->Post['rule'];

			foreach ($_rule as $_k=>$_v)
			{
				$_sets = array();
				foreach ($_v as $__k=>$__v)
				{
					if (isset($_extcredits[$__k]))
					{
						$_sets[$__k] = "`$__k`=".min(999,max(-999,(int) $__v));
					}
				}

				$_k = (int) $_k;
				if ($_sets && $_k>0)
				{
					$sql = "update ".TABLE_PREFIX."credits_rule set ".implode(" , ",$_sets)." where `rid`=$_k";
					$this->DatabaseHandler->Query($sql);
				}
			}
		}


		$sql = "select * from ".TABLE_PREFIX."credits_rule order by rid";
		$query = $this->DatabaseHandler->Query($sql);
		$credits_rule = array();
		while ($row = $query->GetRow())
		{
			$v = false;
			foreach ($_extcredits as $_k=>$_v)
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

		if ($credits_rule)
		{
			ConfigHandler::set('credits_rule',$credits_rule);
		}


		$this->Messager("操作成功","admin.php?mod=setting&code=list_credits_rule");

	}
    

	function ModifySina()
	{
		$sina = ConfigHandler::get('sina');

		if (!$sina)
		{
			$sina = array
			(
				'enable' => 1,
				'app_key' => '3015840342',
				'app_secret' => '484175eda3cf0da583d7e7231c405988',
				'is_account_binding' => 1,
				'is_synctopic_toweibo' => 1,
				'is_syncreply_toweibo' => 1,
				'is_rebutton_display' => 1,
				'reg_pwd_display' => 1,
				'is_sync_face' => 1,
				'is_upload_image' => 1,
				'wbx_share_time' => 15,
                'is_synctopic_tojishigou' => 0,
                'is_syncreply_tojishigou' => 0,
                'is_syncimage_tojishigou' => 0,
                'syncweibo_tojishigou_time' => 180,
			);
		}
        
        if(!isset($sina['syncweibo_tojishigou_time'])) $sina['syncweibo_tojishigou_time'] = 180;

		$sina_enable_radio = $this->FormHandler->YesNoRadio('sina[enable]', (int) ($sina['enable'] && $this->Config['sina_enable']));
		$sina_is_account_binding_radio = $this->FormHandler->YesNoRadio('sina[is_account_binding]', (int) $sina['is_account_binding']);
		$sina_is_synctopic_toweibo_radio = $this->FormHandler->YesNoRadio('sina[is_synctopic_toweibo]', (int) $sina['is_synctopic_toweibo']);
		$sina_is_syncreply_toweibo_radio = $this->FormHandler->YesNoRadio('sina[is_syncreply_toweibo]', (int) $sina['is_syncreply_toweibo']);
		$sina_is_rebutton_display_radio = $this->FormHandler->YesNoRadio('sina[is_rebutton_display]', (int) $sina['is_rebutton_display']);
		$sina_reg_pwd_display_radio = $this->FormHandler->YesNoRadio('sina[reg_pwd_display]', (int) $sina['reg_pwd_display']);
		$sina_is_sync_face_radio = $this->FormHandler->YesNoRadio('sina[is_sync_face]', (int) $sina['is_sync_face']);
		$sina_is_upload_image_radio = $this->FormHandler->YesNoRadio('sina[is_upload_image]', (int) $sina['is_upload_image']);

        $sina_is_synctopic_tojishigou_radio = $this->FormHandler->YesNoRadio('sina[is_synctopic_tojishigou]', (int) $sina['is_synctopic_tojishigou']);
        $sina_is_syncreply_tojishigou_radio = $this->FormHandler->YesNoRadio('sina[is_syncreply_tojishigou]', (int) $sina['is_syncreply_tojishigou']);
        $sina_is_syncimage_tojishigou_radio = $this->FormHandler->YesNoRadio('sina[is_syncimage_tojishigou]', (int) $sina['is_syncimage_tojishigou']);


		include($this->TemplateHandler->Template('admin/setting_sina'));
	}

	function DoModifySina()
	{
		
		$check_result = $this->_sinaCheckEnv();
        if($check_result)
        {
            $config = array();
			include(ROOT_PATH . 'setting/settings.php');
			$config['sina_enable'] = 0;

			ConfigHandler::set($config);
            
            $this->Messager($check_result,null);
        }

		$sina_default = ConfigHandler::get('sina');

		$sina = array();
		$_tmps = $_POST['sina'];

		
		$sina['enable'] = ($_tmps['enable'] ? 1 : 0);


		
		$sina['app_key'] = trim($_tmps['app_key']);
		$sina['app_secret'] = trim($_tmps['app_secret']);
		if (!(preg_match('~^[a-z0-9]+$~i',$sina['app_key'].$sina['app_secret'])))
		{
			$this->Messager("请填写正确的 APP KEY 和 APP SECRET",null);
		}


		
		$__XWB_SET = array();
		$_set_file = ROOT_PATH . 'include/xwb/set.data.php';
		include($_set_file);
		$__XWB_SET_DEFAULT = $__XWB_SET;
		foreach ($__XWB_SET as $__k=>$__v)
		{
			if (isset($_tmps[$__k]))
			{
				$sina[$__k] = (int) $_tmps[$__k];

				$__XWB_SET[$__k] = $sina[$__k];
			}
		}
		if ($__XWB_SET!=$__XWB_SET_DEFAULT)
		{
			$set_data = "<?php\n%s=%s;\n?>";
			if(!(file_put_contents($_set_file, sprintf($set_data, '$__XWB_SET', var_export($__XWB_SET,true)))))
			{
				$this->Messager("配置文件<b>set.data.php</b>写入失败",null);
			}
		}


		
		if ($sina['enable']!=$this->Config['sina_enable'])
		{
			$config = array();
			include(ROOT_PATH . 'setting/settings.php');
			$config['sina_enable'] = $sina['enable'];

			ConfigHandler::set($config);
		}


		if ($sina != $sina_default)
		{
			ConfigHandler::set('sina',$sina);
		}


		$this->Messager("配置修改成功");
	}

	function _sinaCheckEnv()
	{
		Load::functions('sina_env');

        return sina_env();
	}

    function ModifyQQWB()
    {
        $qqwb = ConfigHandler::get('qqwb');

		if (!$qqwb)
		{
			$qqwb = array
			(
				'enable' => 0,
				'app_key' => '8c84c76e55d6491a991d8b568ad15209',
				'app_secret' => '949d659ce5e89230c5bac10e17bc2ba8',
				'is_account_binding' => 1,
				'is_synctopic_toweibo' => 1,
				'is_syncreply_toweibo' => 1,
				'is_rebutton_display' => 1,
				'reg_pwd_display' => 1,
				'is_sync_face' => 1,
				'is_upload_image' => 1,
				'wbx_share_time' => 15,
                'is_synctopic_tojishigou' => 0,
                'is_syncreply_tojishigou' => 0,
                'is_syncimage_tojishigou' => 0,
                'syncweibo_tojishigou_time' => 180,
			);
		}
        
        if(!isset($qqwb['syncweibo_tojishigou_time'])) $qqwb['syncweibo_tojishigou_time'] = 180;

		$qqwb_enable_radio = $this->FormHandler->YesNoRadio('qqwb[enable]', (int) ($qqwb['enable'] && $this->Config['qqwb_enable']));
		$qqwb_is_account_binding_radio = $this->FormHandler->YesNoRadio('qqwb[is_account_binding]', (int) $qqwb['is_account_binding']);
		$qqwb_is_synctopic_toweibo_radio = $this->FormHandler->YesNoRadio('qqwb[is_synctopic_toweibo]', (int) $qqwb['is_synctopic_toweibo']);
		$qqwb_is_syncreply_toweibo_radio = $this->FormHandler->YesNoRadio('qqwb[is_syncreply_toweibo]', (int) $qqwb['is_syncreply_toweibo']);
		$qqwb_is_rebutton_display_radio = $this->FormHandler->YesNoRadio('qqwb[is_rebutton_display]', (int) $qqwb['is_rebutton_display']);
		$qqwb_reg_pwd_display_radio = $this->FormHandler->YesNoRadio('qqwb[reg_pwd_display]', (int) $qqwb['reg_pwd_display']);
		$qqwb_is_sync_face_radio = $this->FormHandler->YesNoRadio('qqwb[is_sync_face]', (int) $qqwb['is_sync_face']);
		$qqwb_is_upload_image_radio = $this->FormHandler->YesNoRadio('qqwb[is_upload_image]', (int) $qqwb['is_upload_image']);

        $qqwb_is_synctopic_tojishigou_radio = $this->FormHandler->YesNoRadio('qqwb[is_synctopic_tojishigou]', (int) $qqwb['is_synctopic_tojishigou']);
        $qqwb_is_syncreply_tojishigou_radio = $this->FormHandler->YesNoRadio('qqwb[is_syncreply_tojishigou]', (int) $qqwb['is_syncreply_tojishigou']);
        $qqwb_is_syncimage_tojishigou_radio = $this->FormHandler->YesNoRadio('qqwb[is_syncimage_tojishigou]', (int) $qqwb['is_syncimage_tojishigou']);
        
        
        include($this->TemplateHandler->Template('admin/setting_qqwb'));
    }
    function DoModifyQQWB()
    {
        
		$check_result = $this->_qqwbCheckEnv();
        if($check_result)
        {
            $config = array();
			include(ROOT_PATH . 'setting/settings.php');
			$config['qqwb_enable'] = 0;

			ConfigHandler::set($config);
            
            $this->Messager($check_result,null);
        }

		$qqwb_default = ConfigHandler::get('qqwb');

		$qqwb = $_POST['qqwb'];

		
		$qqwb['enable'] = ($qqwb['enable'] ? 1 : 0);


		
		if (!(preg_match('~^[a-z0-9]+$~i',$qqwb['app_key'].$qqwb['app_secret'])))
		{
			$this->Messager("请填写正确的 APP KEY 和 APP SECRET",null);
		}


		
		if ($qqwb['enable']!=$this->Config['qqwb_enable'])
		{
			$config = array();
			include(ROOT_PATH . 'setting/settings.php');
			$config['qqwb_enable'] = $qqwb['enable'];

			ConfigHandler::set($config);
		}


		if ($qqwb != $qqwb_default)
		{
			ConfigHandler::set('qqwb',$qqwb);
		}


		$this->Messager("配置修改成功");
    }
	function _qqwbCheckEnv()
	{
		Load::functions('qqwb_env');

        return qqwb_env();
	}
    
	
	function ModifyRewrite()
	{
				$mode_list=array
		(
			''=>array('name'=>"不启用静态化",'value'=>""),
			'stand'=>array('name'=>"标准Rewrite模式",'value'=>"stand"),
			'apache_path'=>array('name'=>"路径模式",'value'=>"apache_path"),
					);

				$config_file=ROOT_PATH . 'setting/rewrite.php';
		include($config_file);

				$mode_select=FormHandler::Select('mode',$mode_list,$_rewrite['mode']);

		$mod_list=array(
			"<b>徽博</b><br>（可以填写微博相关的词，<br>如：miniblog weibo myblog等）" =>"topic",
			"<b>标签</b><br>（可以填写标签相关的词，<br>如：keywords tags class等）" =>"tag",
			"<b>设置页</b>" => "profile",
			"<b>注册页</b><br>（可以填写member相关的词，<br>如：members users等）" => "member",
			"<b>插件</b><br>（如开启标准模式，<br>请设置一个不是plugin的值<br>如：plugins extends expands 等）" => "plugin",
		);
		$mod_alias=$_rewrite['value_replace_list']['mod'];

		include $this->TemplateHandler->Template('admin/setting_rewrite');

	}

	function DoModifyRewrite()
	{
        $reserved_keys = array(
            'api' => 1,
            'backup' => 1,
            'cache' => 1,
            'data' => 1,
            'errorlog' => 1,
            'iis_rewrite' => 1,
            'images' => 1,
            'include' => 1,
            'install' => 1,
            'modules' => 1,
            'setting' => 1,
            'templates' => 1,
            'uc_server' => 1,
            'uc_client' => 1,
            'wap' => 1,
            'blacklist' => 1,
            'get_password' => 1,
            'imjiqiren' => 1,
            'login' => 1,
            'master' => 1,
            'member' => 1,
            'other' => 1,
            'pm' => 1,
            'profile' => 1,
            'report' => 1,
            'search' => 1,
            'settings' => 1,
            'share' => 1,
            'show' => 1,
            'tag' => 1,
            'theme' => 1,
            'topic' => 1,
            'url' => 1,
            'user_tag' => 1,
            'weather' => 1,
            'xwb' => 1,
            'htaccess' => 1,
            'admin' => 1,
            'ajax' => 1,
            'changelog' => 1,
            'favicon' => 1,
            'index' => 1,
            'license' => 1,
            'public' => 1,
            'robots' => 1,
            'server' => 1,
            'test' => 1,
            'upgrade' => 1,
        	'plugin' => 1,
        );

		$mod_alias=array();
		foreach ((array)$this->Post['mod_alias'] as $old_name=>$new_name)
		{
			$new_name=trim($new_name);
			if(!empty($new_name) && $old_name!=$new_name && preg_match("~^[A-Za-z0-9_]+$~",$new_name) && !isset($reserved_keys[$new_name]))
			{
				$mod_alias[$old_name]=$new_name;
			}
		}
				$config_file=ROOT_PATH . 'setting/rewrite.php';
		include($config_file);
		$_rewrite['mode']=$this->Post['mode'];
		$_rewrite['abs_path']=preg_replace("/\/+/",'/',str_replace("\\",'/',dirname($_SERVER['PHP_SELF']))."/");

		$gateway=array("stand"=>"","apache_path"=>"index.php/","normal"=>"?",""=>"");
		$_rewrite['gateway']=$gateway[$_rewrite['mode']];

		if(!empty($mod_alias))
		{
			$_rewrite['value_replace_list']['mod']=$mod_alias;
		}
		else
		{
			unset($_rewrite['value_replace_list']['mod']);
		}

		$this->_saveRewriteConfig("",'rewrite',$_rewrite);
		if($_rewrite['mode']=='stand')
		{
			$this->_writeHtaccess($_rewrite['abs_path']);
		}


		$config = array();
		include(ROOT_PATH . 'setting/settings.php');
		$config_default = $config;
		if ($_rewrite['mode'])
		{
			$config['rewrite_enable'] = 1;
		}
		else
		{
			$config['rewrite_enable'] = 0;
		}
		if($config!=$config_default)
		{
			ConfigHandler::set($config);
		}

		clearcache();

		$this->Messager("修改成功,正在更新缓存");
	}

	function ModifyFilter()
	{
		$filter=ConfigHandler::get('filter');
		$enable_radio=FormHandler::YesNoRadio("filter[enable]",(int)$filter['enable']);
		$keyword_disable_radio = FormHandler::YesNoRadio("filter[keyword_disable]", (int) $filter['keyword_disable']);
		

		include($this->TemplateHandler->Template("admin/setting_filter"));
	}
	function DoModifyFilter()
	{
		$last_tid=(int)ConfigHandler::get('filter','item_list','thread','last_tid');
		$this->Post['filter']['item_list']['thread']['last_tid']=$last_tid;

				$this->Post['filter']['keywords']=trim($this->Post['filter']['keywords']);
		$keywords=str_replace(array("\s","\r\n","\n","\\|"),"|",$this->Post['filter']['keywords']);
		if ($keywords)
		{
			$tmp_keyword_list=explode("|",$keywords);
			$keyword_list = array();
			foreach($tmp_keyword_list as $k=>$v) {
				$v = trim($v);
				if(($vl=strlen($v))>2 && $vl<40) {
					$keyword_list[] = $v;
				}
			}
			$keyword_list=array_unique($keyword_list);
			$this->Post['filter']['keywords']=implode("\r\n",$keyword_list);
			sort($keyword_list);
			$this->Post['filter']['keywords_crc32']=crc32(implode("",$keyword_list));
		}
		ConfigHandler::set('filter',$this->Post['filter']);
		$this->Messager("修改成功");
	}

	function ModifyAccess()
	{
		$access=(array)ConfigHandler::get('access');
		foreach ($access as $type =>$ips)
		{
			if(!empty($ips))
			{
				$ips=str_replace("|","\n",$ips);
				$access[$type]=stripslashes($ips);
			}
		}
		$action="admin.php?mod=setting&code=domodify_access";
		include $this->TemplateHandler->Template('admin/setting_access');
	}
	function DoModifyAccess()
	{
		$access=(array)$this->Post['access'];
		$access['ipbanned']=trim($access['ipbanned']);
		$access['admincp']=trim($access['admincp']);
		foreach ($access as $type =>$ips)
		{
			if(!empty($ips))
			{
				$ips=preg_replace("/[\r\n]+/i","\n",$ips);
				$_ip_list=explode("\n",$ips);
				$access[$type]=$sep="";
				foreach ($_ip_list as $_ip)
				{
					if(preg_match("/^(\d{1,3}\.){1,3}\d{1,3}\.?$/",$_ip))
					{
						$access[$type].=$sep.str_replace(".","\.",$_ip);
						$sep="|";
					}
				}
			}
		}
				if(!empty($access['ipbanned']) && preg_match("~^({$access['ipbanned']})~",client_ip()))
		{
			$this->Messager("您当前的IP在禁止IP里，无法设置。");
		}
				if(!empty($access['admincp']) && !preg_match("~^({$access['admincp']})~",client_ip()))
		{
			$this->Messager("您当前的IP在不在后台允许的IP里，无法设置。",-1);
		}

		ConfigHandler::set('access',$access);


		$config = array();
		include(ROOT_PATH . 'setting/settings.php');
		$config_default = $config;
		if ($access['ipbanned'] || $access['admincp'])
		{
			$config['access_enable'] = 1;
		}
		else
		{
			$config['access_enable'] = 0;
		}
		if($access['ipbanned'])
		{
			$config['ipbanned_enable'] = 1;
		}
		else
		{
			$config['ipbanned_enable'] = 0;
		}
		if($config!=$config_default)
		{
			ConfigHandler::set($config);
		}


		$this->Messager("设置成功");
	}

	function ModifySeccode()
	{
		$seccode = ConfigHandler::get('seccode');

		$seccode_enable_radio = $this->FormHandler->YesNoRadio('seccode[enable]',$this->Config['seccode_verify']);
		$seccode_type_radio = $this->FormHandler->YesNoRadio('seccode[type]',$seccode['type']);
		$seccode_onclickdisplay_radio = $this->FormHandler->YesNoRadio('seccode[onclickdisplay]',$seccode['onclickdisplay']);
		$seccode_background_radio = $this->FormHandler->YesNoRadio('seccode[background]',$seccode['background']);
		$seccode_adulterate_radio = $this->FormHandler->YesNoRadio('seccode[adulterate]',$seccode['adulterate']);
		$seccode_ttf_radio = $this->FormHandler->YesNoRadio('seccode[ttf]',$seccode['ttf']);
		$seccode_angle_radio = $this->FormHandler->YesNoRadio('seccode[angle]',$seccode['angle']);
		$seccode_color_radio = $this->FormHandler->YesNoRadio('seccode[color]',$seccode['color']);
		$seccode_size_radio = $this->FormHandler->YesNoRadio('seccode[size]',$seccode['size']);
		$seccode_shadow_radio = $this->FormHandler->YesNoRadio('seccode[shadow]',$seccode['shadow']);
		$seccode_animator_radio = $this->FormHandler->YesNoRadio('seccode[animator]',$seccode['animator']);

		$where = array(
			'login' => array(
				'name' => "用户登录",
			),
			'register' => array(
				'name' => "用户注册",
			),

		);
		$seccode_verify_where_checkbox = $this->FormHandler->CheckBox('seccode[item][]',$where,$seccode['item']);

		$action = "admin.php?mod=setting&code=do_modify_seccode";
		include($this->TemplateHandler->Template('admin/setting_seccode'));
	}
	function DoModifySeccode()
	{
		$seccode = (array) $this->Post['seccode'];
		$seccode['type'] = (int) ((boolean) $seccode['type']);
		if($seccode['type']) {
			$ch = false;
			$_chl = $this->IoHandler->ReadDir(RELATIVE_ROOT_PATH . 'images/fonts/ch');
			if(is_array($_chl) && count($_chl)) {
				foreach ($_chl as $_val) {
					if ('ttf' == strtolower(trim(substr(strrchr($_val, '.'), 1, 10)))) {
						$ch = true;
						break;
					}
				}
			}

			if ($ch = false) {
				$this->Messager('开启中文图'.'片验证码前，需要将包含完'.'整中文汉字的 TTF 中'.'文字体文件上传到 ima'.'ges/fo'.'nts/ch 目'.'录下，<a hr'.'ef=ht'.'tp:/'.'/ai'.'j'.'uhe'.'.net/gro'.'up_'.'thread/'.'view/id-'.'802 target="_blank"><font '.'color=r'.'ed>点此查'.'看说明及下载'.'记事狗官方提'.'供的中文字体</f'.'ont></a>',null);
			}
		}

		$sec_enable = $seccode['enable'];
		unset($seccode['enable']);
		ConfigHandler::set('seccode',$seccode);

		if ($sec_enable!=$this->Config['seccode_verify']) {
			unset($config);
			include(ROOT_PATH . 'setting/settings.php');
			$config['seccode_verify'] = $sec_enable;
			ConfigHandler::set($config);
		}

		$this->Messager("设置成功");
	}

	function ModifySmtp()
	{
		$smtp = ConfigHandler::get('smtp');

		$smtp_enable_radio = $this->FormHandler->YesNoRadio('smtp[enable]',(int) $smtp['enable']);

		$action = "admin.php?mod=setting&code=do_modify_smtp";
		include($this->TemplateHandler->Template('admin/setting_smtp'));
	}
	function DoModifySmtp()
	{
		if('请输入SMTP服务器的用户密码'==$_POST['smtp']['password']) {
			$smtp_config = ConfigHandler::get('smtp');
			$_POST['smtp']['password'] = $smtp_config['password'];
		}

		ConfigHandler::set('smtp',$_POST['smtp']);

		if($_POST['settingsubmitandtesting'] && $_POST['smtp']['mail']) {
			$smtp_config = ConfigHandler::get('smtp');

			Load::lib('mail');
			send_mail($smtp_config['mail'],$this->Config['site_name'] . '　测试邮件的标题','邮件测试正文',$this->Config['site_name'],$smtp_config['mail']);
			$msg = "，并向<b>{$smtp_config['mail']}</b>邮箱发送了一封测试邮件，请注意查收";
		}

		$this->Messager("设置成功{$msg}");

	}

	function ModifyShortcut()
	{
		$action = 'admin.php?mod=setting&code=do_modify_shortcut';
		unset($menu_list);
		include(ROOT_PATH . 'setting/admin_left_menu.php');

		include($this->TemplateHandler->Template('admin/setting_shortcut'));
	}
	function DoModifyShortcut()
	{
		unset($menu_list);
		$cfg_file = ROOT_PATH . 'setting/admin_left_menu.php';
		include($cfg_file);

		foreach ($menu_list as $m_key=>$m_val) {
			if($m_val['sub_menu_list'] && is_array($m_val['sub_menu_list']) && count($m_val['sub_menu_list'])) {
				foreach ($m_val['sub_menu_list'] as $s_m_key=>$s_m_val) {
					$menu_list[$m_key]['sub_menu_list'][$s_m_key]['shortcut'] = (boolean) $this->Post['menu_list'][$m_key][$s_m_key]['shortcut'];
				}
			}
		}

		$this->IoHandler->WriteFile($cfg_file,'<?php $menu_list = '.var_export($menu_list,true).'; ?>');

		$this->Messager("修改成功");
	}

	function _saveRewriteConfig($domain,$name,$config)
	{
		$file=ROOT_PATH . 'setting/rewrite.php';
		$fp= @fopen($file,"wb");
		if($fp==false)$this->Messager("无法写入配置文件");
		if($name=='settings')
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		else
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		fclose($fp);
	}
	function _writeHtaccess($abs_path)
	{
		$is_local=preg_match("~^localhost|127\.0\.0\.1|192\.168\.\d+\.\d+$~",$_SERVER['SERVER_ADDR']);
		$str="# BEGIN JishiGou
<IfModule mod_rewrite.c>
RewriteEngine On
".
($is_local?"Options FollowSymLinks":"")
."
RewriteBase $abs_path
RewriteCond %{REQUEST_URI}	!\.(gif|jpeg|png|jpg|bmp)$
RewriteCond %{REQUEST_URI}  !^{$abs_path}wap
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>
# END JishiGou";
		$fp = @fopen(ROOT_PATH.".htaccess","wb");
		if($fp==false)$this->Messager(".htaccess文件无法写入，请检查根目录是否有可写权限。");
		fwrite($fp,$str);
		fclose($fp);
	}


		function Email_notice()
	{
		$notice = ConfigHandler::get('email_notice');
		$open_notice_email = $this->FormHandler->YesNoRadio("notice[is_email]",(int)$notice['is_email']);  
		$action = "admin.php?mod=setting&code=do_email_notice";
		include($this->TemplateHandler->Template('admin/email_notice'));
	}

	function DoEmail_notice()
	{
		ConfigHandler::set('email_notice',$this->Post['notice']);
		$this->Messager("修改成功",'admin.php?mod=setting&code=email_notice');
	}
    
    function ModifySlide()
    {
        $slide_config = ConfigHandler::get('slide');
        
        $slide_enable_radio = $this->FormHandler->YesNoRadio('slide[enable]',$slide_config['enable']);
        
        $slide_list = $slide_config['list'];
        
        
        include($this->TemplateHandler->Template('admin/setting_slide'));
    }
    function DoModifySlide()
    {
        $slide_config = ConfigHandler::get('slide');
        
        $_slide = $this->Post['slide'];
        $slide_new = $this->Post['slide_new'];
        
        $slide = array();
        
        
        $slide_order_max = 0;
        $slide_list = array();
        $slide_enable = 0;
        if($_slide['list'])
        {
            foreach($_slide['list'] as $v)
            {
                if($v['src'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    
                    $slide_order_max = max($slide_order_max,$v['order']);
                    
                    $slide_list[] = $v;
                }                
            }
        }
        
        if($slide_new)
        {
            foreach($slide_new as $v)
            {
                if($v['src'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    if($v['order'] < 1)
                    {  
                        $slide_order_max += 1;  
                               
                        $v['order'] = $slide_order_max;
                    }
                    
                    $slide_list[] = $v;
                }
            }
        }
        
        if($slide_list)
        {
            usort($slide_list,create_function('$a,$b','if($a[order]==$b[order])return 0;return $a[order]<$b[order]?-1:1;'));
        }
        
        $slide['list'] = $slide_list;
        $slide['enable'] = ($slide_enable && $_slide['enable'] ? 1 : 0);
        
        if($slide != $slide_config)
        {
            ConfigHandler::set('slide',$slide);
        }
        
        
                if($slide['enable'] != $this->Config['slide_enable'])
        {
            unset($config);
            include(ROOT_PATH . 'setting/settings.php');
            
            $config['slide_enable'] = $slide['enable'];
            
            ConfigHandler::set($config);            
        }
        
        
        $this->Messager("设置成功");
    }
    function ModifySlideIndex()
    {
        $slide_config = ConfigHandler::get('slide_index');
        if(!$slide_config)
        {
            $slide_config = array (
              'list' => 
              array (
                0 => 
                array (
                  'enable' => 1,
                  'src' => 'templates/default/images/index/ad.jpg',
                  'href' => 'index.php?mod=member',
                  'order' => 1,
                ),
                1 => 
                array (
                  'enable' => 1,
                  'src' => 'templates/default/images/index/ad_2.jpg',
                  'href' => 'index.php?mod=member',
                  'order' => 2,
                ),
              ),
              'enable' => 0,
            );
            
            ConfigHandler::set('slide_index',$slide_config);
        }
        
        $slide_enable_radio = $this->FormHandler->YesNoRadio('slide[enable]',$slide_config['enable']);
        
        $slide_list = $slide_config['list'];
        
        
        include($this->TemplateHandler->Template('admin/setting_slide_index'));
    }
    function DoModifySlideIndex()
    {
        $slide_config = ConfigHandler::get('slide_index');
        
        $_slide = $this->Post['slide'];
        $slide_new = $this->Post['slide_new'];
        
        $slide = array();
        
        
        $slide_order_max = 0;
        $slide_list = array();
        $slide_enable = 0;
        if($_slide['list'])
        {
            foreach($_slide['list'] as $v)
            {
                if($v['src'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    
                    $slide_order_max = max($slide_order_max,$v['order']);
                    
                    $slide_list[] = $v;
                }                
            }
        }
        
        if($slide_new)
        {
            foreach($slide_new as $v)
            {
                if($v['src'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    if($v['order'] < 1)
                    {  
                        $slide_order_max += 1;  
                               
                        $v['order'] = $slide_order_max;
                    }
                    
                    $slide_list[] = $v;
                }
            }
        }
        
        if($slide_list)
        {
            usort($slide_list,create_function('$a,$b','if($a[order]==$b[order])return 0;return $a[order]<$b[order]?-1:1;'));
        }
        
        $slide['list'] = $slide_list;
        $slide['enable'] = ($slide_enable && $_slide['enable'] ? 1 : 0);
        
        if($slide != $slide_config)
        {
            ConfigHandler::set('slide_index',$slide);
        }
        
        
                if($slide['enable'] != $this->Config['slide_index_enable'])
        {
            unset($config);
            include(ROOT_PATH . 'setting/settings.php');
            
            $config['slide_index_enable'] = $slide['enable'];
            
            ConfigHandler::set($config);            
        }
        
        
        $this->Messager("设置成功");
    }
    
    function ModifyHotTagRecommend()
    {
        $hot_tag_recommend = ConfigHandler::get('hot_tag_recommend');
        if(!$hot_tag_recommend)
        {
            $hot_tag_recommend = array(
                'enable' => 0,
                'name' => '热门话题推荐',
                'num' => 10,
                'list' => array(),
            );
            
            ConfigHandler::set('hot_tag_recommend',$hot_tag_recommend);
        }
        
        $hot_tag_recommend_enable_radio = $this->FormHandler->YesNoRadio('hot_tag_recommend[enable]',$hot_tag_recommend['enable']);
        $_options = array();
        for($i=1;$i<=20;$i++)
        {
            $_options[$i] = array('name'=>$i,'value'=>$i);
        }
        $hot_tag_recommend_num_select = $this->FormHandler->Select('hot_tag_recommend[num]',$_options,$hot_tag_recommend['num']);
        
        $query_link = "admin.php?mod=setting&code=modify_hot_tag_recommend";
        
        $per_page_num = min(200,max(20,(int) $this->Get['pn']));
                
        $total_record = $this->DatabaseHandler->ResultFirst("select count(*) as `count` from ".TABLE_PREFIX."tag_recommend");
        
        if($total_record > 0)
        {
            $page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'Array'),"20 30 50 100 200");
                    
            $sql = "select tr.*,t.topic_count from ".TABLE_PREFIX."tag_recommend tr left join ".TABLE_PREFIX."tag t on t.name=tr.name order by `order` desc , `id` desc {$page_arr[limit]}";
            $query = $this->DatabaseHandler->Query($sql);
            $hot_tag_recommend_list = array();
            while($row = $query->GetRow())
            {
                $hot_tag_recommend_list[$row['id']] = $row;
            }
        }      
        
        
        include($this->TemplateHandler->Template('admin/setting_hot_tag_recommend'));
    }
    function DoModifyHotTagRecommend()
    {
        $act = ($this->Post['act'] ? $this->Post['act'] : $this->Get['act']);
        $timestamp = time();
        $uid = MEMBER_ID;
        $username = MEMBER_NAME;
        
        $messager = "";
        if('delete' == $act)
        {
            $id = max(0, (int) $this->Request['id']);
            
            $info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."tag_recommend where `id`='$id'");
            if(!$info)
            {
                $this->Messager("你要删除的内容已经不存在了");
            }
            
            $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."tag_recommend where `id`='$id'");
            
            $this->DatabaseHandler->Query("update ".TABLE_PREFIX."tag set `status`=0 where `name`='$name'");
            
            $messager = "删除成功";                       
        }
        else
        {
            $hot_tag_recommend_config = ConfigHandler::get('hot_tag_recommend');
            
            $_arr = $this->Post['hot_tag_recommend'];
            
            $name = ($_arr['name'] ? $_arr['name'] : "热门话题推荐");
            $num = min(20,max(1,(int) $_arr['num']));
            
            $hot_tag_recommend = array(
                'enable' => ($_arr['enable'] ? 1 : 0),
                'name' => $name,
                'num' => $num,
            );            
            
            
            if($_arr['list'])
            {                
                $_list = $this->Post['_list'];                
                foreach($_arr['list'] as $k=>$v)
                {
                    if($v != $_list[$k])
                    {
                        $v['enable'] = $v['enable'] ? 1 : 0;
                        
                        $_sets = array();
                        foreach($v as $_k=>$_v)
                        {
                            if($_v != $_list[$k][$_k])
                            {
                                $_sets[$_k] = "`{$_k}`='{$_v}'";
                            }
                        }
                        
                        if($_sets)
                        {
                            $_sets['last_update'] = "`last_update`='$timestamp'";
                            
                            $this->DatabaseHandler->Query("update ".TABLE_PREFIX."tag_recommend set ".implode(" , ",$_sets)." where `id`='$k'");
                            if($v['enable']!=$_list[$k]['enable'])
                            {
                                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."tag set `status`={$v['enable']} where `name`='$name'");
                            }
                        }
                    }
                }
            }
            
            $_new_arr = $this->Post['hot_tag_recommend_new'];
            foreach($_new_arr as $k=>$v)
            {
                if(($name = $v['name']) && ($this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."tag where `name`='$name'")))
                {
                    $_enable = $v['enable'] ? 1 : 0;
                    $desc = $v['desc'];
                    $order = (int) $v['order'];
                    
                    $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."tag_recommend (`enable`,`name`,`desc`,`order`,`dateline`,`uid`,`username`) values ('$_enable','$name','$desc','$order','$timestamp','$uid','$username')");
                    
                    $this->DatabaseHandler->Query("update ".TABLE_PREFIX."tag set `status`=$_enable where `name`='$name'");
                }
            }                        
            
            
            $__list = array();
            if($hot_tag_recommend[num] > 0)
            {
                $sql = "select tr.*,t.topic_count from ".TABLE_PREFIX."tag_recommend tr left join ".TABLE_PREFIX."tag t on t.name=tr.name where tr.enable=1 order by `order` desc , `id` desc limit {$hot_tag_recommend['num']}";
                $query = $this->DatabaseHandler->Query($sql);
                while($row = $query->GetRow())
                {
                    $__list[$row['id']] = $row;
                }               
            }                
            $hot_tag_recommend['list'] = $__list;
            
            
            if($hot_tag_recommend_config != $hot_tag_recommend)
            {
                ConfigHandler::set('hot_tag_recommend',$hot_tag_recommend);
                
                if($hot_tag_recommend['enable'] != $this->Config['hot_tag_recommend_enable'])
                {
                    unset($config);
                    include(ROOT_PATH . 'setting/settings.php');
                    
                    $config['hot_tag_recommend_enable'] = $hot_tag_recommend['enable'];
                    
                    ConfigHandler::set($config);
                }
            }            
            
            
            $messager = "设置成功";
        }
        
        
        $this->Messager($messager);
    }
    
    function ModifyFtp()
    {
        $ftp = ConfigHandler::get('ftp');
        if(!$ftp)
        {
            $ftp = array(
                'on' => 0,
                'ssl' => 0,
                'port' => 21,
                'attachdir' => '.',
                'pasv' => 0,
                'timeout' => 0
            );
            
            ConfigHandler::set('ftp',$ftp);
        }
        
        $ftp_on_radio = $this->FormHandler->YesNoRadio('ftp[on]',$ftp['on']);
        $ftp_ssl_radio = $this->FormHandler->YesNoRadio('ftp[ssl]',$ftp['ssl']);
        $ftp_pasv_radio = $this->FormHandler->YesNoRadio('ftp[pasv]',$ftp['pasv']);
                
        
        include($this->TemplateHandler->Template('admin/setting_ftp'));
    }
    function DoModifyFtp()
    {
        $ftp_config = ConfigHandler::get('ftp');
        
        $ftp = $this->Post['ftp'];
        $ftp['on'] = ($ftp['on'] && $ftp['host'] ? 1 : 0);
        $ftp['ssl'] = $ftp['ssl'] ? 1 : 0;
        $ftp['port'] = $ftp['port'] ? $ftp['port'] : 21;
        $ftp['pasv'] = $ftp['pasv'] ? 1 : 0;
        $ftp['timeout'] = max(0, (int) $ftp['timeout']);
        if('请输入FTP密码' == $ftp['password'])
        {
            $ftp['password'] = $ftp_config['password'];
        }
        ConfigHandler::set('ftp',$ftp);
        
        if($ftp['on'])
        {
            $ftp_result = ftpcmd('upload','images/no.gif');
            if($ftp_result < 1)
            {
                $ftp_error = ftpcmd('error');
                
                $ftp['on'] = 0;
                
                ConfigHandler::set('ftp',$ftp);
            }
        }
        
        if($ftp['on'] != $this->Config['ftp_on'])
        {
            unset($config);
            include(ROOT_PATH . 'setting/settings.php');
            $config['ftp_on'] = $ftp['on'];
            
            ConfigHandler::set($config);
        }
        
        
        $errors = array(
            '-100' => '服务器禁止了FTP功能',
            '-101' => '配置中没有开启FTP（请在配置中启用FTP功能）',
            '-102' => '连接到FTP服务器错误（请检查FTP地址或者端口号是否正确）',
            '-103' => '登录FTP服务器错误（请检查FTP用户名和密码是否正确）',
            '-104' => '更改FTP目录错误（请检查FTP用户的权限）',
            '-105' => '创建FTP目录错误（请检查FTP用户的权限）',
            '-106' => '文件本地读取错误',
            '-107' => '文件上传到FTP服务器错误（请检查FTP的权限）',
        );
        if($ftp_error && isset($errors[$ftp_error]))
        {
            $this->Messager($errors[$ftp_error],null);
        }    
        
        
        $this->Messager("设置成功");
    }
    
		    
    function Follow()
    {
		$follow_ary = get_def_follow_group();
		
    	    	$tmp = ConfigHandler::get('follow');
    	if (empty($tmp)) {
    		ConfigHandler::set('follow', $follow_ary);
    	} else {
    		$follow_ary = $tmp;
    	}
    	include(template('admin/setting_follow'));
    }
    
	
    function Do_Follow()
    {
		$followgroup = $this->Post['follow_name'];
    	
		    	$del_ids  = $this->Post['del_ids'];
    	
    	if (!empty($followgroup)) {
	    	foreach ($followgroup as $key => $val) {
	    		if (in_array($key, $del_ids)) {
	    			unset($followgroup[$key]);
	    			continue;
	    		}
	    		if (trim($val) !== '') {
	    			$followgroup[$key] = getstr($val, 20, 1, 1);
	    		}
	    	}
    	}
    	
    	    	$new_ary = $this->Post['new_follow'];
    	if (!empty($new_ary)) {
	    	foreach ($new_ary as $val) {
	    	   if (trim($val) !== '') {
	    			$val = getstr($val, 20, 1, 1);
	    			array_push($followgroup, $val);
	    		}
	    	}
    	}
    	
    	if (!empty($followgroup)) {
    		ConfigHandler::set('follow', $followgroup);
    	}
    	$this->Messager('操作成功了');
    }
    
        function regfollow()
    {
		    	$uids = ConfigHandler::get('regfollow');
    	if (!empty($uids)) {
    		Load::logic('topic');
			$TopicLogic = new TopicLogic();
    		$info = $TopicLogic->GetMember($uids, "`uid`,`nickname`");
    		$nicknames = array();
    		if (!empty($info)) {
    			foreach ($info as $val) {
    				$nicknames[] = $val['nickname'];
    			}
    			$str_nickname = implode("\n", $nicknames);
    		}	
    	}
    	
    	    	$default_uids = ConfigHandler::get('default_regfollow');
    	if (!empty($default_uids)) {
    		Load::logic('topic');
			$TopicLogic = new TopicLogic();
    		$info = $TopicLogic->GetMember($default_uids, "`uid`,`nickname`");
    		$nicknames = array();
    		if (!empty($info)) {
    			foreach ($info as $val) {
    				$nicknames[] = $val['nickname'];
    			}
    			$default_nickname = implode("\n", $nicknames);
    		}	
    	}
    	
    	include(template('admin/setting_regfollow'));
    }
    
    function do_regfollow()
    {
		    	$regfollow_type = $this->Post['regfollow_type'];
    	
 		    	$str_nick = $this->Post['nicks'];
	    	
    	if (empty($str_nick)) 
    	{
    		$this->Messager('输入点什么吧');
    	}
    	
    	$uids = array();
    	$nicks = explode("\n", $str_nick);
 
    	foreach ($nicks as $val) {
    		$val = trim($val);
    		if (empty($val)) {
    			continue;
    		}
    		    		$uid = DB::result_first("SELECT uid FROM ".DB::table('members')." WHERE nickname='{$val}'");
    		if ($uid < 1) {
    			continue;
    		}
    		    		$uids[] = $uid;
    	
    	}
  		
    		    if (!empty($uids)) 
	    { 	
	    	if($regfollow_type == 'recommend')
	    	{
		    	ConfigHandler::set('regfollow', $uids);	    		    	
	    	}
	    	
	    	if($regfollow_type == 'default')
	    	{	    	
		    	ConfigHandler::set('default_regfollow', $uids);	    	
	    	}
	    }	
	    
    	$this->Messager('操作成功了');
    }
    
		function Experience()
    {

                
        $slide_config = ConfigHandler::get('experience');
        $slide_list = $slide_config['list'];
        
        
       include(template('admin/experience'));
    }
    
    function do_Experience() 
    {	
 	
    	$slide_config = ConfigHandler::get('experience');
        
        $_slide = $this->Post['slide'];
        $slide_new = $this->Post['slide_new'];
        
        $slide = array();
        
        
        $slide_order_max = 0;
        $slide_list = array();
        $slide_enable = 0;
        if($_slide['list'])
        {
            foreach($_slide['list'] as $v)
            {
                if($v['level'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    
                    $slide_order_max = max($slide_order_max,$v['order']);
                    
                    $slide_list[$v['level']] = $v;
                }                
            }
        }
         
        if($slide_new)
        {
            foreach($slide_new as $v)
            {
                if($v['level'])
                {
                    $v['enable'] = ($v['enable'] ? 1 : 0);
                    if($v['enable'])
                    {
                        $slide_enable = 1;
                    }
                    
                    $v['order'] = max(0, (int) $v['order']);
                    if($v['order'] < 1)
                    {  
                        $slide_order_max += 1;  
                               
                        $v['order'] = $slide_order_max;
                    }
                    
                    $slide_list[$v['level']] = $v;
                }
            }
        }
    
        $slide['list'] = $slide_list;
        $slide['enable'] = ($slide_enable && $_slide['enable'] ? 1 : 0);
       
        if($slide != $slide_config)
        {
            ConfigHandler::set('experience',$slide);
        }
        
        
    	$this->Messager('设置成功');
    }
    
    
        function Navigation() 
    {
    	
  		$slide_config = ConfigHandler::get('navigation');
        $slide_list = $slide_config['list'];
        $type_list = array();
        if (!empty($slide_list)) {
	        foreach ($slide_list as $value) {	
	       		$type_list[] = $value['type_list'];  
	        }
        }
     
    	include(template('admin/web_navigation'));
    }
    
    
	    function Do_Navigation() 
    {
    	$slide = array();
        $type = array();
    	$slide_config = ConfigHandler::get('navigation');
    	
     	$_slide = $this->Post['slide'];
        $type = $this->Post['type'];
        $dels = (array) $this->Post['chk'];
        
        $slide_order_max = 0;
        $slide_list = array();
        $slide_enable = 0;
        
        $code_ary = array();
        if($_slide['list']) {
			foreach($_slide['list'] as $v) { 
                if($v['code'] && !in_array($v['code'], $dels)) {  
                	$v['order'] = max(0, (int) $v['order']);
                    $slide_order_max = max($slide_order_max,$v['order']);
                    $slide_list[$v['code']] = $v;
                }                
            }
        }
 
                $sn_orders = $this->Post['slide_new_order'];
        $sn_names = $this->Post['slide_new_name'];
        $sn_codes = $this->Post['slide_new_code'];
        $sn_urls = $this->Post['slide_new_url'];
        $sn_targets = $this->Post['slide_new_target'];
        if(!empty($sn_codes)) {
            foreach( $sn_codes as $key => $v) {
            	if (empty($v) || in_array($v, $dels)) {
            		continue;
            	}
            	$slide_list[$v] = array(
            		'code' => $v,
            		'name' => $sn_names[$key],
            		'order' => abs(intval($sn_orders[$key])),
            		'url' => $sn_urls[$key],
            		'target' => $sn_targets['tagget'],
            		'type_list' => null,
            	);	
            }
        }
   		
                $type_list = $type['list'];
        
	                    $tn_orders = $this->Post['type_new_order'];
        $tn_names = $this->Post['type_new_name'];
        $tn_codes = $this->Post['type_new_code'];
        $tn_urls = $this->Post['type_new_url'];
        $tn_targets = $this->Post['type_new_target'];
        if(!empty($tn_codes)) {
            foreach( $tn_codes as $key => $subs) {
            	foreach ($subs as $k => $v) {
	            	if (empty($v)) {
	            		continue;
	            	}
	            	$type_list[$key][$v] = array(
	            		'code' => $v,
	            		'name' => $tn_names[$key][$k],
	            		'order' => abs(intval($tn_orders[$key][$k])),
	            		'url' => $tn_urls[$key][$k],
	            		'target' => $tn_targets[$key][$k],
	            		'type_list' => null,
	            	);
            	}	
            }
        }
        
    	if($type_list) {
	    	foreach ($type_list as $key => $subs) {
	    	    if (in_array($key, $dels)) {
            		continue;
            	}
	     		foreach ($subs as $k => $val) {
	     			if (empty($val['code']) || in_array($k, $dels)) {
	     				unset($subs[$k]);
	     				continue;
	     			}
	     			$subs[$k]['order'] = abs(intval($val['order']));
	     		}
	     			        	if($subs) {
	        		usort($subs,create_function('$a,$b','if($a[order]==$b[order])return 0;return $a[order]<$b[order]?1:-1;'));
	        	}
	        		        	$slide_list[$key]['type_list'] = $subs;
	     	}
	    }
	    
     	if($slide_list) {
        	usort($slide_list,create_function('$a,$b','if($a[order]==$b[order])return 0;return $a[order]<$b[order]?1:-1;'));
        }
        $slide['list'] = $slide_list;
      
       
        if($slide != $slide_config) {
        	ConfigHandler::set('navigation',$slide);
        }
        
    	$this->Messager('设置成功');
    }
    
    function invite()
    {
    	$invite = ConfigHandler::get('invite');
    	$invite_msg = empty($invite) ? '' : jstripslashes($invite['invite_msg']);
    	include(template('admin/setting_invite'));
    }
    
    function do_invite()
    {
    	$invite_msg = trim($this->Post['invite_msg']);
    	if (empty($invite_msg)) {
    		$this->Messager("请填写邀请语", -1);
    	}
    	
    	$invite = array(
    		'invite_msg' => $invite_msg,
    	);
    	ConfigHandler::set('invite', $invite);
    	$this->Messager('操作成功了');
    }

}

?>