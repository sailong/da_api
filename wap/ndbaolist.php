<?php

/**
 * [Discuz!] (C)2001-2099 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 *
 * $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */ 
define ( 'APPTYPEID', 1 ); 

 

require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大正网-提供专业高尔夫资讯，教学，旅游，视频，赛事报道，球具订购，球场预定服务，最纯净高尔夫实名认证互动社区</title>
<meta name="keywords" content="大正网,大正高尔夫,高尔夫预订,高尔夫球场预订,高尔夫旅游,高尔夫球具,高尔夫俱乐部,高尔夫社区,城市挑战赛"/>
<meta name="Description" content="专业高尔夫爱好者无广告纯净的高尔夫主题互动社区，实名认证，远离广告骚扰，互动社区，提供最优惠的球场预定、球具订购和旅游定制服务,承办高尔夫比赛（皇冠杯城市挑战赛、英菲尼迪车主赛、电信天翼高尔夫活动等），球场资讯、旅游线路。大正高尔夫让您更好的享受高尔夫,享受生活。"/>
<link rel="stylesheet" type="text/css" href="/static/space/index/css/main.css">
<link rel="stylesheet" type="text/css" href="/static/space/index/css/lrtk.css" >
<script type="text/javascript" src="/static/space/index/js/index_solid.js"></script>
<script src="/static/js/logging.js?1bf" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--

var $ = function(o) {return document.getElementById(o);}
function showDiv(parm)
{
    $('index_wb_mxs').style.display='none';
    $('index_wb_axs').style.display='none';
	$('index_wb_lpga').style.display='none';
	$('index_wb_cstzs').style.display='none';
    $('index_wb_httj').style.display='none';
    $(parm).style.display = '';
    for(var i in $('ulMenu').getElementsByTagName('LI')){
        $('ulMenu').getElementsByTagName('LI')[i].className = 'codeDemomouseOutMenu';
    }
}
//-->
</SCRIPT>
<style>
.STYLE1 {color: #FF0000}
.data_list td{height:25px;border-left: 1px solid #ffffff; border-bottom: 1px solid #ffffff; }
.alt {  border-top: 0; background:#d5e6f6; color: #797268; width:35px;height:25px;  font-weight:800; font-size:14px;} 
.spec {  border-top: 0; background: #bdd3e8; font-weight:800;  font-size:14px;} 
</style>
<meta http-equiv="refresh" content="180">
</head>

<body>
<div id="box">
<!--头部文件开始-->
     
      <div class="box_1">
          <!--20120312修改部分开始-->
                <div class="head_bg">
                                      <div class="head_logo"><img src="/images/logo-1.jpg" width="308" height="112"></div>
                                      <div style="float:left;">
                                                   <div class="head_tel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400-810-9966  <a href="http://wap.bwvip.com" target="_blank" class="wap">WAP:wap.bwvip.com</a>
                                                   </div>
                                                   <div class="head_denglu">
                                                                <div class="fleft"><img src="/images/dl_left.gif"></div>
                                                                <div class="head_denglu_bg">
                                                                                        <?php if($_G['uid']) {?>
                                                                                        <div class="head_denglu_hou">
                                          <form id="scbar_form" method="post" autocomplete="off" onsubmit="searchFocus($('scbar_txt'))" action="/search.php?searchsubmit=yes" target="_blank">
                                          <div style="float:left;">
                                          欢迎 &nbsp;&nbsp;
                                          <a href="/home.php?mod=space&uid=<?php  echo $_G['uid'];?>">
                                                     <img src="/uc_server/avatar.php?uid=<?php  echo $_G['uid'];?>&size=small" align="absmiddle" class="head_denglu_pic"/></a>
                                          &nbsp;&nbsp;<a href="/home.php?mod=space&uid=<?php  echo $_G['uid'];?>" ><?php  echo $getstat['usrnickname'];?>	</a>
                                          &nbsp;&nbsp;
                                          <a href="/member.php?mod=logging&action=logout&formhash=<?php  echo $_G['formhash'];?>">
                                                        <img src="/images/dl_bt_3.gif" border="0" align="absmiddle">
                                          </a>
                                   </div>
                                   <!--search start -->
                                       <div style="float:right;">
                                        <input type="hidden" name="mod" id="scbar_mod" value="weibo" />
                                        <input type="hidden" name="formhash" value="6107e2c3" />
                                        <input type="hidden" name="srchtype" value="title" />
                                        <input type="hidden" name="srhfid" value="0" id="dzsearchforumid" />
                                        <input type="hidden" name="srhlocality" value="home::space" />
                                        <input type="text" name="srchtxt" id="scbar_txt" style="height:14px; width:100px; border:1px slid #CCC;" autocomplete="off"/><input type="image" src="/images/ss_bt_1.jpg"  style="width:61px; height:18px; border:none; margin-left:10px; margin-right:10px;">
                                        </div> 
                                      </form>                              
                                          <!--search end -->
                                   </div>
                                     <?php } else{?>
                                                                          <form onsubmit="return lsSubmit()" action="/member.php?mod=logging&amp;action=login&amp;loginsubmit=yes&amp;infloat=yes&amp;lssubmit=yes" id="lsform" autocomplete="off" method="post">
                                                                                    <div class="fleft"><span>Email</span></div>
                                                                                    <div class="fleft">
                                                                                        <input type="text" name="username" />
                                                                                    </div>
                                                                                    <div class="fleft"><span>密码</span></div>
                                                                                    <div class="fleft">
                                                                                        <input  type="password" name="password" />
                                                                                    </div>
                                                                                    <div class="fleft">
                                                                                         <input name="" type="image" src="/images/dl_bt_1.gif"  style="width:61px; height:18px; border:none; margin-left:10px; margin-right:10px;"/>
                                                                                    </div>
                                                                                    <div class="fleft" style="margin-top:20px;">
                                                                                            <a href="/member.php?mod=register" target="_blank" ><img src="/images/dl_bt_2.gif" border="0"></a>
                                                                                    </div>
                                                                        <input type="hidden" value="54ec7ac9" name="formhash" />
                                                                        <input type="hidden" value="http://211.94.187.157" name="referer">
                                                                           </form>
           <?php }?>
                                  
                                                       </div>
                                                                <div class="fleft"><img src="/images/dl_right.gif"></div>
                                                   </div>
                                      </div>
                                      <div class="clear"></div>
                </div>
           <!--20120312修改部分结束-->
      </div>
        <div  class="box_1">
             <div class="dh_1"><img src="/static/space/index/images/dh_bg_1.gif" width="17" height="24"></div>
             <div class="dh_2">
                     <a href="/index.php" class="dh_3">首页</a>
                     <span class="diy_miao">|</span>
                     <a href="/star.php " class="dh_3" >球星</a><span class="diy_miao">|</span>
                     <a href="/event.php" class="dh_3" >赛事</a><span class="diy_miao">|</span>
                     <a href="/tour.php"  class="dh_3">旅游</a><span class="diy_miao">|</span>
                     <a href="/field.php" class="dh_3">球场</a><span class="diy_miao">|</span>
                     <!--<a href="/teach.php"  class="dh_3">教学</a><span class="diy_miao">|</span>-->
                     <a href="/infomation.php"  class="dh_3">行业</a><span class="diy_miao">|</span>
                     <a href="/mobilelist.php" class="dh_3">手机报</a><span class="diy_miao">|</span>
                     <a href="/club.php" class="dh_3">品牌俱乐部</a><span class="diy_miao">|</span> 
                     <a href="http://www.gcctour.cn/" target="_blank" class="dh_gcct" >城市挑战赛</a>
  </div>
             <div class="dh_1"><img src="/static/space/index/images/dh_bg_3.gif" width="16" height="24"></div>
             <div class="clear"></div>
       </div>
      
       
         
<!--头部文件结束-->
 
<table cellpadding="0" cellspacing="0" style="background-attachment:#e7e7e7;" width="100%" class="data_list">
  <tr>
    <td colspan="25" bgcolor="#D5E6F8" > <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center"><img src="/images/dz-jsbf.jpg" width="964" height="115"></div></td>
  </tr>
</table>
</td>
  </tr>  
 <tr>
    <td width="5%" rowspan="2"  style="background:#67859f;"><font color="#FFFFFF">排名</font></td>
    <td width="5%" rowspan="2" style="background:#67859f;"><font color="#FFFFFF">姓名</font></td>
    <td width="5%" rowspan="2" style="background:#67859f;"><font color="#FFFFFF">分组</font></td>
    <td width="5%" rowspan="2" style="background:#67859f;"><font color="#FFFFFF">成绩</font></td>
    <td  class="alt">1</td>
    <td  class="alt">2</td>
    <td  class="alt">3</td>
    <td  class="alt">4</td>
    <td  class="alt">5</td>
    <td  class="alt">6</td>
    <td  class="alt">7</td>
    <td  class="alt">8</td>
    <td  class="alt">9</td>
    <td  class="alt">OUT</td>
    <td  class="alt">10</td>
    <td  class="alt">11</td>
    <td  class="alt">12</td>
    <td  class="alt">13</td>
    <td  class="alt">14</td>
    <td  class="alt">15</td>
    <td  class="alt">16</td>
    <td  class="alt">17</td>
    <td  class="alt">18</td>
    <td  class="alt">IN</td>
    <td  class="alt">总计</td>
  </tr>
   <tr>
    <td class="spec">5</td>
    <td class="spec">4</td>
    <td class="spec">3</td>
    <td class="spec">4</td>
    <td class="spec">4</td>
    <td class="spec">4</td>
    <td class="spec">5</td>
    <td class="spec">3</td>
    <td class="spec">4</td>
    <td class="spec">36</td>
   <td class="spec">4</td>
    <td class="spec">4</td>
    <td class="spec">3</td>
    <td class="spec">5</td>
    <td class="spec">4</td>
    <td class="spec">4</td>
    <td class="spec">3</td>
    <td class="spec">5</td>
    <td class="spec">4</td>
    <td class="spec">36</td>
    <td class="spec">72</td>
  </tr>                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">1</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889982&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">黄浩</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">4</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">-1</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">35</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">36</td>
    <td class="specalt" style="text-align:center;">71</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">2</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899081&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">付洪涛</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">1</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">E</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt" style="text-align:center;">35</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt" style="text-align:center;">72</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">3</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890065&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">李邵隆</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">11</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+1</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>

    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">35</td>
    <td class="specalt" style="text-align:center;">73</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">4</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889604&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">胡楠</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">7</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+1</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt" style="text-align:center;">33</td>
    <td class="specalt" style="text-align:center;">73</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">5</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890197&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">郑翔</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">18</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+2</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">36</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">74</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">6</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890275&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">纪幸</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">20</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+2</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt" style="text-align:center;">74</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">7</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890394&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">许雄</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">17</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+2</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt" style="text-align:center;">74</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">8</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889725&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">辛月旱</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">2</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+4</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt" style="text-align:center;">76</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">9</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1888902&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">卢纯</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">9</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+4</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">76</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">10</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890305&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张申波</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">5</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+4</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt" style="text-align:center;">76</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">11</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899175&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘楚耀</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">3</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+4</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">76</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">12</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889723&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">钟鹏驰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">8</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+5</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt" style="text-align:center;">77</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">13</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889578&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">姚宇翔</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">1</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+5</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">77</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">14</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1888901&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张飞</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">23</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+6</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">78</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">15</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889964&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">师志斌</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">4</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+7</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt" style="text-align:center;">79</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">16</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890038&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">唐锐</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">16</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+7</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt" style="text-align:center;">79</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">17</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890250&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">戴剑</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">18</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+7</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt" style="text-align:center;">79</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">18</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890395&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘军</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">22</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+8</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt" style="text-align:center;">80</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">19</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890242&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">李小卫</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">6</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+8</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt" style="text-align:center;">80</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">20</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890060&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">吴晓伟</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">19</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+8</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">37</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">80</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">21</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889915&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张亚明</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">25</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+9</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt" style="text-align:center;">81</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">22</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890073&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">朱小静</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">26</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+9</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt" style="text-align:center;">81</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">23</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889845&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">杨华峰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">3</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+9</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">81</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">24</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890072&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">肖社初</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">6</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+9</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">81</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">25</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889589&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘星</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">7</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+10</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">82</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">26</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890249&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">彭小虎</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">17</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+10</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt" style="text-align:center;">82</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">27</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889868&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">胡锦峰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">3</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+10</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt" style="text-align:center;">82</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">28</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889581&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">唐海兵</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">16</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+10</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">82</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">29</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889817&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">邓迪祥</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">5</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+10</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">82</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">30</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890136&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">任孚涛</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">17</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+11</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">40</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">83</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">31</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899101&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">傅强</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">18</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+11</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt" style="text-align:center;">83</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">32</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889668&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">黄忠运</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">16</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+11</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt" style="text-align:center;">83</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">33</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889904&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">邓滔</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">21</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+12</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">84</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">34</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890068&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">章驰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">23</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+12</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt" style="text-align:center;">84</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">35</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890083&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">吴玉龙</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">6</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+12</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt" style="text-align:center;">84</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">36</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889531&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">彭海亮</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">5</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+12</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt" style="text-align:center;">84</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">37</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899127&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">陈林峰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">27</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+12</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt" style="text-align:center;">84</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">38</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890295&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">曹东</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">18</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+14</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">86</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">39</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890070&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">廖小凡</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">1</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+14</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">39</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">86</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">40</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889742&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">龙舟</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">19</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+14</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">86</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">41</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889850&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">卢洪文</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">21</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+14</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">86</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">42</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890263&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张涵</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">26</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+14</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt" style="text-align:center;">86</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">43</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889886&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">周明德</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">11</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+15</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt" style="text-align:center;">87</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">44</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889528&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">熊万林</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">7</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+16</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">38</td>
    <td class="specalt" style="text-align:center;">88</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">45</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889909&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘赵熊</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">4</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+17</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt" style="text-align:center;">89</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">46</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889597&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">谭鑫</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">14</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+17</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">89</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">47</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889522&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">王晓京</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">29</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+17</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">89</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">48</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890246&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘国辉</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">26</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+17</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">89</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">49</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890298&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">马海军</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">20</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+18</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">90</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">50</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889820&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">陆忠</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">9</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+18</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt" style="text-align:center;">90</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">51</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889582&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">曹洁</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">14</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+18</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">43</td>
    <td class="specalt" style="text-align:center;">90</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">52</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889592&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">谭君</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">14</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+19</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">91</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">53</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889966&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">陈杰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">28</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+19</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">91</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">54</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889626&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">袁易军</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">9</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+20</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt" style="text-align:center;">92</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">55</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889565&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">郝帅</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">19</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+20</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt" style="text-align:center;">92</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">56</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890254&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">任飞</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">20</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+20</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">92</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">57</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890108&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">陈迎新</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">10</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+20</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">92</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">58</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890203&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">俞卡明</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">10</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+21</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt" style="text-align:center;">93</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">59</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889894&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘展</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">4</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+21</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">93</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">60</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889558&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">管凯</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">2</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+22</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">94</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">61</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889971&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">李祥</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">8</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+22</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt" style="text-align:center;">94</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">62</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889888&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张嘉礼</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">25</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+23</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt" style="text-align:center;">95</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">63</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899125&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">邱剑</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">24</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+23</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">42</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">95</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">64</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889541&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">侯迪睿</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">29</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+24</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">96</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">65</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889515&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">蒋金旭</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">17</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+24</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt" style="text-align:center;">96</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">66</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890063&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">卓先委</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">24</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">67</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889571&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">杜智慧</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">25</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">68</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890299&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">史文超</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">27</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">69</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890313&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">陈北华</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">10</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">70</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890271&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">凌建明</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">13</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">71</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889577&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘勇</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">8</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+25</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt" style="text-align:center;">97</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">72</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890103&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">周红辉</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">28</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">73</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890284&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">郝军</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">1</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">74</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890262&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">胡佳</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">23</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">75</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889912&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">乐惠军</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">11</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">45</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">76</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890061&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">符俊安</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">29</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">41</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">57</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">77</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889507&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">余果</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">12</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">78</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899095&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">张俊</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">27</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+26</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">98</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">79</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889785&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">吴东泽</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">12</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+27</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">99</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">80</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889995&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">徐荣</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">28</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+27</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt" style="text-align:center;">99</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">81</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889779&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">周斌</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">6</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+27</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">11</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">55</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">44</td>
    <td class="specalt" style="text-align:center;">99</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">82</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890283&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">郝晶</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">20</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">83</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889682&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">罗金平</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">15</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">84</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890270&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘良帅</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">13</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">85</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889711&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">彭余辉</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">10</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">86</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889822&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">曹飚</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">14</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">47</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">87</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889718&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">顾原</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">24</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+28</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">100</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">88</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890204&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">范纲</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">13</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+29</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">101</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">89</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889772&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">李博</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">5</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+29</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">101</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">90</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889501&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">冯科</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">24</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+30</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">48</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt" style="text-align:center;">102</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">91</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889583&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">黄林峰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">9</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+30</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt" style="text-align:center;">102</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">92</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889588&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">孙鸽</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">2</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+31</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt" style="text-align:center;">103</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">93</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889796&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">罗琦</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">16</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+32</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt" style="text-align:center;">104</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">94</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889639&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">廖干雄</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">25</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+32</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">11</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">104</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">95</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899281&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">徐献辉</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">30</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+32</font></td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">51</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">104</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">96</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899277&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">祝卫平</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">12</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+32</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">104</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">97</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889537&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">周文海</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">19</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+33</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">46</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">11</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">59</td>
    <td class="specalt" style="text-align:center;">105</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">98</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899276&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">董锋</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">27</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+33</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">2</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">105</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">99</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890296&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">蒋文</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">15</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+34</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt" style="text-align:center;">106</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">100</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889897&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">彭晓</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">21</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+34</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt" style="text-align:center;">106</td>
  </tr> 
                          

 
 <tr>
    <td colspan="25"   height="10"></td>
  </tr>  
 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">101</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889733&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">钟长平</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">12</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+34</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt" style="text-align:center;">53</td>
    <td class="specalt" style="text-align:center;">106</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">102</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890269&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">史诚威</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">26</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+35</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">49</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">58</td>
    <td class="specalt" style="text-align:center;">107</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">103</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889887&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">刘忠江</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">29</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+35</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">52</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">55</td>
    <td class="specalt" style="text-align:center;">107</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">104</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890042&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">林杰</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">22</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+36</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">50</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">58</td>
    <td class="specalt" style="text-align:center;">108</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">105</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889823&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">罗毅</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">21</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+39</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt" style="text-align:center;">57</td>
    <td class="specalt" style="text-align:center;">111</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">106</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1889917&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">魏方</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">11</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+39</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#cd3301">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">54</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">57</td>
    <td class="specalt" style="text-align:center;">111</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">107</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890046&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">戴建明</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">30</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+51</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">60</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">63</td>
    <td class="specalt" style="text-align:center;">123</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#D5E6F6   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#D5E6F6';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">108</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1890043&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">黄炎秋</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">15</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+51</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt" style="text-align:center;">60</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">10</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt" style="text-align:center;">63</td>
    <td class="specalt" style="text-align:center;">123</td>
  </tr> 
                          

 				         
     
  <tr  bgcolor=#efefef   onmouseover="this.style.backgroundColor='#D6D6D6';this.style.color='#ffffff';"    onmouseout="this.style.backgroundColor='#efefef';this.style.color='#000000';"   >  
    <td style="background:#42a7c3;"><font color="#FFFFFF">109</font></td>
    <td style="background:#2e96b1;"><a href="/home.php?mod=space&amp;do=common&amp;op=score&amp;uid=1899280&amp;id=#qianscore" target="_blank"><font color="#FFFFFF">劳文武</font></a></td>
    <td style="background:#12748f;"><font color="#FFFFFF">30</font></td>
    <td style="background:#12748f;"><font color="#FFFFFF">+52</font></td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#5dcff1">4</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">8</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">11</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt" style="text-align:center;">64</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#000000;">3</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">7</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">6</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#0166ff">5</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt"  style="text-align:center;color:#FFffff;background:#000033">9</td>
    <td class="specalt" style="text-align:center;">60</td>
    <td class="specalt" style="text-align:center;">124</td>
  </tr> 

   <tr>
    <td colspan="25" align="right" style="background:#dae7f0; border-left: 1px solid #ffffff;" ></td>
  </tr> 
  <tr>
    <td colspan="25" align="right" bgcolor="#000033" style="background:#dae7f0; border-left: 1px solid #ffffff;" ><img src="/images/bsslt0000.jpg" width="393" height="42" /></td>
  </tr> 
</table> 
 
<!-- 底部包含文件-->
<div id="foot">
        <div style="width:966px;">
                 <div class="foot_1">
                       <div>
                              <A class=f9 
                              href="/about.php">关于大正</A> 
                              <SPAN class=f9>|</SPAN> <A class=f9 
                              href="/about.php">商务合作</A> 
                              <SPAN class=f9>|</SPAN> <A class=f9 
                              href="/zhaopin.php">招贤纳士</A> 
                              <SPAN class=f9>|</SPAN> <A class=f9 
                              href="/huoban.php" >合作伙伴</A> 
                              <SPAN class=f9>|</SPAN> <A class=f9 
                              href="/lianxi.php" >联系我们</A> 
                              
                              <SPAN class=f9>|</SPAN> <A class=f9 
                              href="/shengming.php" >免责声明</A>
                   </div>
                        <div>
                             大正网版权归北京大正承平文化传播有限公司
                        </div>
                        <div style="line-height:30px;">
                             京ICP证110339号 &nbsp;&nbsp; 京公网安备110108008116号
                        </div>
          </div>
                   <div style="float:left;"><img src="/static/space/index/images/foot-2.gif" width="7" height="168"></div>
                   <div style="float:left; padding-top:40px; padding-left:30px;"><img src="/static/space/index/images/foot-3.gif"  width="202" height="77"></div>
   </div>  

</div>
<!-- 底部包含文件-->
<script>
function change(lb){
    //alert(lb);
    if(lb==1){
        document.getElementById("rightimg1").style.display = "block";
        document.getElementById("rightimg2").style.display = "none";
        document.getElementById("rightimg3").style.display = "none";
    }else if(lb==2){
        document.getElementById("rightimg1").style.display = "none";
        document.getElementById("rightimg2").style.display = "block";
        document.getElementById("rightimg3").style.display = "none";
    }else if(lb==3){
        document.getElementById("rightimg1").style.display = "none";
        document.getElementById("rightimg2").style.display = "none";
        document.getElementById("rightimg3").style.display = "block";
    }

}

</script>
<div style="display:none"><script language="javascript" src="http://count47.51yes.com/click.aspx?id=476847339&logo=12" charset="gb2312"></script></div>
</body>
</html>