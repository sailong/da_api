<?php
/**
 * 文件名：setting.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年10月27日 10时05分58秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 个人设置模块
 */

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
			case 'do_modify_password':
				$this->DoModifyPassword();
				break;
			case 'do_modify_face':
				$this->DoModifyFace();
				break;
			case 'do_modify_profile':
				$this->DoModifyProfile();
				break;
			case 'do_notice':
				$this->DoNotice();
				break;
			case 'user_share':
				$this->DoUserShare();
				break;
			case 'invite_by_email':
				$this->InviteByEmail();
				break;
			case 'modify_email':
				$this->DoModifyEmail();
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
        $_act_list = array('imjiqiren'=>1,'qqrobot'=>1,'sms'=>1,'sina'=>1,'qqwb'=>1,);
        if(isset($_act_list[$this->Code]))
        {
            $this->Messager(null,"index.php?mod=tools&code={$this->Code}");
        }
        if('email'==$this->Code)
        {
            $this->Messager(null,'index.php?mod=settings&code=base#modify_email_area');
        }

		$act_list = array('base'=>'我的资料','face'=>'我的头像','secret'=>'修改密码','user_medal'=>'我的勋章','exp'=>'微博等级','user_tag'=>array('name'=>'我的标签','link_mod'=>'user_tag',),);
		if ($this->Config['extcredits_enable'])
		{
			$act_list['extcredits'] = '我的积分';
		}        
		$act = isset($act_list[$this->Code]) ? $this->Code : 'base';


		$member = $this->Member;
		$member_nickname = $member['nickname'];

		Load::lib('form');
		$FormHandler = new FormHandler();

		
		if('face' == $act)
		{
						if(true === UCENTER_FACE)
            {
			     include_once(ROOT_PATH . 'uc_client/client.php');

								$uc_avatarflash = uc_avatar(MEMBER_UCUID,'avatar','returnhtml');

                $query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."members where `uid`='{$member['uid']}'");
                $_member_info = $query->GetRow();
                if($member['uid'] > 0 && MEMBER_UCUID > 0 && !($_member_info['face']))
                {
                    $uc_check_result = uc_check_avatar(MEMBER_UCUID);
                    if($uc_check_result)
                    {
                        $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `face`='./images/no.gif' where `uid`='{$member['uid']}'");
                    }
                }
			}
            else
            {
                $temp_face = '';
                if($this->Get['temp_face'] && is_image($this->Get['temp_face']))
                {
                    $temp_face = $this->Get['temp_face'];
                    
                    $member['face_original'] = $temp_face;
                }
            }
		}
			
		
		elseif('base' == $act)
		{
			$sql = "select * from `".TABLE_PREFIX."memberfields` where `uid`='{$member['uid']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$memberfields = $query->GetRow();
			if(!$memberfields) {
				$memberfields = array();
				$memberfields['uid'] = $member['uid'];

				$sql = "insert into `".TABLE_PREFIX."memberfields` (`uid`) values ('{$member['uid']}')";
				$this->DatabaseHandler->Query($sql);
			}
		
		
			$gender_radio = $FormHandler->Radio('gender',array(1=>array('name'=>'男','value'=>1,),2=>array('name'=>'女','value'=>2,),),$member['gender']);
			$_options = array(
				'0' => array(
					'name' => '请选择',
					'value' => '0',
				),
				'身份证' => array(
					'name' => '身份证',
					'value' => '身份证',
				),
				'学生证' => array(
					'name' => '学生证',
					'value' => '学生证',
				),
				'军官证' => array(
					'name' => '军官证',
					'value' => '军官证',
				),
				'护照' => array(
					'name' => '护照',
					'value' => '护照',
				),
				'其他' => array(
					'name' => '其他',
					'value' => '其他',
				),
			);
			$validate_card_type_select = $FormHandler->Select('validate_card_type',$_options,$memberfields['validate_card_type']);

			Load::lib('form');
			$FormHandler = new FormHandler();
						$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
			while ($rsdb = $query->GetRow()){
				$province[$rsdb['id']]['value']  = $rsdb['id'];
				$province[$rsdb['id']]['name']  = $rsdb['name'];
				if($member['province'] == $rsdb['name']){
					$province_id = $rsdb['id'];
				}
			}
			$province_list = $FormHandler->Select("province",$province,$province_id,"onchange=\"changeProvince();\"");
			if($province_id){
				if($member['city']){
					$hid_city = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '$member[city]' and upid = $province_id");				}
				
				if($hid_city){
					if($member['area']){
						$hid_area = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '$member[area]' and upid = $hid_city");					}
					
					if($hid_area){
							if($member['street']){
							$hid_street = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '$member[street]' and upid = $hid_area");						}
					}
				}
			}
		}
		
		elseif('user_medal'==$act){
			
    		if($this->Config[sina_enable] && sina_weibo_init($this->Config))
    		{ 
    			$sina = sina_weibo_has_bind(MEMBER_ID);
    		}
    		
    		if($this->Config[imjiqiren_enable] && imjiqiren_init($this->Config))
    		{
    			$imjiqiren = imjiqiren_has_bind(MEMBER_ID);	
    		}	
    		if($this->Config[sms_enable] && sms_init($this->Config))
    		{
    			$sms = sms_has_bind(MEMBER_ID);	
    		}
			if($this->Config[qqwb_enable] && qqwb_init($this->Config))
			{
				$qqwb = qqwb_bind_icon(MEMBER_ID);
			}
    	
			$sql = "select  MD.medal_img , MD.medal_name ,  UM.* from `".TABLE_PREFIX."user_medal` UM left join `".TABLE_PREFIX."medal` MD on UM.medalid=MD.id where UM.uid='".MEMBER_ID." ' ";
			$query = $this->DatabaseHandler->Query($sql);
			$medal_list = array();
            $medal_ids = array();
			while($row = $query->GetRow())
			{ 
				$medal_list[] = $row;
                $medal_ids[$row['medalid']] = $row['medalid'];
			}
			
            $medal_ids_str = implode(",",$medal_ids);
            $_member = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='".MEMBER_ID."'");
            if($medal_ids_str != $_member['medal_id'])
            {
                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set medal_id='$medal_ids_str' where `uid`='".MEMBER_ID."'");
            }
		}
		elseif('exp'==$act){
			
			
						$experience = ConfigHandler::get('experience');
			$exp_list = $experience['list'];

						$mylevel = $member['level'];
			
						$my_credits = $member['credits'];
			
						foreach ($exp_list as $v) {
				if($my_credits >= $v['start_credits'])
				{	
					$my_level = $v['level'];
				}
			}
			
			if($mylevel !=  $my_level)
			{
								$sql = "update `" . TABLE_PREFIX . "members` set `level`='{$my_level}' where `uid`='".MEMBER_ID."'";
           		$this->DatabaseHandler->Query($sql);
		
           		$sql = "select `level` from `" . TABLE_PREFIX .
                "members` where `uid`='" . MEMBER_ID . "' ";
	            $query = $this->DatabaseHandler->Query($sql);
	            $members = $query->GetRow();
	            
	            $member['level'] = $members['level'];
 
           		
			}
				
						$my_level_begin_credits = $exp_list[$my_level]['start_credits'];

			
						$next_level = $my_level + 1; 
			$next_level_begin_credits = $exp_list[$next_level]['start_credits'];
			
			
						$my_exp = $my_credits - $my_level_begin_credits;
			
			
						$nex_exp = $next_level_begin_credits - $my_level_begin_credits;
			
						
						$level_width_arr = array(
			
					'1' => '27',
					'2' => '31',
					'3' => '45',
					'4' => '51',
					'5' => '62',
					'6' => '68',
					'7' => '77',
					'8' => '82',
					'9' => '93',
					'10' => '107',
			
			);
						$level_width = $my_exp * $level_width_arr[$my_level] / $nex_exp;
			
						$exp_width_arr = array(
			
					'1'  => '15',
					'2'  => '41',
					'3'  => '72',	
					'4'  => '116',	
					'5'  => '166',	
					'6'  => '229',	
					'7'  => '296',	
					'8'  => '372',	
					'9'  => '451',	
					'10' => '545',			
			);
			
			$exp_width = 100*(($level_width + $exp_width_arr[$my_level])/569);
			
			$nex_exp_credit = $next_level_begin_credits - $my_credits;
	
		}
		
		elseif('notice' == $act)
		{

						$jiange_options = array(
				'单条即时通知' => array ('name' => '单条即时通知','value' => '0',),
				'每天通知一次' => array('name' 	=> '每天通知一次','value' => '86400',),
				'每周汇总通知' => array('name' 	=> '每周汇总通知','value' => '604800',),
			);
			$user_notice_time = $FormHandler->Select('user_notice_time',$jiange_options,$member['user_notice_time']);

		}

		
		elseif ('qqrobot' == $act)
		{
			if( empty($member['qq'])) {
				$qq_code = $member['uid']."j".md5($member['password'].$member['username']);
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

		$this->Title = $act_list[$act];
		include($this->TemplateHandler->Template('setting_main'));
	}

    
    function DoModifyFace()
    {
        
        if(MEMBER_ID < 1)
        {
            $this->Messager("请先登录或者注册一个帐号",null);
        }

        
        $src_x = max(0,(int) $this->Post['x']);
        $src_y = max(0,(int) $this->Post['y']);
        $src_w = max(0,(int) $this->Post['w']);
        $src_h = max(0,(int) $this->Post['h']);
        $src_file = $this->Post['img_path'];

        
        Load::lib('io');
        $IoHandler = new IoHandler();

        
        if(!is_image($src_file))
        {
            $IoHandler->DeleteFile($src_file);

            $this->Messager("请上传正确的图片文件");
        }

        
        $image_path = RELATIVE_ROOT_PATH . 'images/face/' . face_path(MEMBER_ID);
        if(!is_dir($image_path))
        {
            $IoHandler->MakeDir($image_path);
        }

        
        $image_file = $dst_file = $image_path . MEMBER_ID . '_b.jpg';
        $make_result = makethumb($src_file,$dst_file,max(50,min(128,$src_w)),max(50,min(128,$src_w)),0,0,$src_x,$src_y,$src_w,$src_h);

        
        $image_file_small = $dst_file = $image_path . MEMBER_ID . '_s.jpg';
        $make_result = makethumb($src_file,$dst_file,50,50,0,0,$src_x,$src_y,$src_w,$src_h);
        
        
                $face_url = '';
        if($this->Config['ftp_on'])
        {
            $face_url = ConfigHandler::get('ftp','attachurl');
            
            $ftp_result = ftpcmd('upload',$image_file);
            if($ftp_result > 0)
            {
                ftpcmd('upload',$image_file_small);
                
                $IoHandler->DeleteFile($image_file);
                $IoHandler->DeleteFile($image_file_small);
            }
        }
        

        
        $sql = "update `".TABLE_PREFIX."members` set `face_url`='{$face_url}', `face`='{$dst_file}' where `uid`='".MEMBER_ID."'";
		$this->DatabaseHandler->Query($sql);

        
        $IoHandler->DeleteFile($src_file);

        
        if($this->Config['extcredits_enable'] && MEMBER_ID > 0)
		{
			
			update_credits_by_action('face',MEMBER_ID);
		}

        
        $this->Messager("头像设置成功",'',0);
    }

	function DoModifyPassword()
	{
		$arr = array();
		$resendEmail = false;

		$password_old = $this->Post['password_old'];
		$password_new1 = $this->Post['password_new1'];
		$password_new2 = $this->Post['password_new2'];

		

		if(!$password_new1)
		{
			$this->Messager("请输入新密码",-1);
		}
		if ($password_new1!=$password_new2) {
			$this->Messager("两次输入的密码不一致",-1);
		}
		if($password_new1 && $password_new1!=$password_old) {
			if(strlen($password_new1) < 5) {
				$this->Messager("为了您帐户的安全，请设置5位以上的密码",-1);
			}

			$arr['password'] = md5($password_new1);
		}

		

				if(true === UCENTER) {
						include_once(ROOT_PATH . 'uc_client/client.php');

			$ucresult_pw = uc_user_edit($this->Member['username'], $password_old, $password_new1, $this->Member['email']);
			if($ucresult_pw == -1) {
				$this->Messager('旧密码不正确',-1);
			} elseif($ucresult_pw == -4) {
				$this->Messager('Email 格式有误',-1);
			} elseif($ucresult_pw == -5) {
				$this->Messager('Email 不允许注册',-1);
			} elseif($ucresult_pw == -6) {
				$this->Messager('该 Email 已经被注册',-1);
			}
		}

		$this->_update($arr);

		$message[] = "密码修改成功，重新登录";
		$this->Messager($message,'index.php',1);
	}

	function DoModifyProfile()
	{
        foreach($this->Post as $key=>$val)
        {
            $key = strip_tags($key);
            $val = strip_tags($val);

            $this->Post[$key] = $val;
        }
		
        $nickname = trim($this->Post['nickname']);
		
		$province = trim($this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['province'])); 		$city = trim($this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['city']));		if($this->Post['area']){
			$area = trim($this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['area']));		}
		if($this->Post['street']){
			$street = trim($this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['street']));		}

		$gender = in_array(($gender = (int) $this->Post['gender']),array(1,2)) ? $gender : 0;
		$email2 = preg_match("~^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$~i",($email2 = trim($this->Post['email2']))) ? $email2 : '';
		$qq = (($qq = is_numeric($this->Post['qq']) ? $this->Post['qq'] : 0) > 10000 && strlen((string) $qq) < 11) ? $qq : '';
		$msn = trim(strip_tags($this->Post['msn']));

		$aboutme = strlen(($aboutme = trim(strip_tags($this->Post['aboutme'])))) > 255 ? cut_str($aboutme,254) . ' ' : $aboutme;
		
						$signature = $signature = trim(strip_tags($this->Post['signature']));
				if(($filter_msg = filter($aboutme)))
        {
            $this->Messager($filter_msg,null);
        }
		
        		if(($filter_msg = filter($signature)))
        {
            $this->Messager($filter_msg,null);
        }
 
        		if(($filter_msg = filter($nickname)))
        {
            $this->Messager($filter_msg,null);
        }

		if (!$province || !$city || !$gender)
        {
			$this->Messager("省所在地和性别不能为空，请返回修改",-1);
		}

				$sql = "select `uid`,`nickname`,`validate` from `".TABLE_PREFIX."members` where `nickname`='{$nickname}'";
		$query = $this->DatabaseHandler->Query($sql);
		$nickname_exists=$query->GetRow();

		if($nickname_exists && $nickname_exists['uid']!=MEMBER_ID)
		{
			$this->Messager("姓名/昵称(<b>{$nickname}</b>)已经存在，<A HREF='javascript:history.go(-1)'>请选择其它姓名/昵称</A>",null);
		}
		
		


		$arr = array (
			'province' => addslashes($province),
			'city' => addslashes($city),
			'area' => addslashes($area),
			'street' => addslashes($street),
			'gender' => $gender,
			'nickname' => $nickname,
			'aboutme' => addslashes($aboutme),
			'signature' => addslashes($signature),
		);
		$this->_update($arr);

		$arr1 = array();
		$sql = "select * from `".TABLE_PREFIX."memberfields` where `uid`='".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		$memberfields = $query->GetRow();
		if (!$memberfields['validate_true_name'] && $this->Post['validate_true_name'])
        {
			$arr1['validate_true_name'] = $this->Post['validate_true_name'];
		}
		if (!$memberfields['validate_card_type'] && $this->Post['validate_card_type'])
        {
			$arr1['validate_card_type'] = $this->Post['validate_card_type'];
		}
		if (!$memberfields['validate_card_id'] && $this->Post['validate_card_id'])
        {
			$arr1['validate_card_id'] = $this->Post['validate_card_id'];
		}
		if ($arr1)
        {
			$sets = array();
			if (is_array($arr1))
            {
				foreach ($arr1 as $key=>$val)
                {
					$val = addslashes($val);
					$sets[$key] = "`{$key}`='{$val}'";
				}
			}
			$sql = "update `".TABLE_PREFIX."memberfields` set ".implode(" , ",$sets)." where `uid`='".MEMBER_ID."'";
			
			$this->DatabaseHandler->Query($sql);
		}


		$this->Messager("修改成功",'',1);
	}

		function DoModifyEmail()
	{
		$arr = array();
		$resendEmail = false;

		$password_old = $this->Post['password_old'];
		$email_new = $this->Post['email_new'];

		if (!$password_old || md5($password_old)!=$this->Member['password']) {
			$this->Messager("请正确输入原先的密码",-1);
		}

		if ($email_new && $email_new!=$this->Member['email'])
		{
				if (!preg_match("~^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$~i",$email_new)) {
					$this->Messager("Email 地址的格式错误，请返回重新填写",-1);
				}

								$email_host=strstr($email_new,'@');
				if (stristr($this->Config['reg_email_forbid'],$email_host)!==false) {
					$this->Messager("由于您所填写的email所在的服务器会过滤本站程序发送的有效邮件，所以请返回重新填写一个EMAIL地址。",-1,10);
				}

								$sql="select uid from ".TABLE_PREFIX.'members'." WHERE email='{$email_new}'";
				$query = $this->DatabaseHandler->Query($sql);
				$row=$query->GetRow();
				if ($row!=false) {
					$this->Messager("对不起，你输入的EMAIL地址已经存在，请重新输入。",-1,10);
				}

				if($this->Config['reg_email_verify']==1) {
										$arr['role_id'] = $this->Config['no_verify_email_role_id'];

					$resendEmail = true;
				}

				$arr['email'] = $email_new;

								if($resendEmail) {
					Load::functions('my');

					my_member_validate($this->Member['uid'],$email_new,(int) ($this->Member['role_id']!=$this->Config['no_verify_email_role_id'] ? $this->Member['role_id'] : $this->Config['normal_default_role_id']));

					$message=array();
					$message[]="Email 重新激活验证的方法已经发送到注册邮箱 <b>".$email."</b>，请用邮件中提供的方法进行激活。";
					$message[]="如果24小时内仍没有收到系统发送的系统邮件，请在个人设置/修改密码页面中重新提交或尝试更换成其他的email地址";
					$this->Messager($message,null);
				}
		}


		$this->_update($arr);

		$this->Messager('邮箱修改成功','index.php?mod=settings&code=base');
	}

		function DoNotice()
	{
		if (MEMBER_ID < 1) {
			$this->Messager(null,$this->Config['site_url'] . "/index.php?mod=login");
		}
		$notice_at			= $this->Post['notice_at'];
		$notice_pm			= $this->Post['notice_pm'] ;
		$notice_reply		= $this->Post['notice_reply'];
		$user_notice_time		= $this->Post['user_notice_time'];

		$sql = "update `".TABLE_PREFIX."members` set `notice_at`='{$notice_at}',`notice_pm`='{$notice_pm}',`notice_reply`='{$notice_reply}',`user_notice_time`='{$user_notice_time}' where `uid`='".MEMBER_ID."'";
		$this->DatabaseHandler->Query($sql);

		$this->Messager(null,"index.php?mod=settings&code=notice");

	}



	function _update($arr)
	{
		$sets = array();
		if (is_array($arr)) {
			foreach ($arr as $key=>$val) {
				$val = addslashes($val);
				$sets[$key] = "`{$key}`='{$val}'";
			}

			if ($sets) {
				$sql = "update `".TABLE_PREFIX."members` set ".implode(" , ",$sets)." where `uid`='".MEMBER_ID."'";
				$this->DatabaseHandler->Query($sql);
			}
		}
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
