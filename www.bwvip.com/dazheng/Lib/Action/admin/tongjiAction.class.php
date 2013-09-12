<?php
/**
 *    #Case		bwvip
 *    #Page		tongjiAction.class.php (用户)
 *
 *    @author	Zhang Long
 *    @E-mail	123695069@qq.com
 */
class tongjiAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}
	
	
	public function reg()
	{
		echo "<h3>大正注册统计：</h3><hr>";
		
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0  limit 1 ");
		echo "<li>总注册人数：".$today_num4[0]['num']."</li>";
		
		echo "<br ><br >";
		
		
		
		
		$today_s=strtotime(date("Y-m-d",(time()-86400*1))." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-86400*1))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		
		echo "<li>".$day2."(昨天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		echo "<li>".$day."(今天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='0' and app_log_mod='login' group by uid ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
	
		echo "<li>已登录客户端总数：".$today_num4[0]['num']."  　  </li>";
		
		echo "<br /><br /><br /><br /><br />";
		
		
		
		
		echo "<h3>美兰湖注册统计：</h3><hr>";
		
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186  limit 1 ");
		echo "<li>总注册人数：".$today_num4[0]['num']."</li>";
		
		echo "<br><br>";
		
		
		
		
		$today_s=strtotime(date("Y-m-d",(time()-86400*1))." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-86400*1))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		
		echo "<li>".$day2."(昨天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		echo "<li>".$day."(今天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1186' and app_log_mod='login' group by uid ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		echo "<li>已登录客户端总数：".$today_num4[0]['num']."   </li>";
		
		
		echo "<br /><br /><br /><br /><br />";
		
		echo "<h3>南山注册统计：</h3><hr>";
		
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160  limit 1 ");
		echo "<li>总注册人数：".$today_num4[0]['num']."</li>";
		
		echo "<br><br>";
		
		
		
		
		$today_s=strtotime(date("Y-m-d",(time()-86400*1))." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-86400*1))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		
		echo "<li>".$day2."(昨天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') group by uid  ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}
		
		$today_num5=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' group by uid  ");
		if(!$today_num5[0]['num'])
		{
			$today_num5[0]['num']=0;
		}
		
		$today_num6=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' group by uid  ");
		if(!$today_num6[0]['num'])
		{
			$today_num6[0]['num']=0;
		}
		echo "<li>".$day."(今天)：".$today_num4[0]['num']." 　　　 (IOS：".$today_num5[0]['num']." / ANDROID：".$today_num6[0]['num'].")  </li>";
		
		
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",(time()-84000*6))." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from tbl_app_log where field_uid='1160' and app_log_mod='login' group by uid ");
		if(!$today_num4[0]['num'])
		{
			$today_num4[0]['num']=0;
		}

		echo "<li>已登录客户端总数：".$today_num4[0]['num']."   　 </li>";
		
		
		
		
		
		
	}
	
}
?>