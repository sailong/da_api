<? 
 
$width = $_GET ['width'];
if(!$width)
{
	$width=460;
}
 
 //字体缩放
$ziti=14/460;
$fonts=$ziti*$width;
$ziti=16/460;
$fonts1=$ziti*$width;

$ziti=18/460;
$pt=$ziti*$width;

$input=450/460;
$inputc=$input*$width;
 
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
.pt{margin:0;padding:0;font-size:<?php echo $pt;?>px; color:#000000; font-weight:500;padding:20px;}
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
<td>
<img src="images/huabinbanner.jpg" width="<?php echo $width;?>" >
</td>
</tr>
  <tr>
    <td  style="padding: 15px;"><!-- add the info layer functionality here -->
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bg.png) no-repeat;">
        <tr>
          <td align="center"><p class="pt">您已经成功提交，稍后我们的客服<br />
            人员将通过客服专线13301159966<br />
            与您进行联系，请保持手机畅通，<br />
            或致电4008109966大正服务热线。</p>
            </td> 
        </tr> 
    </table></td>
  </tr>
  
 <tr>
    <td><img src="images/hbbottom.jpg" width="<?php echo $width;?>" ></td>
  </tr>
  
</table>
</body>