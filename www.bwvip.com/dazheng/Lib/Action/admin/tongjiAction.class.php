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
		echo '<style>body{font-family:microsoft yahei;};</style>';
		echo "<br >";
		
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		//echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		$reg_all_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=0 limit 1 ");
		//echo "<li>总注册人数：".$reg_all_num[0]['num']."</li>";
		
		echo "<br >";

		//昨天
		$today_s=strtotime(date("Y-m-d",time()-86400)." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-86400)." 23:59:59");
		$login_yestoday_num=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_yestoday_num_iphone=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_yestoday_num_android=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		//今天
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$login_today_num=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_today_num_iphone=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_today_num_android=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		$login_all_num=M()->query("select uid from tbl_app_log where field_uid='0' and app_log_mod='login' and uid>0 group by uid ");
	
	
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		
		echo '<table width="800" border="0" bgcolor="#cccccc">
  <tr>
    <td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">大正登录注册统计</td>
  </tr>
  <tr>
    <td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">注册</td>
    <td width="35%" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.$today_num[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.$today_num2[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day3.'(前天)</td>
    <td bgcolor="#FFFFFF">'.$today_num3[0]['num'].'</td>
  </tr>
   <tr>
   
   
    <td bgcolor="#FFFFFF">'.date("Y-m-d",$today_s)."~".date("Y-m-d",$today_e).'(本周)</td>
    <td bgcolor="#FFFFFF">'.$today_num4[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">总注册人数</td>
    <td bgcolor="#FFFFFF">'.$reg_all_num[0]['num'].'</td>
  </tr>
 
  <tr>
    <td rowspan="3" bgcolor="#FFFFFF"  align="center">登录</td>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.count($login_yestoday_num).' 　　　 (IOS：'.count($login_yestoday_num_iphone).' / ANDROID：'.count($login_yestoday_num_android).')  </td>
  </tr>
  <tr>

    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.count($login_today_num).' 　　　 (IOS：'.count($login_today_num_iphone).' / ANDROID：'.count($login_today_num_android).')  </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">已登录客户端总人数</td>
    <td bgcolor="#FFFFFF">'.count($login_all_num).'</td>
  </tr>
</table>';
		

		echo "<br >";
		//美兰湖
		
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		//echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		$reg_all_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1186 limit 1 ");
		//echo "<li>总注册人数：".$reg_all_num[0]['num']."</li>";
		
		echo "<br >";

		//昨天
		$today_s=strtotime(date("Y-m-d",time()-86400)." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-86400)." 23:59:59");
		$login_yestoday_num=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_yestoday_num_iphone=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_yestoday_num_android=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		//今天
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$login_today_num=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_today_num_iphone=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_today_num_android=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		$login_all_num=M()->query("select uid from tbl_app_log where field_uid='1186' and app_log_mod='login' and uid>0 group by uid ");
	
	
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		
		echo '<table width="800" border="0" bgcolor="#cccccc">
  <tr>
    <td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">美兰湖登录注册统计</td>
  </tr>
  <tr>
    <td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">注册</td>
    <td width="35%" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.$today_num[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.$today_num2[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day3.'(前天)</td>
    <td bgcolor="#FFFFFF">'.$today_num3[0]['num'].'</td>
  </tr>
   <tr>
   
   
    <td bgcolor="#FFFFFF">'.date("Y-m-d",$today_s)."~".date("Y-m-d",$today_e).'(本周)</td>
    <td bgcolor="#FFFFFF">'.$today_num4[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">总注册人数</td>
    <td bgcolor="#FFFFFF">'.$reg_all_num[0]['num'].'</td>
  </tr>
 
  <tr>
    <td rowspan="3" bgcolor="#FFFFFF"  align="center">登录</td>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.count($login_yestoday_num).' 　　　 (IOS：'.count($login_yestoday_num_iphone).' / ANDROID：'.count($login_yestoday_num_android).')  </td>
  </tr>
  <tr>

    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.count($login_today_num).' 　　　 (IOS：'.count($login_today_num_iphone).' / ANDROID：'.count($login_today_num_android).')  </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">已登录客户端总人数</td>
    <td bgcolor="#FFFFFF">'.count($login_all_num).'</td>
  </tr>
</table>';
		
		echo "<br />";
		
		//南山
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		//echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		$reg_all_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=1160 limit 1 ");
		//echo "<li>总注册人数：".$reg_all_num[0]['num']."</li>";
		
		echo "<br >";

		//昨天
		$today_s=strtotime(date("Y-m-d",time()-86400)." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-86400)." 23:59:59");
		$login_yestoday_num=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_yestoday_num_iphone=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_yestoday_num_android=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		//今天
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$login_today_num=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_today_num_iphone=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_today_num_android=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		$login_all_num=M()->query("select uid from tbl_app_log where field_uid='1160' and app_log_mod='login' and uid>0 group by uid ");
	
	
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		
		echo '<table width="800" border="0" bgcolor="#cccccc">
  <tr>
    <td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">南山登录注册统计</td>
  </tr>
  <tr>
    <td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">注册</td>
    <td width="35%" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.$today_num[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.$today_num2[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day3.'(前天)</td>
    <td bgcolor="#FFFFFF">'.$today_num3[0]['num'].'</td>
  </tr>
   <tr>
   
   
    <td bgcolor="#FFFFFF">'.date("Y-m-d",$today_s)."~".date("Y-m-d",$today_e).'(本周)</td>
    <td bgcolor="#FFFFFF">'.$today_num4[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">总注册人数</td>
    <td bgcolor="#FFFFFF">'.$reg_all_num[0]['num'].'</td>
  </tr>
 
  <tr>
    <td rowspan="3" bgcolor="#FFFFFF"  align="center">登录</td>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.count($login_yestoday_num).' 　　　 (IOS：'.count($login_yestoday_num_iphone).' / ANDROID：'.count($login_yestoday_num_android).')  </td>
  </tr>
  <tr>

    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.count($login_today_num).' 　　　 (IOS：'.count($login_today_num_iphone).' / ANDROID：'.count($login_today_num_android).')  </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">已登录客户端总人数</td>
    <td bgcolor="#FFFFFF">'.count($login_all_num).'</td>
  </tr>
</table>';
		
		echo "<br /><br /><br /><br /><br />";
		
		
		
		
		
		
		
		//华彬
		$day=date("Y-m-d",time());
		$today_s=strtotime($day." 00:00:00");
		$today_e=strtotime($day." 23:59:59");
		$today_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=3803491 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day."(今天)：".$today_num[0]['num']."</li>";
		
		
		$day2=date("Y-m-d",(time()-84000*1));
		$today_s=strtotime($day2." 00:00:00");
		$today_e=strtotime($day2." 23:59:59");
		$today_num2=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=3803491 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day2."(昨天)：".$today_num2[0]['num']."</li>";
		
		$day3=date("Y-m-d",(time()-84000*2));
		$today_s=strtotime($day3." 00:00:00");
		$today_e=strtotime($day3." 23:59:59");
		$today_num3=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=3803491 and regdate>'".$today_s."' and regdate<'".$today_e."' limit 1 ");
		//echo "<li>".$day3."(前天)：".$today_num3[0]['num']."</li>";
		
		
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		$today_num4=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=3803491 and regdate>'".$today_e."' and regdate<'".$today_s."' limit 1 ");
		//echo "<li>".date("Y-m-d",$today_s)."　～　".date("Y-m-d",$today_e)."(本周)：".$today_num4[0]['num']."</li>";
		
		
		$reg_all_num=M()->query("select count(uid) as num from pre_common_member_profile where reg_source=3803491 limit 1 ");
		//echo "<li>总注册人数：".$reg_all_num[0]['num']."</li>";
		
		echo "<br >";

		//昨天
		$today_s=strtotime(date("Y-m-d",time()-86400)." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-86400)." 23:59:59");
		$login_yestoday_num=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_yestoday_num_iphone=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_yestoday_num_android=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		//今天
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time())." 23:59:59");
		$login_today_num=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and (user_agent='iPhone' or user_agent='Android') and uid>0 group by uid ");
		$login_today_num_iphone=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='iPhone' and uid>0 group by uid ");
		$login_today_num_android=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and app_log_addtime<'".$today_e."' and app_log_addtime>'".$today_s."' and user_agent='Android' and uid>0 group by uid ");
		
		$login_all_num=M()->query("select uid from tbl_app_log where field_uid='3803491' and app_log_mod='login' and uid>0 group by uid ");
	
	
		$today_s=strtotime(date("Y-m-d",time())." 00:00:00");
		$today_e=strtotime(date("Y-m-d",time()-84000*6)." 23:59:59");
		
		echo '<table width="800" border="0" bgcolor="#cccccc">
  <tr>
    <td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">华彬登录注册统计</td>
  </tr>
  <tr>
    <td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">注册</td>
    <td width="35%" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.$today_num[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.$today_num2[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">'.$day3.'(前天)</td>
    <td bgcolor="#FFFFFF">'.$today_num3[0]['num'].'</td>
  </tr>
   <tr>
   
   
    <td bgcolor="#FFFFFF">'.date("Y-m-d",$today_s)."~".date("Y-m-d",$today_e).'(本周)</td>
    <td bgcolor="#FFFFFF">'.$today_num4[0]['num'].'</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">总注册人数</td>
    <td bgcolor="#FFFFFF">'.$reg_all_num[0]['num'].'</td>
  </tr>
 
  <tr>
    <td rowspan="3" bgcolor="#FFFFFF"  align="center">登录</td>
    <td bgcolor="#FFFFFF">'.$day2.'(昨天)</td>
    <td bgcolor="#FFFFFF">'.count($login_yestoday_num).' 　　　 (IOS：'.count($login_yestoday_num_iphone).' / ANDROID：'.count($login_yestoday_num_android).')  </td>
  </tr>
  <tr>

    <td bgcolor="#FFFFFF">'.$day.'(今天)</td>
    <td bgcolor="#FFFFFF">'.count($login_today_num).' 　　　 (IOS：'.count($login_today_num_iphone).' / ANDROID：'.count($login_today_num_android).')  </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">已登录客户端总人数</td>
    <td bgcolor="#FFFFFF">'.count($login_all_num).'</td>
  </tr>
</table>';
		
		echo "<br /><br /><br /><br /><br />";
		
		
		
		
		
		
	}
	
}
?>