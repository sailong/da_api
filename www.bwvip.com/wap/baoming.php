<?
/*
*
*	报名页面
*
*/
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();


//echo date("Y-m-d G:i:s","1366619650");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>赛事报名</title>
<link href="css/wap.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<form name="act_form" action="wap_action.php" method="post">
				<table border="0" width="80%" cellspacing="0" cellpadding="0"  class="add_form">
					<tr>
						<th style="width:40%;">姓名：</th>
						<td  style="width:60%;">
							<input type="text" class="mod_input" name="event_apply_realname" style="width:100%;" maxlength="20">
						</td>
					</tr>
					<tr>
						<th>性别：</th>
						<td>
							<input type="radio"  name="event_apply_sex"  value="男" checked >男
							<input type="radio"  name="event_apply_sex"  value="女">女
						</td>
					</tr>
					<tr>
						<th>身份证号：</th>
						<td>
							<input type="text" class="mod_input" name="event_apply_card" style="width:100%;" maxlength="18">
						</td>
					</tr>
					<tr>
						<th>差点：</th>
						<td>
							<input type="text" class="mod_input" name="event_apply_chadian" style="width:100%;" maxlength="10">
						</td>
					</tr>
					<tr>
						<th>分站：</th>
						<td>
							<?php 
							$arr=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州');
							?>
							<select name="event_apply_fenzhan" id="event_apply_fenzhan">
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
						<td>
							<input type="radio"  name="event_apply_is_huang"  value="1" checked >是
							<input type="radio"  name="event_apply_is_huang"  value="0">否
						</td>
					</tr>
					<tr>
						<th></th>
						<td>
							<input type="hidden" name="event_id" value="<?php echo $_G['gp_event_id']; ?>">
							<input type="hidden" name="uid" value="<?php echo $_G['gp_uid']; ?>">
							<input type="hidden" name="act" value="baoming_add">
							<input type="button" value="添&nbsp;&nbsp;&nbsp;&nbsp;加" class="btn" onclick="submit_pro();">
						</td>
					</tr>
				</table>
			</form>


		</td>
	</tr>
</table>

<script>
function submit_pro()
{
	var lobj=document.act_form;
	if(lobj.event_apply_realname.value=="")
	{
		alert("请填写姓名");
	}
	else if(lobj.event_apply_sex.value=="")
	{
		alert("请选择性别");
	}
	else if(lobj.event_apply_card.value=="")
	{
		alert("请填写身份证号");
	}
	else if(lobj.event_apply_chadian.value=="")
	{
		alert("请填写差点");
	}
	else if(lobj.event_apply_fenzhan.value=="")
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
