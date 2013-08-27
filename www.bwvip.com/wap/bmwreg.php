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
p {
	font-size: <?php echo $fonts;?>px; 
	color: #000;
}
h3 {
	font-size: <?php echo $fonts;?>px; 
	font-weight: 600;
	color: #000;
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
    <td  style="padding: 15px;"><p >*必填部分</p></td>
  </tr>
   
  <tr>
     <td  style="padding: 15px;">
     <form name="form1" method="post" action="bmwregac.php?ac=bwm_reg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center" class="centertl">姓名</td>
       </tr>
       <tr>
         <td class="tptitle">称谓*</td>
       </tr>
       <tr>
         <td>
           <label for="qiancheng"></label>
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
         <td><label for="family_name"></label>
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
           <input name="phone" id="phone" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">电子邮箱*</td>
       </tr>
       <tr>
         <td><label for="email"></label>
           <input name="email" id="name" type="text"></td>
       </tr>
       <tr>
         <td align="center" class="centertl">地址</td>
       </tr>
       <tr>
         <td class="tptitle">省份*</td>
       </tr>
       <tr>
         <td><label for="province"></label>
           <select name="province" id="province" onchange='getarea(this.value)'>

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
 
</script>

           </td>
       </tr>
       <tr>
         <td class="tptitle">城市/地区*</td>
       </tr>
       <tr>
         <td><label for="city"></label>
           <select name="city" id="city">
             <option value="0">请选择</option> 
           </select></td>
       </tr>
       <tr>
         <td class="tptitle">地址*</td>
       </tr>
       <tr>
         <td><label for="address"></label>
           <input name="address" id="name" type="text"></td>
       </tr>
       <tr>
         <td class="tptitle">邮政编码*（海外用户请亲临现场取票）</td>
       </tr>
       <tr>
         <td><label for="postcode"></label>
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
           <input type="checkbox" name="watch_date[]" id="watch_date" value="10月24日"> </label>         
           10月24日
           <label for="watch_date2"> 
           <input type="checkbox" name="watch_date[]" id="watch_date2"  value="10月25日"> </label>        
           10月25日
           <br> 
           <label for="watch_date3"> 
            <input type="checkbox" name="watch_date[]" id="watch_date3" value="10月26日">  </label>       
           10月26日
           <label for="watch_date4"> 
            <input type="checkbox" name="watch_date[]" id="watch_date4" value="10月27日"> </label>    
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
         <td><label for="is_owners"></label>
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
             <option value="高尔夫球场">高尔夫球场</option>
             <option value="高尔夫练习场">高尔夫练习场</option>
             <option value="高尔夫专卖店/高尔夫订场中介">高尔夫专卖店/高尔夫订场中介</option>
             <option value="报纸/杂志媒体">报纸/杂志媒体</option>
             <option value="户外媒体">户外媒体</option>
             <option value="线上媒体">线上媒体</option>
             <option value="其他">其他</option>	
           </select></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td class="tptitle"><label for="is_contact"><input name="is_contact" type="checkbox" id="is_contact" value="1" checked>
           需要当地经销商与我取得联系。</label></td>
       </tr>
       <tr>
         <td class="tptitle"><label for="is_readed">
           <input name="is_readed" type="checkbox" id="is_readed" checked value="1">
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