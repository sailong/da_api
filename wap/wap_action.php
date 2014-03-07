<?
/*
*
*
*	报名页面
*
*
*
*/
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();
$hot_2013district=array(
                 'tj511'=>'5/11天津',
                 'gz524'=>'5/24广州',
                 'sz531'=>'5/31深圳',
                 'hz615'=>'6/15杭州',
                 'sh621'=>'6/21上海',
                 'cs629'=>'6/29长沙',
                 'bj719'=>'7/19北京',
                 'dl726'=>'7/26大连',
                 'zz89'=>'8/9郑州',
                 'cd824'=>'8/24成都',
                 'sz830'=>'8/30苏州',
                 'fz97'=>'9/7福州',);


				 
if($_G['gp_act']=="baoming_add")
{

	$event_id=$_G['gp_event_id'];
	$uid=$_G['gp_uid'];

	if($event_id==25)
	{
	
		$fenzhan=array_search(urldecode($_G['gp_event_apply_fenzhan']),$hot_2013district);

		$bm=DB::fetch_first("select bm_id from pre_home_dazbm where uid='".$_G['gp_uid']."' and hot_district='".$fenzhan."'  and year='2014' ");
		if(!$bm['bm_id'])
		{
			if(urlencode($_G['gp_event_apply_sex'])=="男")
			{
				$sex=1;
			}
			else
			{
				$sex=2;
			}
			//$fenzhan=urldecode($_G['gp_event_apply_fenzhan']);
		
			$mobile=DB::result_first("select mobile from ".DB::table("common_member_profile")." where uid='".$_G['gp_uid']."' ");
			
			$data_bm['uid']=$_G['gp_uid'];
			$data_bm['realname']   = urldecode($_G['gp_event_apply_realname']);         //真实姓名
			$data_bm['gender']     = $sex;            //1 男 2 女
			//$data_bm['credentials_num']    = $_G['gp_event_apply_card'];     //证件号码
			$data_bm['hot_district']    = $fenzhan;

			$data_bm['cahdian']    = !empty ( $_G['gp_event_apply_chadian'] ) ? $_G['gp_event_apply_chadian'] : '';              //差点
			$data_bm['moblie']             = $mobile;
			$data_bm['is_huang']             =  $_G['gp_event_apply_is_huang']; //是否车主
			$data_bm['nationality']='中国';     //国籍
			$data_bm['addtime']= time(); 
			$data_bm['game_s_type']= 1000333; 
			$data_bm['year']= '2014';

			DB::insert('home_dazbm',$data_bm,true);
			echo "<script>location='baoming_msg.php?msg=ok';</script>";
		}
		else
		{
			echo "<script>alert('请选择分站');location='baoming.php?event_id=".$event_id."&uid=".$_G['gp_uid']."';</script>";
		}
	}
	
	
	
	//2014城市挑战赛
	if($event_id==65)
	{
		$baoming_info=DB::fetch_first("select baoming_id from tbl_baoming where uid='".$_G['gp_uid']."' and event_id='".$event_id."' ");
		if(!$baoming_info['baoming_id'])
		{
			
			$fenzhan_ids=implode(",",$_POST['fenzhan_ids']);
			$sql="insert into tbl_baoming (event_id,uid,baoming_realname,baoming_sex,baoming_is_huang,baoming_chadian,fenzhan_ids,baoming_addtime) values('".$event_id."','".$uid."','".$_G['gp_baoming_realname']."','".$_G['gp_baoming_sex']."','".$_G['baoming_is_huang']."','".$_G['gp_baoming_chadian']."','".$fenzhan_ids."','".time()."') ";
			
			//echo $sql;
			DB::query($sql);
			
			echo "<script>location='baoming_msg.php?msg=ok';</script>";
			
		
		}
		else
		{
			echo "<script>alert('不能重复报名');location='baoming.php?event_id=".$event_id."&uid=".$_G['gp_uid']."';</script>";
		}
		
	
	}
	
	
	
	//亚运会
	if($event_id==66)
	{
		$baoming_info=DB::fetch_first("select baoming_id from tbl_baoming where uid='".$_G['gp_uid']."' and event_id='".$event_id."' ");
		if(!$baoming_info['baoming_id'])
		{
		
			/* if(urldecode($_G['gp_baoming_is_zidai_qiutong'])=="是")
			{
				$baoming_is_zidai_qiutong='1';
			}
			else
			{
				$baoming_is_zidai_qiutong='0';
			} */
			
			$fenzhan_ids=implode(",",$_POST['fenzhan_ids']);
			$sql="insert into tbl_baoming (event_id,uid,baoming_realname,baoming_sex,baoming_card,baoming_mobile,baoming_email,baoming_chadian,baoming_zige,baoming_is_zidai_qiutong,fenzhan_ids,baoming_addtime) values('".$event_id."','".$uid."','".$_G['gp_baoming_realname']."','".$_G['gp_baoming_sex']."','".$_G['gp_baoming_card']."','".$_G['gp_baoming_mobile']."','".$_G['gp_baoming_email']."','".$_G['gp_baoming_chadian']."','".$_G['gp_baoming_zige']."','".$_G['gp_baoming_is_zidai_qiutong']."','".$fenzhan_ids."','".time()."') ";
			
			//echo $sql;
			DB::query($sql);
			
			echo "<script>location='baoming_msg.php?msg=ok';</script>";
			
		
		}
		else
		{
			echo "<script>alert('不能重复报名');location='baoming.php?event_id=".$event_id."&uid=".$_G['gp_uid']."';</script>";
		}
		
	
	}


	

}

?>