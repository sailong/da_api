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
	
	
	
	
	public function topic_tongji()
	{
	
		$page = intval(get("p"))?get("p"):1;
		$page_size=20;

		$sort=" dateline desc ";
		
		if(get('k'))
		{
			$where = " 1 and content like '%#".get('k')."#%' ";
		}
		else
		{
			$where = " 1 and content like '%LPGA%' ";
		}
		//$where = " 1 and content like '%#华彬LPGA中国精英赛#%' ";
		

		if(get("starttime")!="")
		{
			$where .=" and dateline>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and dateline<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("topic","jishigou_")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		

		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["uid"]!="")
			{
				$user=M()->query("select realname,mobile from pre_common_member_profile where uid='".$data["item"][$i]["uid"]."' ");
				$data["item"][$i]["realname"]=$user[0]["realname"];
				$data["item"][$i]["mobile"]=$user[0]["mobile"];
			}
		}
		
		$data["total"] = M("topic","jishigou_")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();
		
		$this->assign("list",$data["item"]);
		$this->assign("pages",$data["pages"]);
		$this->assign("total",$data["total"]);
	
		$this->display();
	}
	
	
	
	
	public function ticket_login()
	{
		$event_id=get('event_id');
	
		$user_ticket_list=M()->query("select * from tbl_user_ticket where event_id='".$event_id."' and ip is null group by uid limit 700");
	
		echo "select * from tbl_user_ticket where event_id='".$event_id."' group by uid  ";
		echo "<hr>";
		for($i=0; $i<count($user_ticket_list); $i++)
		{
			$ip=M()->query("select ip from tbl_app_log where uid='".$user_ticket_list[$i]['uid']."' order by app_log_addtime desc limit 1 ");
			$ip=$ip[0]['ip'];
			
			

			//$city_info =get_city($ip);
			
			
			if($ip)
			{
				$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
				$ip_data=json_decode(file_get_contents($url));
				if((string)$ip_data->code=='1')
				{
				  //return false;
				}
				$city_info = (array)$ip_data->data;
			
				$sheng=$city_info['region'];
				$city=$city_info['city'];
				
				$up=M()->query("update tbl_user_ticket set ip='".$ip."',sheng='".$sheng."',city='".$city."' where user_ticket_id='".$user_ticket_list[$i]['user_ticket_id']."' ");
				echo "update tbl_user_ticket set ip='".$ip."',sheng='".$sheng."',city='".$city."' where user_ticket_id='".$user_ticket_list[$i]['user_ticket_id']."' ";
				echo "<hr>";
			}
		}
		
		
		
	}
	
	
	
	public function ticket_city()
	{
		$event_id=get('event_id');
	
		$user_ticket_list=M()->query("select * from tbl_user_ticket where event_id='".$event_id."' group by sheng");

		for($i=0; $i<count($user_ticket_list); $i++)
		{
			if($user_ticket_list[$i]['sheng'])
			{
				echo $user_ticket_list[$i]['sheng'];
				
				$num=M("user_ticket")->where(" sheng='".$user_ticket_list[$i]['sheng']."' and  event_id='".$event_id."' ")->count();
				
				echo $num."人";
				

				echo "<br >";
			}
		}
		
		
		
	}
	
	
	function get_city($ip)
	{
		
		return $data;
	}
	
	
	
	public function login()
	{
	
		$first_time=M('app_log')->where(" app_log_mod='login' and (user_agent='iPhone' or user_agent='Android') ".$field_sql."  ")->order('app_log_addtime asc')->find();
		
		$tip_str ="<br /><br />第一条登录记录开始于：".date('Y-m-d G:i:s',$first_time['app_log_addtime'])."<br />";
		
		$tongji_str  .="<br /><br />时间用法举例： 2013-10-05 ~ 2013-10-07 这是3天。<br /><br /><br /><br /><table width='100%'  border=\"0\" cellspacing=\"1\" bgcolor=\"#666666\">";
		
		
		$field_sql=" and field_uid=0 ";
		
		$starttime=strtotime(get('starttime'));
		$endtime=strtotime(get('endtime'));
		$endtime=$endtime+86400;
		
		
		$timelong=($endtime-$starttime)/86400;
		
		$total_num=M('app_log')->where(" app_log_mod='login' and ( app_log_addtime>'".$starttime."' and app_log_addtime<'".$endtime."' ) and (user_agent='iPhone' or user_agent='Android') ".$field_sql."  ")->count();
		
		$tongji_str  .="<tr><th colspan=12 bgcolor='#ffffff' >总登录次数：".$total_num."</th></tr><tr>";
		
		for($i=0; $i<24; $i++)
		{
			
			$c_num=0;
			for($j=0; $j<($timelong+1); $j++)
			{
			
				$s_time=$starttime+(86400*($j))+(3600*$i);
				$e_time=$starttime+(86400*($j))+(3600*($i+1));
				
				$time_arr[$i][$j]=date("Y-m-d G:i:s",$s_time)." -- ".date("Y-m-d G:i:s",$e_time);
				$time_sql_arr[$i][$j]=" ( app_log_addtime>'".$s_time."' and app_log_addtime<'".$e_time."' ) ";
				//$tongji_str .= "<hr>";
				
			}
			
			$sql_str=implode(" or ",$time_sql_arr[$i]);
			$c_num=M('app_log')->where(" app_log_mod='login' and ( ".$sql_str." ) and (user_agent='iPhone' or user_agent='Android') ".$field_sql."  ")->count();
			$c_lv=number_format(($c_num/$total_num)*100,1);
			
			if($i==12)
			{
				$tongji_str .='</tr><tr><td  bgcolor="#ffffff" style="padding:10px 0;" align="center" ><b>'.$i. '时~'.($i+1). '时	'.$c_num.'	'.$c_lv.'%</td>';
			}
			else
			{
				$tongji_str .='<td  bgcolor="#ffffff"  style="padding:10px 0;"  align="center" ><b>'.$i. '时~'.($i+1). '时</b>	'.$c_num.'	'.$c_lv.'%</td>';
			}
			
			
		}
		
		$tongji_str .='</tr></table>';
		
		$this->assign('tip_str',$tip_str);
		$this->assign('tongji_str',$tongji_str);
		$this->display();
	}
	


	
}
?>