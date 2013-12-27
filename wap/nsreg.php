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
//获取网址参数 
$strqurey=$_SERVER["QUERY_STRING"]; 
if(checkstr($strqurey)){ 
$strqurey=str_replace('?','&',$strqurey);
 header('Location: ?'.$strqurey);
}


function checkstr($str){
    $needle = "?";//判断是否包含?这个字符
    $tmparray = explode($needle,$str);
    if(count($tmparray)>1){
    return true;
    } else{
    return false;
    }
}
$width = $_GET ['width'];
if(!$width)
{
	$width=460;
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
<img src="images/nanshanbanner.jpg" width="<?php echo $imgwidth;?>" >
</td>
</tr>
  <tr>
    <td  ><!-- add the info layer functionality here -->
<div style="padding: 15px;">
     
      <h3 >观看2013中国大师赛。</h3>
      <p >2013年中国大师赛于山东省烟台市龙口南山高尔夫球会：10月7日，巨星荟萃，闪亮登场，激情挥杆！      </p>
      <p >尊敬的各位球友，让我们共同倒计时，共同迎接这澎湃时刻，共享辉煌，这个10月让我们和世界各国的明星，各大媒体，高层富商和国际球星欢度国庆！</p>
      </div>
    </td>
  </tr>
  
  <tr>
    <td ><p  style="padding: 15px;">*必填部分</p></td>
  </tr>
   
  <tr>
     <td >
    
      <form name="form1" id="form1" method="post" action="nsregac.php?ac=ns_reg"  onsubmit="return CheckForm()">
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
         <td align="left" class="centertl">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日：300元，仅限10日或11日</div></td>
       </tr>
       <tr>
         <td height="35" align="right"><table width="150" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name2','add')"></td>
             <td><input name="name2" type="text" id="name2" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name2','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">假日：500元，仅限12日或13日</div></td>
       </tr>
       <tr>
         <td height="35" align="right"><table width="150" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name3','add')"></td>
             <td><input name="name3" type="text" id="name3" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name3','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：1000元，10日-13日比赛</div></td>
       </tr>
       <tr>
         <td height="35" align="right"><table width="150" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="32" height="32" onClick="showpicket('name4','add')"></td>
             <td><input name="name4" type="text" id="name4" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
             <td><img src="images/jianhao.png" width="32" height="32"  onClick="showpicket('name4','sub')"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">18岁以下青少年需成年人陪同可免费观赛</div></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td align="center"><input type="image" name="imageField" id="imageField" src="images/button.jpg"> <input name="width" value="<?php echo $width;?>" type="hidden"></td>
       </tr>
       <tr>
         <td align="center"><img src="images/nsbottom.jpg" width="<?php echo $imgwidth;?>" ></td>
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