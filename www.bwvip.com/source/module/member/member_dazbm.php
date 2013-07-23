<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_dazbm.php 20126 2011-02-16 02:42:26Z angf $
 */

if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
/*比赛的基本设置  用于扩展*/
$setting_for_bm_init = array(
     'game_name'=>array('0'=>"皇冠比赛"),
	 'submit_value'=>array('add'=>"提交报名信息","edit"=>"修改报名信息"),
	 'bm_eidt_succeed_to_pay'=>"dazbm.php?set_last_page=yes",
	 'bm_eidt_succeed_to_home'=>"dazbm.php?set_last_page=yes",
);
$game_name = '1000333';


/*报名页面判断*/
$bm_page= getgpc('bm_page') ? getgpc('bm_page'):1;

/*赛场地区 start*/
$ajaxget_url = 'dazbm.php?d_action=get_sc_dq&aid=';
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
}

if($_GET['d_action']=="get_sc_dq"){
	$query = DB::query('select id, fieldname from '.DB::table('common_field')." where province='".$_GET['aid']."' order by id desc");
	while($list = mysql_fetch_assoc($query)) {
		$field[] = $list;
	}
	$option = "<option value='0'>请选择</option>";
	foreach($field as $k=>$v) {
	   $option .= "<option value='".$v['id']."'>".$v['fieldname']."</option>";
	}
    include template('common/header_ajax');
      echo "<select name='cj_saicahng' >".$option."</select>";
    include template('common/footer_ajax');

	exit;
}
/*赛场结束 end*/




$nbsp ="&nbsp;&nbsp;&nbsp;&nbsp;";
$bitian = "<font color=\"red\">*</font>";
/*
 * 提交数据判断
 */
if (isset ( $_G ['gp_do'] ) && ($_G ['gp_do'] == 'bm_apply' || $_G ['gp_do'] == 'edit_bm_info'  )) {

	/*上传文件处理*/
	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			$upload->init ( $file, 'profile' );
			$attach = $upload->attach;

			if (! $upload->error ()) {
				$upload->save ();

				if (! $upload->get_image_info ( $attach ['target'] )) {
					@unlink ( $attach ['target'] );
					continue;
				}

				$attach ['attachment'] = dhtmlspecialchars ( trim ( $attach ['attachment'] ) );
				if ($_G ['cache'] ['fields_register'] [$field_key] ['needverify']) {
					$verifyarr [$key] = $attach ['attachment'];
				} else {
					$profile [$key] = $attach ['attachment'];
				}
			}
		}
	}


	if($_G['uid']){


	   if($_G['gp_member_id']){
		   $_G['uid']=intval(getgpc('member_id'));
		   $form_ac ="&member_id=".$_G['gp_member_id'];
		}

		/*dz 的用户信息块*/
		$data_userinfo['realname']   = !empty($_G['gp_realname']) ? $_G['gp_realname'] : '';         //真实年龄
		$data_userinfo['gender']     = !empty($_G['gp_gender']) ? $_G['gp_gender'] : '1';            //1 男 2 女
		$data_userinfo['nationality']=!empty($_G['gp_nationality']) ? $_G['gp_nationality'] :'';     //国籍
		$data_userinfo['telephone']  =!empty($_G['gp_telephone']) ? $_G['gp_telephone'] : '';        //固定电话
		$data_userinfo['height']     =!empty($_G['gp_height']) ?  $_G['gp_height'] : '';             //身高
		$data_userinfo['address']    =!empty($_G['gp_address']) ? $_G['gp_address'] : '';            //通讯地址
		$data_userinfo['zipcode']    =!empty($_G['gp_zipcode']) ? $_G['gp_zipcode'] : '';            //邮编
		$data_userinfo['position']   =!empty($_G['gp_position']) ? $_G['gp_position'] : '';          //职务


		/*dazbm 大正报名信息块*/
		$data_bm['buy_car']            = intval(getgpc('buy_car'));      //是否买车
		$data_bm['car_impress']        = !empty ( $_G['gp_car_impress'] ) ? $_G['gp_car_impress'] : '';
		$data_bm['car_impress']        = is_array($data_bm['car_impress']) ? implode('|',$data_bm['car_impress']) : $data_bm['car_impress'] ;

		$data_bm['age']                = !empty ($_G['gp_age'] ) ? $_G['gp_age'] : "";                              //传真
		$data_bm['residecity_now']     = !empty($_G['gp_residecity_now']) ? $_G['gp_residecity_now'] : '已经不用这个字段'; //居住城市
		$data_bm['moblie']             = !empty($_G['gp_moblie']) ? $_G['gp_moblie'] : '';                          //手机号码
		$data_bm['fax']                = !empty ($_G['gp_fax'] ) ? $_G['gp_fax'] : "";                              //传真
		$data_bm['credentials']        = !empty ( $_G['gp_credentials'] ) ? $_G['gp_credentials'] : 1;              //证件 1身份证 2驾驶本
		$data_bm['car_brand']          = !empty ( $_G['gp_car_brand'] ) ? $_G['gp_car_brand'] : 0;
		$data_bm['credentials_photo']  = !empty ( $profile['credentials_photo'] ) ? $profile['credentials_photo'] : $_G['gp_credentials_photo']; //上传的证件图片
		//$data_bm['inform']             = !empty ( $_G['g_inform'] ) ? $_G['g_inform'] : 1;                        //报名提醒方式 1短信 2邮件
		$data_bm['credentials_num']    = !empty ( $_G['gp_credentials_num'] ) ? $_G['gp_credentials_num'] : '';     //证件号码
		$data_bm['company']            = !empty ( $_G['gp_company'] ) ? $_G['gp_company'] : '';                     //公司名称
		$data_bm['company_class']      = !empty ( $_G['gp_company_class'] ) ? $_G['gp_company_class'] : '';         //公司名称
		$data_bm['realname_photo']     = !empty ($profile['realname_photo']) ? $profile['realname_photo'] : $_G['gp_realname_photo'] ; //真实头像
		$data_bm['car_drive_pic']      = !empty ($profile['car_drive_pic'])  ? $profile['car_drive_pic']  : $_G['gp_car_drive_pic'];  //驾驶本照片
		$data_bm['birth']              = !empty ($_G['gp_birth']) ? $_G['gp_birth'] : '2012';                              //生日
		//年收入 1=》15万元以下 2=》15-20万元 3=》20-30万元 4=》30-50万元 5=》50万-100万 6=》100以上 0=>保密
		$data_bm['income']             = !empty ( $_G['gp_income'] ) ? $_G['gp_income'] : 1;
        $data_bm['is_treaty']          = !empty ($_G['gp_is_treaty']) ? $_G['gp_is_treaty'] : 1;                        //是否同意条款
	    $data_bm['is_join_c']          = !empty ($_G['gp_is_join_c']) ? $_G['gp_is_join_c'] : 0;                        //去年是否参加
		$data_bm['banklist']           =  is_array($_G['gp_banklist']) ?  implode(',',$_G['gp_banklist']) : 1;           //银行
		$data_bm['cj_province']        =  !empty ( $_G['gp_cj_province'] ) ? $_G['gp_cj_province'] : 1;                  //最好成绩所在城市
		$data_bm['cj_saicahng']        =  !empty ( $_G['gp_cj_saicahng'] ) ? $_G['gp_cj_saicahng'] : 1;                  //最好成绩所场地
	    //1=》手机短信 2=》赛事官网 3=》球场宣传页 4=》其他网站 5=》杂志或报刊广告
		$data_bm['accept_way']         = !empty ( $_G['gp_accept_way'] ) ? $_G['gp_accept_way'] : 1;                    //赛事信息获取途径
		$data_bm['car_type']           = !empty ( $_G['gp_car_type'] ) ? $_G['gp_car_type'] : '';                       //皇冠车型h_car_type：
	    $data_bm['h_car_type']         = !empty ( $_G['gp_h_car_type'] ) ? $_G['gp_h_car_type'] : '';                   //皇冠车型h_car_type：
		$data_bm['car_j_type']         = !empty ( $_G['gp_car_j_type'] ) ? $_G['gp_car_j_type'] : '';                   //皇冠车架号：
		$data_bm['car_marking_shop']   = !empty ( $_G['gp_car_marking_shop'] ) ? $_G['gp_car_marking_shop'] : '';       //所属经销店：
		$data_bm['ball_age']           = !empty ( $_G['gp_ball_age'] ) ? $_G['gp_ball_age'] : '';                       //球龄：
		$data_bm['inform_way']         = !empty ( $_G['gp_inform_way'] ) ? $_G['gp_inform_way'] : 1;                    //提醒方式
		$data_bm['best_score']         = !empty ( $_G['gp_best_score'] ) ? $_G['gp_best_score'] : '';                   //最好成绩（杆）
		$data_bm['game_type']          = !empty ( $_G['gp_game_type'] ) ? $_G['gp_game_type'] : '';                     //你希望参加哪个形式的比赛：1 网络选拔赛 2线下选拔赛
		$data_bm['hot_district']    = !empty ( $_G['gp_hot_district'] ) ? $_G['gp_hot_district'] : '';              //你希望参加哪个地区的分站赛 状态查看模板文件


		$data_bm['h_car_yinx']      = getgpc('h_car_yinx');
		$data_bm['konw_saishi']     = getgpc('konw_saishi');
		$data_bm['bianhua']         = getgpc('bianhua');
		$data_bm['hot_district']    = is_array($data_bm['hot_district']) ? implode('|',$data_bm['hot_district']) : $data_bm['hot_district'] ;
		$data_bm['tool_brand']      = !empty ( $_G['gp_tool_brand'] ) ? $_G['gp_tool_brand'] : '';                    //您拥有的球具品牌:
        $data_bm['tool_brand']      = is_array($data_bm['tool_brand']) ? implode('|',$data_bm['tool_brand'] ) : $data_bm['tool_brand'] ;
		$data_bm['faction']         = !empty ( $_G['gp_faction'] ) ? $_G['gp_faction'] : '';                             //您拥有哪个球会的会籍（可多填）：
		$data_bm['sure_realize']    = !empty ( $_G['gp_sure_realize'] ) ? $_G['gp_sure_realize'] : 1;               //您是否了解皇冠品牌 1 是 0 否
		$data_bm['sure_drive']      = !empty ( $_G['gp_sure_drive'] ) ? $_G['gp_sure_drive'] : 0;                     //您是否试驾过皇冠汽车： 1是 0否
		$data_bm['assess_price']    = !empty ( $_G['gp_assess_price'] ) ? $_G['gp_assess_price'] : 1;               //请问您对皇冠的评价: 1中级车 2中高级车 3中高级车
	    $data_bm['realize_club']     =  getgpc('realize_club');              //请问您是否知道别克俱乐部联赛（单选）: 0 不知道 1听说过 2 参加过
		$data_bm['realize_quattro']  =  getgpc( 'realize_quattro' );      //请问您是否知道奥迪QUATTRO杯高尔夫锦标赛（单选) 0 不知道 1听说过 2 参加过
		$data_bm['attract']    = !empty ( $_G['gp_attract'] ) ? $_G['gp_attract'] : 1;                       //本次比赛最吸引您的地方（多选）: 1,2,3,4 状态参考模板
        $data_bm['attract']    = is_array($data_bm['attract']) ?  implode('|',$data_bm['attract']) : $data_bm['attract'];
		$data_bm['pay_way']    = !empty ( $_G['gp_pay_way'] ) ? $_G['gp_pay_way'] : 1;                       //支付方式（多选）: 1 网银支付 2信用卡 3线下
		$data_bm['resideprovince'] = !empty ( $_G['gp_resideprovince'] ) ? $_G['gp_resideprovince'] : '';    //省id
		$data_bm['residecity'] = !empty ( $_G['gp_residecity'] ) ? $_G['gp_residecity'] : '';                //市id
		$data_bm['residedist'] = !empty ( $_G['gp_residedist'] ) ? $_G['gp_residedist'] : '';                //县id
		$data_bm['residecommunity'] = !empty ( $_G['gp_residecommunity'] ) ? $_G['gp_residecommunity'] : ''; //区id
		$data_bm['cahdian']         = intval(getgpc('cahdian'));

		if(getgpc('yiqi_shop')!='--请选择营销店--'){
		$data_bm['yiqi_shop']       = getgpc('yiqi_shop');}
		$data_bm['is_huang']        = getgpc('is_huang');
		$data_bm['num_qiu_hui']     = getgpc('num_qiu_hui');

		$data_bm['uid'] = $_G['uid'];
		$data_bm['game_s_type'] = $game_name;
		$data_bm['addtime'] = time();
		$data_bm_user =array_merge($data_userinfo,$data_bm);



        /*用户编辑报名信息*/
		if(isset($_G['gp_do']) && $_G['gp_do']=="edit_bm_info") {

			/*添加微博关注*/
			$hot_district = getgpc('hot_district');
			if(empty($hot_district)){
				$query =DB::query(" SELECT `uid` FROM ".DB::table('home_dazbm')." where hot_district like '%".$hot_district[0]."%' limit 5");
				while($result = DB::fetch($query)){
					$cha_query = DB::fetch_first( " SELECT  count(*) as num FROM jishigou_buddys  where uid='".$_G['uid']."'");
					if($cha_query['num']<=5){
						DB::query(" UPDATE  `jishigou_members` SET  `follow_count` = follow_count+1  where uid='".$_G['uid']."' or uid='".$result['uid']."'");
						DB::query("INSERT INTO jishigou_buddys SET `uid`='".$_G['uid']."',`buddyid`='".$result['uid']."'");
						DB::query("INSERT INTO jishigou_buddys SET `uid`='".$result['uid']."',`buddyid`='".$_G['uid']."'");
					}
				}
			}

			DB::update("home_dazbm",$data_bm_user, array('uid'=>$_G['uid'],'bm_id'=>$_G['gp_bm_id']));
            $new_bm_page = ($bm_page+1>4) ? $bm_page : $bm_page+1;
            header('Location: dazbm.php?bm_page='.$new_bm_page.$form_ac );

		}else{

			$uid_already = DB::fetch_first("SELECT `uid` FROM ".DB::table('home_dazbm')." WHERE uid='".$_G['uid']."' and game_s_type='".$game_name."'");

			if($uid_already['uid']) showmessage('bm_uid_already');

			$data_userinfo['mobile'] = $data_bm['moblie'];  //两个表的 手机字段不一样

			DB::update('common_member_profile',$data_userinfo,array('uid'=>$_G['uid']));

			DB::insert('home_dazbm',$data_bm_user,true);



			if(DB::insert_id()){
				 $new_bm_page = ($bm_page+1>4) ? $bm_page : $bm_page+1;
				 if($new_bm_page<4){
				   header('Location: dazbm.php?bm_page='.$new_bm_page.$form_ac);
				 }else{
					showmessage('bm_eidt_succeed_to_pay', $setting_for_bm_init['bm_eidt_succeed_to_pay']);
				 }
			}else{
				showmessage('apply_bm_info_error');
			}
		}
	}

} else {
	/*
	 * 报名模板页
	 */
	 $uid = $_G['uid'];
   	 if(empty($uid)) showmessage('no_register_for_page_dazbm','home.php?mod=spacecp');
     if($_G['gp_member_id']) {
		 $uid = getgpc('member_id');
		 $member_id =$uid;
	     $form_ac ="&member_id=".$uid;
	 }

	 /*引入城市区域列表*/
	 libfile('function/profile');
	 require_once libfile('function/profile');
	 $html = profile_setting('residecity', $space, $vid ? false : true);
	 if($html) {
	    $settings[$fieldid] = $_G['cache']['profilesetting'][$fieldid];
	    $htmls['residecity'] = $html;
	 }

	/*获取用户信息*/
	 $user_info  = DB::fetch_first(" SELECT cm.`email`,cmp.`uid`,cmp.`realname`,cmp.`mobile` FROM ".DB::table('common_member_profile')." as cmp LEFT JOIN ".DB::table('common_member')." as cm ON cm.uid = cmp.uid  where cmp.uid='".$uid."' limit 1");

	 /*获取 当前 用户 是否报名*/
	 $bm_info=array();
     $bm_info = DB::fetch_first("SELECT * FROM ".DB::table('home_dazbm')." WHERE uid='".$uid."' and game_s_type='".$game_name."' order by bm_id desc"); 
	 if($_G['gp_member_id'] && empty($bm_info)) showmessage('该用户 暂时还没有报名');

	 $background = "/static/space/comm/images/123.jpg";  //默认的报名 背景
	 if(!empty($bm_info) && $bm_info['apply_status']==0) $background = "/static/space/comm/images/123.jpg";  //审核中的背景
	 if(!empty($bm_info) && $bm_info['apply_status']==1) $background = "/static/space/comm/images/123.jpg";  //已经审核的背景
	 $bm_info['quyu'] = $bm_info['resideprovince']."-". $bm_info['residecity'].'-'.$bm_info['residedist'];

	 if(empty($bm_info['uid'])){
		  $submit_value=$setting_for_bm_init['submit_value']['add'];
	      $apply_do = "bm_apply";
	 }else{
		 $submit_value=$setting_for_bm_init['submit_value']['edit'];
		 $apply_do = "edit_bm_info";
     }


	 $set_last_page = getgpc('set_last_page');

	 /*取出车的品牌*/
	 $query = DB::query(" SELECT * FROM ".DB::table('car_brand'));
	 $bm_info['car_brand_option'] = "";
	 while($result_car_brand = DB::fetch($query)){
		$checked = $result_car_brand['brand_name'] == $bm_info['car_brand'] ? " selected " : '';
	    $bm_info['car_brand_option'].= "<option ".$checked." value=\"".$result_car_brand['brand_name']."\"   >".$result_car_brand['brand_name']."</option>";
	 }

	 include template ( 'member/dazbm' );
}

?>