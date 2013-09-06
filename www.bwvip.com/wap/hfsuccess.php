<? 
 
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
	$width=460;
}
  if(get_device_type()=='ios'){
	$width=320;
} 

 //字体缩放
$ziti=14/460;
$fonts=$ziti*$width;
$ziti=16/460;
$fonts1=$ziti*$width;

$input=450/460;
$inputc=$input*$width;
 
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
.inputc{width:<?php echo $inputc;?>px;}
</style> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<SCRIPT type=text/javascript src="images/jquery.js"></SCRIPT>

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
    <td  style="padding: 15px;"><!-- add the info layer functionality here -->

     
      
      <p >您已经成功提交，稍后我们的客服
人员将通过客服专线13301159966
与您进行联系，请保持手机畅通，
或致电4008109966大正服务热线。</p>
    </td>
  </tr>
  <tr>
    <td align="center" > </td>
  </tr>
  
 <tr>
    <td align="center"><img src="images/hfbottom.jpg" width="<?php echo $imgwidth;?>" ></td>
  </tr>
  
</table>
</body>