<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>亚运会世锦赛选拔-66-赛赛事报名</title>
<link href="css/wap.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			
			
				<table border="0" width="65%" align="center" cellspacing="0" cellpadding="0" class="form_bg">
					<tr>
						<td class="t_l"></td>
						<td class="t_c"></td>
						<td class="t_r"></td>
					</tr>
					
					<tr>
						<td class="m_l"></td>
						<td class="m_c">
						
						<!--/页面内容开始-->
						
						
			<form name="act_form" action="wap_action.php" method="post">
				<table border="0" cellspacing="0" cellpadding="0"  class="add_form">
					<tr>
						<th style="width:40%;">姓名：</th>
					</tr>
					<tr>
						<td style="width:60%;">
							<input type="text" class="mod_input" name="baoming_realname"  maxlength="20">
						</td>
					</tr>
					
					<tr>
						<th>性别：</th>
					</tr>
					<tr>
						<td>
							<input type="radio" class="mod_input" name="baoming_sex" value="男" checked>男    
							<input type="radio" class="mod_input" name="baoming_sex" value="女">女    
							
						</td>
					</tr>
					
					<tr>
						<th>手机号：</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="mod_input" name="baoming_mobile" maxlength="18">
						</td>
					</tr>
					
					
					<tr>
						<th>邮箱：</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="mod_input" name="baoming_email"   maxlength="50">
						</td>
					</tr>
					
					
					
					<tr>
						<th>证件号：</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="mod_input" name="baoming_card"  maxlength="18">
						</td>
					</tr>
					
					
					<tr>
						<th>差点：</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="mod_input" name="baoming_chadian"  maxlength="10">
						</td>
					</tr>
					
					
					<tr>
						<th>请注明报名资格：</th>
					</tr>
					<tr>
						<td>
							<select name="baoming_zige" id="baoming_zige"  class="mod_select">
								<option value="">请选择</option>
								<option value="国家队现役运动员">国家队现役运动员</option>
								<option value="业余比赛前三名运动员">业余比赛前三名运动员</option>
								<option value="海外运动员">海外运动员</option>
								<option value="中高协推荐运动员">中高协推荐运动员</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<th>我要报名参加以下比赛：</th>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="fenzhan_ids[]"  id="fenzhan_ids" value="142" checked>第一场珠海金湾站(4.1-4.4) <br />
							<input type="checkbox" name="fenzhan_ids[]"  id="fenzhan_ids" value="143">第二场天津滨海湖站(4.29-5.2)
						</td>
					</tr>
					<tr>
						<th>是否自带球童：</th>
					</tr>
					<tr>
						<td>
							<input type="radio" class="mod_input" name="baoming_is_zidai_qiutong" value="Y" >是    
							<input type="radio" class="mod_input" name="baoming_is_zidai_qiutong" value="N" checked>否    
						</td>
					</tr>
					

					<tr>
						<td align="center" height="60">
							<input type="hidden" name="event_id" value="<?php echo $_G['gp_event_id']; ?>">
							<input type="hidden" name="uid" value="<?php echo $_G['gp_uid']; ?>">
							<input type="hidden" name="act" value="baoming_add">
							<input type="button" value="立即申请" class="btn" onclick="submit_pro();">
							<p></p>
						</td>
					</tr>
				</table>
			</form>
			
					<!--/页面内容结束-->
					</td>
						<td class="m_r"></td>
					</tr>
					
					<tr>
						<td class="b_l"></td>
						<td class="b_c"></td>
						<td class="b_r"></td>
					</tr>
				</table>


		</td>
	</tr>
</table>

<script>
function submit_pro()
{
	
	var lobj=document.act_form;
	if(lobj.baoming_realname.value=="")
	{
		alert("请填写姓名");
	}
	else if(lobj.baoming_sex.value=="")
	{
		alert("请填写性别");
	}
	else if(lobj.baoming_mobile.value=="")
	{
		alert("请填写手机号");
	}
	else if(lobj.baoming_email.value=="")
	{
		alert("请填写邮箱");
	}
	else if(lobj.baoming_card.value=="")
	{
		alert("请填写证件号");
	}
	else if(lobj.baoming_chadian.value=="")
	{
		alert("请填写差点");
	}
	else if(lobj.baoming_zige.value=="")
	{
		alert("请选择报名资格");
	}
	else if(lobj.fenzhan_ids.value=="")
	{
		alert("请选择分站");
	}
	else
	{
		lobj.submit();
	}
}


</script>

</body>
</html>
