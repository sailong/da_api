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
<td align="center">&nbsp;</td>
</tr>
  <tr>
    <td  ><!-- add the info layer functionality here -->
<div style="padding: 15px; text-align:center">
     
      <h3 >1000元套票</h3>
</div>
    </td>
  </tr>
  
  
  <tr>
    <td  align="center"><img src="/upload/erweima/20130918/1718654403.png"></td>
  </tr>
  <tr>
    <td style="font-size:12px; text-align:center;">凭此二维码观赛 最终解释权归主办方所有</td>
  </tr>
  <tr>
    <td > </td>
  </tr>
  
</table>
</body>