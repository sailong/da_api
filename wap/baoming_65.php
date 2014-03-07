<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>2014城市挑战赛-65-赛事报名</title>
<link href="css/wap.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
		
				<table border="0" align="center" cellspacing="0" cellpadding="0" class="form_bg">
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
				<table border="0" cellspacing="0" cellpadding="0" class="add_form">
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
							<input type="radio"  name="baoming_sex"  value="男" checked >男
							<input type="radio"  name="baoming_sex"  value="女">女
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
						<th>分站：</th>
					</tr>
					<tr>
						<td>
							<?php 
							$arr=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州');
							?>
							<select name="fenzhan_ids" id="fenzhan_ids" class="mod_select">
								<option value="">请选择</option>
								<? 
								for($i=0; $i<count($arr); $i++)
								{
									echo '<option value="'.$arr[$i].'">'.$arr[$i].'</option>';	
								}
								?>
							</select>
							
						</td>
					</tr>
					
					
					
					<tr>
						<th>车主：</th>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="baoming_is_huang"  value="1" checked >是
							<input type="radio"  name="baoming_is_huang"  value="0">否
						</td>
					</tr>
					
					
					<tr>
						<td align="center" height="100">
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
		alert("请选择性别");
	}
	else if(lobj.baoming_chadian.value=="")
	{
		alert("请填写差点");
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
