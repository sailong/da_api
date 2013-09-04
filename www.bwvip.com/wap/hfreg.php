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
<img src="images/huifengbanner.jpg" width="<?php echo $width;?>" >
</td>
</tr>
  <tr>
    <td  style="padding: 15px;"><!-- add the info layer functionality here -->

     
      <h3 >观看2013汇丰冠军赛。</h3>
      <p >享有"亚洲大满贯赛"美誉的世界高尔夫锦标赛-汇丰冠军赛在观澜湖高尔夫球会奥拉沙宝成功举办一届之后，今年将重返上海并永久落户。第九届赛事将于2013年10月31日至11月3日日在上海佘山国际高尔夫俱乐部举行，该场地曾于2005年至2011年连续承办了七届汇丰冠军赛。</p>
    </td>
  </tr>
  
  <tr>
    <td  style="padding: 15px;"><p >*必填部分</p></td>
  </tr>
   
  <tr>
     <td  style="padding: 15px;">
     <form name="form1" id="form1" method="post"   onsubmit="return CheckForm()"><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center" class="centertl">姓名</td>
       </tr>
       <tr>
         <td class="tptitle">称谓*</td>
       </tr>
       <tr>
         <td>
           <label for="qiancheng"></label>
           <select name="qiancheng" class="inputc" id="qiancheng">
             <option value="0">请选择</option>
             <option value="先生">先生</option>
             <option value="女士">女士</option>
           </select>
      </td>
       </tr>
       <tr>
         <td class="tptitle">姓*</td>
       </tr>
       <tr>
         <td><label for="family_name"></label>
          <input name="family_name" id="family_name" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle">名*</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="name" id="name" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle">出生日期*</td>
       </tr>
       <tr>
         <td><label for="year"></label>
           <select name="year" id="year">
             <option value="0">请选择</option>
             <?php for ($i=0; $i<=112; $i++) { ?>
             <option value="<?php echo 1900+$i;?>"><?php echo 1900+$i;?></option>
             
<?php }?>
           </select>
           年
           <select name="month" id="month">
             <option value="0">请选择</option>  <?php for ($i=0; $i<=11; $i++) { ?>
             <option value="<?php echo 1+$i;?>"><?php echo 1+$i;?></option>
             
<?php }?>
           </select>
月
<select name="day" id="day">
  <option value="0">请选择</option><?php for ($i=0; $i<=30; $i++) { ?>
             <option value="<?php echo 1+$i;?>"><?php echo 1+$i;?></option>
             
<?php }?>
</select> 
日
</td>
       </tr>
       <tr>
         <td align="center" class="centertl">联系信息</td>
       </tr>
       <tr>
         <td class="tptitle">手机号码*</td>
       </tr>
       <tr>
         <td><label for="phone"></label>
           <input name="phone" id="phone" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle">电子邮箱*</td>
       </tr>
       <tr>
         <td><label for="email"></label>
           <input name="email" id="email" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td align="center" class="centertl">地址</td>
       </tr>
       <tr>
         <td class="tptitle">省份*</td>
       </tr>
       <tr>
         <td><label for="province"></label>
           <select name="province" id="province" onchange='getarea(this.value)'  class="inputc" >

             <option value="0">请选择</option>
             <?php $query = DB::query('select id, name from '.DB::table('common_district')." where level=1");
	while($value = DB::fetch($query)) {?>
             <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option> 
              
<?php }?>
           </select>
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
 
 
if  (document.getElementById("qiancheng").value ==  0)  {  
	alert("请选择您的称谓!");
	document.getElementById("qiancheng").focus();
	return  false;
	}  
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
if  (document.getElementById("year").value  ==  0)  {  
	alert("请输入您的出生年!");
	document.getElementById("year").focus();
	return  false;
	}
if  (document.getElementById("month").value  ==  0)  {  
	alert("请输入您的出生月!");
	document.getElementById("month").focus();
	return  false;
	}
if  (document.getElementById("day").value  ==  0)  {  
	alert("请输入您的出生日!");
	document.getElementById("day").focus();
	return  false;
	}
if  (document.getElementById("phone").value.length  !=  11)  {  
	alert("请输入您的手机号!");
	document.getElementById("phone").focus();
	return  false;
	}
	var phone=document.getElementById("phone").value; 
    if(!(/^1[3|5][0-9]\d{4,8}$/.test(phone))){ 
        alert("不是完整的11位手机号或者正确的手机号前七位"); 
        document.getElementById("phone").focus(); 
        return false; 
    } 

if (document.getElementById("email").value.length  ==  0)  {  
	alert("请输入您的email!");
	document.getElementById("email").focus();
	return  false;
	}
  var email=document.getElementById("email").value;
   
    if(!(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(email))){ 
        alert("email格式不对"); 
        document.getElementById("email").focus(); 
        return false; 
    } 

if  (document.getElementById("province").value  ==  0)  {  
	alert("请选择省份!");
	document.getElementById("province").focus();
	return  false;
	}   
  
if  (document.getElementById("city").value  ==  0)  {  
	alert("请选择城市/地区!");
	document.getElementById("city").focus();
	return  false;
	}   
if (document.getElementById("address").value.length  ==  0)  {  
	alert("请输入您的地址!");
	document.getElementById("address").focus();
	return  false;
	}
  
if (document.getElementById("postcode").value.length  ==  0)  {  
	alert("请输入您的邮政编码!");
	document.getElementById("postcode").focus();
	return  false;
	}  
 
return  true;
}

 

-->
 </script>
           </td>
       </tr>
       <tr>
         <td class="tptitle">城市/地区*</td>
       </tr>
       <tr>
         <td><label for="city"></label>
           <select name="city" id="city"  class="inputc" >
             <option value="0">请选择</option> 
           </select></td>
       </tr>
       <tr>
         <td class="tptitle">地址*</td>
       </tr>
       <tr>
         <td><label for="address"></label>
           <input name="address" id="address" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td class="tptitle">邮政编码*（海外用户请亲临现场取票）</td>
       </tr>
       <tr>
         <td><label for="postcode"></label>
           <input name="postcode" id="postcode" type="text" class="inputc" ></td>
       </tr>
       <tr>
         <td align="center" class="centertl">观看比赛</td>
       </tr>
       <tr>
         <td class="tptitle">请选择观看比赛的日期(可多选)* <br>
           重复提交，以最后一次提交内容为准 
           *</td>
       </tr>
       <tr>
         <td height="25" align="right">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="centertl">普通票：</td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">职业-业余配对赛：200元，10月30日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="10%" border="0" cellspacing="0" cellpadding="0" style="padding-right:80px;">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">平日票：500元，10月31日或11月1日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="10%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name3" type="text" id="name3" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">周末票：1000元，11月2日或11月3日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">套票：2000元，10月30-11月3日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl">家庭票：</td>
       </tr>
       <tr>
         <td align="left" class="tptitle"><span class="tptitle1">职业-业余配对赛：300元，10月30日</span></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle"><span class="tptitle1">平日票：900元，10月31日或11月1日 </span></td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">周末票：1800元，11月2日或11月3日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">套票：3500元，10月30日-11月3日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td align="left" class="centertl">特殊票：</td>
       </tr>
       <tr>
         <td align="left" class="tptitle1">学生票：100元，10月30日或10月31日
           或11月1日</td>
       </tr>
       <tr>
         <td height="25" align="right"><table width="100" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td><img src="images/jiahao.png" width="15" height="15"></td>
             <td><input name="name2" type="text" id="name2" size="5" ></td>
             <td><img src="images/jianhao.png" width="15" height="15"></td>
           </tr>
         </table></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td align="center"><input type="image" name="imageField" id="imageField" src="images/button.jpg"></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
      
     </table>
        </form>
     </td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>