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
 function get_device_type(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$type = 'other';
	if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
		$type = 'ios';
	}
	if(strpos($agent, 'android')){
		$type = 'android';
	}
	return $type;
}

$width = $_GET ['width'];
if(!$width)
{
	$width=320;
} 
if(get_device_type()=='ios'){
	$width=320;
} 


 //$width =320;
 //字体缩放
$ziti=14/460;
$fonts=$ziti*$width;
$ziti=16/460;
$fonts1=$ziti*$width;

$input=450/460;
$inputc=$input*$width-30;
 
 $imgwidth=$width-30;
?>

<html xmlns="http://www.w3.org/1999/xhtml"><style type="text/css">
.centertl {
	font-size: <?php echo $fonts;?>px;
	font-weight: 600;
}
.tptitle {
	font-size: <?php echo $fonts;?>px;
	font-weight: 600;
	color: #999;
}
.tptitle1 {
	font-size: <?php echo $fonts1;?>px;
	font-weight: 600;
	color: #999;
	padding-left:20px;
}
p {
	font-size: <?php echo $fonts;?>px; 
	color: #000;
}
h3 {
	font-size: <?php echo $fonts;?>px; 
	font-weight: 600;
	color: #000;
}
.inputc{width:<?php echo $inputc;?>px; margin-left:10px;}
</style> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<SCRIPT type=text/javascript src="images/jquery.js"></SCRIPT>
 <script type="text/javascript">
function getarea(val) { 
$.ajax({
		   type: "POST",
		   url: "wapgetdistr.php",
		   data: "val="+val,
		   success: function(msg){
			$('#city').html(msg);
			  
		   }
		});
} 
 
 function  CheckForm()
{  
 
  
if  (document.getElementById("family_name").value.length  ==  0)  {  
	alert("请输入您的姓!");
	document.getElementById("family_name").focus();
	return  false;
	} 
if  (document.getElementById("name").value.length  ==  0)  {  
	alert("请输入您的名字!");
	document.getElementById("name").focus();
	return  false;
	}
 if  (document.getElementById("phone").value.length  !=  11)  {  
	alert("请输入正确手机号!");
	document.getElementById("phone").focus();
	return  false;
	}
 
return  true;
}

 function showpicket(puname,type) {
	if(type=='add') 
	{
		$('#'+puname).val(parseInt($('#'+puname).val())+1);
	} 
	if(type=='sub') 
	{ 
		if($('#'+puname).val()>0){ 
			$('#'+puname).val(parseInt($('#'+puname).val())-1); 
		}else
		{
		  $('#'+puname).val(0); 
		}
	} 
 
 }
-->
 </script>
  <link rel="stylesheet" type="text/css" href="images/golf_ticket.css">  
<title></title> 
  
</head> 
<body >

<table width="<?php echo $width;?>" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
<td align="center">
<img src="images/huifengbanner.jpg" width="<?php echo $imgwidth;?>" >
</td>
</tr>
  <tr>
    <td ><!-- add the info layer functionality here -->
<div style="padding: 15px;">
     
      <h3 >观看2013汇丰冠军赛。</h3>
    <p >享有"亚洲大满贯赛"美誉的世界高尔夫锦标赛-汇丰冠军赛在观澜湖高尔夫球会奥拉沙宝成功举办一届之后，今年将重返上海并永久落户。第九届赛事将于2013年10月31日至11月3日日在上海佘山国际高尔夫俱乐部举行，该场地曾于2005年至2011年连续承办了七届汇丰冠军赛。</p>
    </div></td>
  </tr>
  
  <tr>
    <td  ><p style="padding: 15px;">*必填部分</p></td>
  </tr>
   
  <tr>
     <td >
    
      <form name="form1" id="form1" method="post" action="hfregac.php?ac=hf_reg"  onsubmit="return CheckForm()">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center" class="centertl">姓名</td>
       </tr>
       <tr>
         <td class="tptitle"><div style="padding-left:15px;">姓*</div></td>
       </tr>
       <tr>
         <td><label for="family_name"></label>
           <input name="family_name" id="family_name" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle"><div style="padding-left:15px;">名*</div></td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="name" id="name" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td align="center" class="centertl">联系信息</td>
       </tr>
       <tr>
         <td class="tptitle"><div style="padding-left:15px;">手机号码*</div></td>
       </tr>
       <tr>
         <td><label for="phone"></label>
           <input name="phone" id="phone" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td align="center" class="centertl">地址</td>
       </tr>
       <tr>
         <td class="tptitle"><div style="padding-left:15px;">地址*</div></td>
       </tr>
       <tr>
         <td><label for="address"></label>
           <input name="address" id="address" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle">&nbsp;</td>
       </tr>
       
       <tr>
         <td align="center" class="centertl">观看比赛</td>
       </tr>
       <tr>
         <td class="tptitle"><div style="padding-left:15px;">请选择观看比赛的日期(可多选)* <br>
           重复提交，以最后一次提交内容为准 
           *</div></td>
       </tr>
       <tr>
         <td height="25" align="right">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">普通票：</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">职业-业余配对赛：200元，10月30日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name2','add')"></td>
             <td><input name="name2" type="text" id="name2" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name2','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：500元，10月31日或11月1日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name3','add')"></td>
             <td><input name="name3" type="text" id="name3" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name3','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：1000元，11月2日或11月3日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name4','add')"></td>
             <td><input name="name4" type="text" id="name4" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name4','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：2000元，10月30-11月3日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name5','add')"></td>
             <td><input name="name5" type="text" id="name5" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name5','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">家庭票：</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">职业-业余配对赛：300元，10月30日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name6','add')"></td>
             <td><input name="name6" type="text" id="name6" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name6','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：900元，10月31日或11月1日 </div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name7','add')"></td>
             <td><input name="name7" type="text" id="name7" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name7','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：1800元，11月2日或11月3日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name8','add')"></td>
             <td><input name="name8" type="text" id="name8" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name8','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：3500元，10月30日-11月3日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name9','add')"></td>
             <td><input name="name9" type="text" id="name9" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name9','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">特殊票：</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">学生票：100元，10月30日或10月31日
           或11月1日</div></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name10','add')"></td>
             <td><input name="name10" type="text" id="name10" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name10','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td align="center"><input type="image" name="imageField" id="imageField" src="images/button.jpg"> <input name="width" value="<?php echo $width;?>" type="hidden"></td>
       </tr>
       <tr>
         <td align="center"><img src="images/hfbottom.jpg" width="<?php echo $imgwidth;?>" ></td>
       </tr>
      
     </table>
        </form>
     </td>
  </tr>
  
  <tr>
    <td style="font-size:12px; text-align:center;">购票热线:4008109966</td>
  </tr>
  
</table>
</body>