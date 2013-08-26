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
 
 //横版缩放
$dguoqi=960/1280;
$dguoqi1=$dguoqi*$width;
 
?>

<html xmlns="http://www.w3.org/1999/xhtml"><style type="text/css">
.centertl {
	font-size: 14px;
	font-weight: 600;
}
.tptitle {
	font-size: 14px;
	font-weight: 600;
	color: #999;
}
</style> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<SCRIPT type=text/javascript src="images/jquery.js"></SCRIPT>
 
 
  <link rel="stylesheet" type="text/css" href="images/golf_ticket.css">  
<title></title> 
  
</head> 
<body  onclick=checkSelectBoxStatus();>

<table width="<?php echo $width;?>" border="0" align="left" cellpadding="0" cellspacing="0"><tr><td><img src="images/banner.jpg" width="<?php echo $width;?>" ></td>
</tr>
  <tr>
    <td  style="padding: 15px;"><!-- add the info layer functionality here -->

      <h3 >观看2013 BMW大师赛（中国）。</h3>
      <p >2013年10月24日至27日，BMW大师赛将于上海举办。<br>
        即刻注册抢票，就有机会亲临现场，见证顶级赛事，观赏世界级球手的精彩表现。</p>
      <p >抢票以先到先得形式进行，每人每场比赛日可抢得2张亲临观赏票，观赛日期可多选。</p>
      <p >注册抢票即有机会获得惊喜抢票大奖。</p>
    </td>
  </tr>
  
  <tr>
    <td  style="padding: 15px;">*必填部分 </td>
  </tr>
   
  <tr>
     <td  style="padding: 15px;">
     <form name="form1" method="post" action=""><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center" class="centertl">姓名</td>
       </tr>
       <tr>
         <td class="tptitle">称谓*</td>
       </tr>
       <tr>
         <td>
           <label for="year"></label>
           <select name="qiancheng" id="qiancheng">
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
         <td><label for="name"></label>
          <input name="family_name" id="family_name" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">名*</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="name" id="name" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">出生日期*</td>
       </tr>
       <tr>
         <td><label for="qiancheng4"></label>
           <select name="year" id="qiancheng4">
             <option value="0">请选择</option>
             <option value="先生">先生</option>
             <option value="女士">女士</option>
           </select>
           年
           <select name="month" id="year">
             <option value="0">请选择</option>
             <option value="先生">先生</option>
             <option value="女士">女士</option>
           </select>
月
<select name="day" id="year2">
  <option value="0">请选择</option>
  <option value="先生">先生</option>
  <option value="女士">女士</option>
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
         <td><label for="name"></label>
           <input name="phone" id="phone" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">电子邮箱*</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="email" id="name" type="text"></td>
       </tr>
       <tr>
         <td align="center" class="centertl">地址</td>
       </tr>
       <tr>
         <td class="tptitle">省份*</td>
       </tr>
       <tr>
         <td><label for="year"></label>
           <select name="province" id="qiancheng3">
             <option value="0">请选择</option>
             <option value="先生">先生</option>
             <option value="女士">女士</option>
           </select></td>
       </tr>
       <tr>
         <td class="tptitle">城市/地区*</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <select name="city" id="qiancheng2">
             <option value="0">请选择</option>
             <option value="先生">先生</option>
             <option value="女士">女士</option>
           </select></td>
       </tr>
       <tr>
         <td class="tptitle">地址*</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="address" id="name" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">邮政编码*（海外用户请亲临现场取票）</td>
       </tr>
       <tr>
         <td><label for="name"></label>
           <input name="postcode" id="name" type="text"></td>
       </tr>
       <tr>
         <td align="center" class="centertl">观看比赛</td>
       </tr>
       <tr>
         <td class="tptitle">请选择观看比赛的日期(可多选)* 
           <br>
           重复提交，以最后一次提交内容为准 
*</td>
       </tr>
       <tr>
         <td><label for="watch_date"> 
           <input type="checkbox" name="watch_date[]" id="watch_date"> </label>         
           10月24日
           <label for="watch_date2"> 
           <input type="checkbox" name="watch_date[]" id="watch_date2"> </label>        
           10月25日
           <br> 
           <label for="watch_date3"> 
            <input type="checkbox" name="watch_date[]" id="watch_date3">  </label>       
           10月26日
           <label for="watch_date4"> 
            <input type="checkbox" name="watch_date[]" id="watch_date4"> </label>    
           10月27日
        </label></td>
       </tr>
       <tr>
         <td align="center" class="centertl">您的购车计划 </td>
       </tr>
       <tr>
         <td class="tptitle">是否是BMW车主？*</td>
       </tr>
       <tr>
         <td><label for="learn_channels"></label>
           <select name="is_owners" id="is_owners">
             <option value="">请选择</option>
             <option value="1">是</option>
             <option value="0">否</option>
           </select></td>
       </tr>
       <tr>
         <td class="tptitle">您感兴趣的BMW车系*</td>
       </tr>
       <tr>
         <td><label for="bwm_cars"></label>
           <select name="bwm_cars" id="bwm_cars">
             <option value="0">请选择</option>
             <option value="BMW 1系">BMW 1系</option>
             <option value="BMW 3系">BMW 3系</option>
             <option value="BMW 5系">BMW 5系</option>
             <option value="BMW 6系">BMW 6系</option>
             <option value="BMW 7系">BMW 7系</option>
             <option value="BMW X1系">BMW X1系</option>
             <option value="BMW X3系">BMW X3系</option>
             <option value="BMW X5系">BMW X5系</option>
             <option value="BMW X6系">BMW X6系</option>
             <option value="BMW Z4系">BMW Z4系</option>
             <option value="BMW M">BMW M</option>
             <option value="暂无计划">暂无计划</option>
           </select></td>
       </tr>
      <tr>
         <td class="tptitle">您打算何时购买新车*</td>
       </tr>
       <tr>
         <td><label for="buy_car_date"></label>
           <select name="buy_car_date" id="buy_car_date">
             <option value="0">请选择</option>
             <option value="3个月以内">3个月以内</option>
             <option value="3-6个月">3-6个月</option>
             <option value="6-12个月">6-12个月</option>
             <option value="1年以上">1年以上</option>
             <option value="暂无打算">暂无打算</option>
           </select></td>
       </tr>
       <tr>
         <td align="center" class="centertl">了解渠道 </td>
       </tr>
       <tr>
         <td class="tptitle">您从何处得到我们的信息?*</td>
       </tr>
       <tr>
         <td><label for="learn_channels"></label>
           <select name="learn_channels" id="learn_channels">
             <option value="">请选择</option>
             <option value="1">是</option>
             <option value="0">否</option>
           </select></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td><label for="is_contact"><input name="is_contact" type="checkbox" id="is_contact" checked>
           需要当地经销商与我取得联系。</label></td>
       </tr>
       <tr>
         <td><label for="is_readed">
           <input name="is_readed" type="checkbox" id="is_readed" checked>
           我已阅读并接受数据使用声明. </label></td>
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
    <td><img src="images/bottom.jpg" width="<?php echo $width;?>" ></td>
  </tr>
  
</table>
</body>