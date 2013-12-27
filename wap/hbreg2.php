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
body{margin:0;padding:0;width:100%;}
p,h3,input{margin:0;padding:0;}

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
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="MobileOptimized" content="236"/>
<meta http-equiv="Cache-Control" content="no-cache"/>

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
<img src="images/huabinbanner.jpg" width="<?php echo $imgwidth;?>" >
</td>
</tr>
  <tr>
    <td  ><!-- add the info layer functionality here -->
<div style="padding: 15px;">
     
      <h3 >观看2013华彬LPGA中国精英赛。</h3>
      <p >总奖金180万美元，冠军奖金27万美元，华彬LPGA中国精英赛10月3日至6日在北京华彬庄园尼克劳斯球场举行。</p>
      </div>
    </td>
  </tr>
  
  <tr>
    <td ><p  style="padding: 15px;">*必填部分</p></td>
  </tr>
   
  <tr>
     <td >
    
      <form name="form1" id="form1" method="post" action="hbregac.php?ac=hb_reg"  onsubmit="return CheckForm()">
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
           <textarea name="address" class="inputc" id="address"></textarea></td>
       </tr>
       <tr>
         <td class="tptitle">&nbsp;</td>
       </tr>
       <tr>
         <td  align="right">&nbsp;</td>
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
         <td  align="right">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">普通票：</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">学生票：100元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
               <td width="40%" class="tptitle1">（只可使用一次）</td>
               <td><table width="30%" border="0" cellspacing="0" cellpadding="0" ">
                 <tr>
                   <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name2','add')"></td>
                   <td><input name="name2" type="text" id="name2" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:32; text-align:center;"></td>
                   <td><img src="images/jianhao.png" width="32"    onClick="showpicket('name2','sub')"></td>
                 </tr>
               </table></td>
             </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：580元，10月3日或10月4日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" align="left" cellpadding="0" cellspacing="0" ">
               <tr>
                 <td><img src="images/jiahao.png" width="32" onClick="showpicket('name3','add')"></td>
                 <td><input name="name3" type="text" id="name3" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name3','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：880元，10月5日或10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32" onClick="showpicket('name4','add')"></td>
                 <td><input name="name4" type="text" id="name4" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name4','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：2580元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（可观看所有赛事）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name5','add')"></td>
                 <td><input name="name5" type="text" id="name5" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name5','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
      
       <tr>
         <td align="left" class="tptitle1">
           <div style="padding-left:15px;"><font color="#8b0000">特别说明：</font><br>
             ▪ 学生票只可使用一次。只限全日制学校<br>
             在校生购买，入场时须出示学生证！<br>
             ▪ 16岁以下青少年在有成人陪同的情况<br>
               下可免票观摩赛事。<br>
             ▪ 拥有伤残证明及老年证的观众，可持<br>
               相应证件换票入场。<br>
             ▪ 各类门票一旦出售，概不退换，敬请谅解<br>
             ▪ 如因天气情况单日赛事取消，则当日票<br>
               可顺延至次日使用 
         </div> </td>
       </tr>
       <tr>
         <td align="left" class="centertl">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">白银贵宾票：单人票</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：880元，10月3日或10月4日</div></td>
       </tr>
       <tr>
         <td align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name6','add')"></td>
                 <td><input name="name6" type="text" id="name6" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name6','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：1080元，10月5日或10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name7','add')"></td>
                 <td><input name="name7" type="text" id="name7" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name7','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：3180元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1">（可观看所有赛事）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32" onClick="showpicket('name8','add')"></td>
                 <td><input name="name8" type="text" id="name8" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name8','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">白银贵宾票：家庭票</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：2066元，10月3日或10月4日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name9','add')"></td>
                 <td><input name="name9" type="text" id="name9" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name9','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：2592元，10月5日或10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1" align="center">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name10','add')"></td>
                 <td><input name="name10" type="text" id="name10" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name10','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：8508元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1">（可观看所有赛事）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name11','add')"></td>
                 <td><input name="name11" type="text" id="name11" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name11','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;"><font color="#8b0000">特别说明：</font><br>
           ▪ 购买白银贵宾票的观众可享受以下礼<br>
遇（仅限110个席位）。 <br>
▪ 专享停车位。<br>
▪ 每天价值180元餐券，持券观众可在<br>
啤酒吧、公众餐饮区购买食品及酒水<br>
饮料。<br>
▪ 赠送球帽。 </div></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">黄金贵宾票：单人票</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：1080元，10月3日或10月4日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name12','add')"></td>
                 <td><input name="name12" type="text" id="name12" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name12','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：1680元，10月5日或10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name13','add')"></td>
                 <td><input name="name13" type="text" id="name13" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name13','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：4580元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（可观看所有赛事）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name14','add')"></td>
                 <td><input name="name14" type="text" id="name14" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name14','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">黄金贵宾票：家庭票</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">平日票：2588元，10月3日或10月4日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name15','add')"></td>
                 <td><input name="name15" type="text" id="name15" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name15','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">周末票：3966元，10月5日或10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（只可使用一次）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name16','add')"></td>
                 <td><input name="name16" type="text" id="name16" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"  onClick="showpicket('name16','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">套票：10688元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%" class="tptitle1">（可观看所有赛事）</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name17','add')"></td>
                 <td><input name="name17" type="text" id="name17" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32"   onClick="showpicket('name17','sub')"></td>
               </tr>
             </table></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;"><font color="#8b0000"><font color="#8b0000">特别说明：</font></font><br>
           ▪ 黄金贵宾票（Gold Package）。 <br>
           ▪ 购买黄金贵宾票的观众可享受以下礼<br>
遇（每天仅限140个席位）。 <br>
▪ 专享停车位。<br>
▪ 每天价值250元餐券，持券观众可在啤<br>
酒吧、公众餐饮区购买食品及酒水饮料。<br>
▪ 可进入贵宾看台和啤酒吧。<br>
▪ 赛事官方纪念品。<br>
▪ 幸运抽奖活动。 </div></td>
       </tr>
       <tr>
         <td align="left" class="centertl"><div style="padding-left:15px;">铂金贵宾票：</div></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;">100000元，10月3日至10月6日</div></td>
       </tr>
       <tr>
         <td  align="right"><table width="80%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="30%">&nbsp;</td>
             <td><table width="30%" border="0" cellspacing="0" cellpadding="0" >
               <tr>
                 <td><img src="images/jiahao.png" width="32"  onClick="showpicket('name18','add')"></td>
                 <td><input name="name18" type="text" id="name18" value="0" size="3" maxlength="3" onKeyUp="value=value.replace(/[^\d]/g,'') " 
      onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  style="width:30px; text-align:center;"></td>
                 <td><img src="images/jianhao.png" width="32" onClick="showpicket('name18','sub')"></td>
                 </tr>
               </table></td>
             </tr>
           </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1"><div style="padding-left:15px;"><font color="#8b0000">特别说明：</font><br>
           ▪ 铂金贵宾票Platinum Package<br>
           限量10桌 ，贵宾包厢专享招待套餐<br>
           (1桌/10人，周四至周日):100,000 RMB <br>
           ▪ 购买铂金贵宾票的观众可享受以下<br>
           礼遇： 专享VIP停车位，位于赞助商停<br>
           车位或华彬俱乐部停车位。<br>
           ▪ 席位设于精英会Classic Club。 <br>
           ▪ 赛事官方纪念品。 <br>
           ▪ 幸运抽奖活动。 <br>
           ▪ 华彬LPGA精英赛官方活动入场函及<br>
庄园活动体验。 </div></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td align="center"><input type="image" name="imageField" id="imageField" src="images/button.jpg"> <input name="width" value="<?php echo $width;?>" type="hidden"></td>
       </tr>
       <tr>
         <td align="center"><img src="images/hbbottom.jpg" width="<?php echo $imgwidth;?>" ></td>
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