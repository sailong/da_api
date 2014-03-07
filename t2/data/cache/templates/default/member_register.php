<?php /* 2013-11-15 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?>
<?php $__my=$this->
MemberHandler->MemberFields; ?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <?php $__my=$this->MemberHandler->MemberFields; ?> <base href="<?php echo $this->Config['site_url']; ?>/" /> <?php $conf_charset=$this->Config['charset']; ?> <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $conf_charset; ?>" /> <meta http-equiv="x-ua-compatible" content="ie=7" /> <title><?php echo $this->Title; ?> - <?php echo $this->Config['site_name']; ?>(<?php echo $this->Config['site_domain']; ?>)</title> <meta name="Keywords" content="<?php echo $this->MetaKeywords; ?>,<?php echo $this->Config['site_name']; ?>" /> <meta name="Description" content="<?php echo $this->MetaDescription; ?>,<?php echo $this->Config['site_notice']; ?>" /> <script type="text/javascript">
var thisSiteURL = '<?php echo $this->Config['site_url']; ?>/';
var thisTopicLength = '<?php echo $this->Config['topic_input_length']; ?>';
var thisMod = '<?php echo $this->Module; ?>';
var thisCode = '<?php echo $this->Code; ?>';
var thisFace = '<?php echo $__my['face_small']; ?>';
<?php $qun_setting = ConfigHandler::get('qun_setting'); ?> <?php if($qun_setting['qun_open']) { ?>
var isQunClosed = false;
<?php } else { ?>var isQunClosed = true;
<?php } ?>
function faceError(imgObj)
{
var errorSrc = '<?php echo $this->Config['site_url']; ?>/images/noavatar.gif';
imgObj.src = errorSrc;
}
</script> <link rel="shortcut icon" href="favicon.ico" > <link href="templates/default/styles/main.css?build+20120829" rel="stylesheet" type="text/css" /> <link href="templates/default/styles/reg.css?build+20120829" rel="stylesheet" type="text/css" /> <script type="text/javascript" src="templates/default/js/min.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/common.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/validate.js?build+20120829"></script> <style type="text/css">
.regU{ margin:0 0 20px}
.set_warp select{ margin-right:6px; margin-bottom:6px; float:left; display:inline;}
</style> <script type="text/javascript"> 
$(function(){ 
$("#inviter_nickname_input").focus(function(){$(this).css("background","#CBFE9F");$(".R_tt0").show();}).blur(function(){$(this).css("background","#FFF");$(".R_tt0").hide();});
$("#email_input").focus(function(){$(this).css("background","#CBFE9F");$(".R_tt1").show();}).blur(function(){$(this).css("background","#FFF");$(".R_tt1").hide();});
$("#password").focus(function(){$(this).css("background","#CBFE9F");$(".R_tt2").show();}).blur(function(){$(this).css("background","#FFF");$(".R_tt2").hide();});
$("#nickname_input").focus(function(){$(this).css("background","#CBFE9F");$(".R_tt4").show();}).blur(function(){$(this).css("background","#FFF");$(".R_tt4").hide();});
}); 
</script> </head> <body> <?php if($this->Config['company_enable']) { ?> <?php $d_c_name = $this->Config['default_company'] ? $this->Config['default_company'] : '单位'; $d_d_name = $this->Config['default_department'] ? $this->Config['default_department'] : '部门';  ?> <?php } ?> <div class="Rlogo"><h1 class="logo"><a title="<?php echo $this->Config['site_name']; ?>" href="index.php"></a></h1></div> <div class="main_2"> <div class="main_t"></div> <div class="set_warp"> <div class="R_L"> <form method="post"  action="<?php echo $action; ?>" name='reg' id="member_register" onSubmit="return check_submit(this, 3);"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input type="hidden" name="referer" value="<?php echo $referer; ?>"> <table width="100%" border="0"> <?php if($inviter_member['nickname']) { ?> <tr> <td align="center"><img onerror="javascript:faceError(this);" src="<?php echo $inviter_member['face']; ?>" width="54px" height="54px;"/></td> <td align="left" valign="middle"><span class="fontGreen"><?php echo $inviter_member['nickname']; ?></span> 正邀请您加入<?php echo $this->Config['site_name']; ?>，<br />
注册成功后，你们将自动相互关注，并在个人首页中看到对方分享的信息。</td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> </tr> <?php } elseif($this->Config['register_invite_input']) { ?> <tr> <td align="right" valign="top" width="90">邀请人：</td> <td><input type="text" name="inviter_nickname" id="inviter_nickname_input" value="" class="regP" /> <div class="R_tt0">可以为空，填写邀请人昵称注册成功后，你们将自动相互关注。</div></td> </tr> <?php } ?> <?php if($this->_sms_register()) { ?> <tr> <td align="right" valign="top" width="90">手机验证：</td> <td> <div id="sms_bind_area">
第一步：请输入您的手机号，并点击“获取验证码”按钮<br>
验证码将通过免费短信发到您手机上(节假日期间短信稍有延迟，请耐心等待)<br> <input type="text" value="" name="sms_bind_num" id="sms_bind_num" class="regP" onBlur="Validator.Validate(this.form,3,this.name)" />&nbsp;<input type="button" value="获取验证码" class="save" onclick="Validator.Validate(this.form,3,'sms_bind_num')&&sms_bind()" id="sms_bind_button" /> </div> </td> </tr> <tr> <td></td> <td> <div style="display:none;" id="sms_bind_verify_area">
第二步：请输入发到您手机的验证码<br> <input type="text" value="" name="sms_bind_key" id="sms_bind_key" class="regP" onBlur="Validator.Validate(this.form,3,this.name)" /> </div> <div id="sms_bind_msg"></div> <script language="javascript">        
function sms_bind()
{
var sms_num = $('#sms_bind_num').val();
if(11!=sms_num.length)
{
MessageBox('notice', '请输入11位的手机号码');
$('#sms_bind_num').focus();
return false;
}
var myAjax = $.post
(
'ajax.php?mod=sms&code=register_verify',
{
sms_num:sms_num                        
},
function (d) 
{
if(d)
{
$('#sms_bind_msg').html(d);
$('#sms_bind_num').focus();
}
else
{
$('#sms_bind_button').attr('disabled','true');
$('#sms_bind_verify_area').css('display','block');
}
}
);
}
function check_sms_bind_key()
{
var sms_num = $('#sms_bind_num').val();
var bind_key = $('#sms_bind_key').val();
if(11!=sms_num.length)
{
MessageBox('notice', '请输入11位的手机号码');
$('#sms_bind_num').focus();
return false;
}
if(6!=bind_key.length)
{
MessageBox('notice', '请输入6位的验证码');
$('#sms_bind_key').focus();
return false;
}
var myAjax = $.post(
'ajax.php?mod=sms&code=check_register_verify',
{
sms_num:sms_num,
bind_key:bind_key
},
function (d)
{
if(d)
{
$('#sms_bind_msg').html(d);
$('#sms_bind_key').focus();
}
}
);
}
</script> </td> </tr> <?php } ?> <?php if($this->Config['company_enable']) { ?> <tr> <td align="right" valign="top" width="90">所属<?php echo $d_c_name; ?>：</td> <td><div style="float:left;"><?php echo $companyselect; ?></div> <div id="check_company_result" class="error" style="display:none"></div> </tr> <?php if($this->Config['department_enable']) { ?> <tr> <td align="right" valign="top" width="90">所在<?php echo $d_d_name; ?>：</td> <td><div style="float:left;" id="departmentselect"><?php echo $departmentselect; ?></div> <div id="check_department_result" class="error" style="display:none"></div> </tr> <?php } ?> <?php } ?> <?php if(!$noemail) { ?> <tr> <td align="right" valign="top" width="90">常用Email：</td> <td><input type="text" name="email" id="email_input" value="<?php echo $email; ?>" class="regP" tabindex="1" /> <div id="check_email_result" class="error" style="display:none"></div> <div class="R_tt1">需要验证Email，用于登录和取回密码等.</div></td> </tr> <?php } ?> <tr> <td align="right" valign="top">登录密码：</td> <td><input type="password" name="password" id="password" maxlength="32" class="regP" onBlur="Validator.Validate(this.form,3,this.name)" tabindex="2"/> <div class="R_tt2">密码至少5位</div></td> </tr> <tr> <td align="right" valign="top">确认密码：</td> <td><input type="password" name="password2" id="password2" maxlength="32" class="regP" onBlur="Validator.Validate(this.form,3,this.name)" tabindex="3"/></td> </tr> <tr> <td align="right" valign="top">帐户昵称：</td> <td><input name="nickname" type="text" id="nickname_input" maxlength="15"  class="regP" tabindex="5"/> <div id="check_nickname_result" class="error" style="display:none;"></div> <div class="R_tt4">中英文均可，用于显示、@通知和发私信等</div></td> </tr> <?php if($this->
Config[city_status]) { ?> <tr> <td align="right" valign="top">所在地区：</td> <td><div style="float:left;"> <?php echo $province; ?> </div> <div style="float:left;"> <select id="city" name="city" onchange="changeCity();Validator.Validate(this.form,3,this.name)"> <option value=''>请选择</option> </select> </div> <div style="float:left;"> <select id="area" name="area" onchange="changeArea();" style="display:none"> <option value=''>请选择</option> </select> </div> <div style="float:left;"> <select id="street" name="street" style="display:none"> <option value=''>请选择</option> </select> </div> <span class="fontC" style="display:block; float:left; *float:none; *display:inline; width:400px;">（可查看同城用户、内容等）</span> </td> </tr> <?php } ?> <?php if($this->Config['seccode_register']) { ?> <tr> <td align="right" valign="top">验证码：</td> <td> <div class="ml10"> <input type="text" name="seccode" id="seccode_input" style="width:80px;" class="regP" tabindex="10"/> <div id="check_seccode_result" class="error_2" style="display:none;"></div> </div> <div class="ml11"> <script language="javascript">seccode({"id":"seccode_input"});</script> <a href="javascript:updateSeccode('seccode_input');">换一换</a> </div> </td> </tr> <?php } ?> <tr> <td align="right" valign="middle">&nbsp;</td> <td> <input name="copyrightInput" type="checkbox" id="copyrightInput" onclick="regCopyrightSubmit();" value="1" checked="checked" /> <label for="copyrightInput"> <span class="font12px"><a href="index.php?mod=other&code=regagreement" target="_blank">我已看过并同意《使用协议》</a></span></label> </td> </tr> <tr> <td align="right" valign="middle">&nbsp;</td> <td> <input id="regSubmit" class="Reg_b" type="submit" disabled value="确定注册"/> </td> </tr> </table> </form> </div> <div class="R_R"> <div class="r_tit">已有本站帐号？</div> <a class="r_loginbtn" href="<?php echo $this->Config['site_url']; ?>/index.php?mod=login" rel="nofollow" title="快捷登录" onclick="ShowLoginDialog();return false;">请点此登录</a> <div class="R_linedot"></div> <div class="r_tit">或使用其他帐户登录：</div> <div class="R_logoList"> <?php if($this->Config['sina_enable'] && sina_weibo_init()) { ?>
&nbsp; 
<?php echo sina_weibo_login('b'); ?> <?php } ?> <?php if($this->Config['qqwb_enable'] && qqwb_init()) { ?>
&nbsp; 
<?php echo qqwb_login('b'); ?> <?php } ?> <?php if($this->Config['yy_enable'] && yy_init()) { ?>
&nbsp; 
<?php echo yy_login('b'); ?> <?php } ?> <?php if($this->Config['renren_enable'] && renren_init()) { ?>
&nbsp; 
<?php echo renren_login('b'); ?> <?php } ?> <?php if($this->Config['kaixin_enable'] && kaixin_init()) { ?>
&nbsp; 
<?php echo kaixin_login('b'); ?> <?php } ?> <?php if($this->Config['fjau_enable'] && fjau_init()) { ?>
&nbsp; 
<?php echo fjau_login('b'); ?> <?php } ?> </div> </div> </div> </div> <script type="text/javascript">
function regCopyrightSubmit() {
document.getElementById('regSubmit').disabled = !document.getElementById('copyrightInput').checked;
}
regCopyrightSubmit();
$(document).ready(function(){
var selectOption=
<?php load::functions('area');echo area_config_to_json(); ?>
;
});
</script> <script language="JavaScript" type="text/javascript">
var validateRegularList={
nickname:{dataType:"LimitB",min:'3',max:'15',msg:"帐户/昵称必须在3至15个字节以内"},
password:{dataType:"LimitB",min:"5",msg:"密码过短，请设成5位以上。"},
password2:{dataType:"Repeat",to:"password",msg:"两次输入的密码不一致"},
<?php if($this->_sms_register()) { ?>
sms_bind_num:{require:true,dataType:"Mobile",min:"10",max:'12',msg:"请输入11位的手机号码"},
sms_bind_key:{require:true,dataType:"Number",min:"5",max:'7',msg:"请输入6位的验证码"},
<?php } ?> <?php if(!$noemail) { ?>
email:{require:true,dataType:"Email",msg:"Email邮箱格式不正确"},
<?php } ?>
province:{dataType:"LimitB",min:'1',msg:"请选择省/直辖市"},
city:{dataType:"LimitB",min:'1',msg:"请选择城市/地区"},
truename:{require:false,dataType:"Truename",msg:"请填写真实姓名"},
seccode:{dataType:"LimitB",min:"4",msg:"验证码不正确，重新输入下吧。"}
}
Validator.SetRegular("member_register",validateRegularList);
</script> <script type="text/javascript">
function addEvent(eventHandler)
{
var tags = document.getElementsByTagName('input');
for(var i=0;i<tags.length;i++)
{
if(tags[i].getAttribute('url') == 'true')
{
if(tags[i].addEventListener)
{
tags[i].addEventListener('keyup',eventHandler,true);
}
else
{
tags[i].attachEvent('onkeyup',eventHandler);
}
}
}
}
function addInput(e)
{
var obj = e.target ? e.target : e.srcElement;
var tags = document.getElementsByTagName('input');
for(var i=0;i<tags.length;i++)
{
if(tags[i].getAttribute('url') == 'true'&&tags[i]!=obj)
{
tags[i].value = obj.value;
}
}
}
window.onload = function()
{
addEvent(addInput);
}
</script> <SCRIPT LANGUAGE="JavaScript"> <?php if($this->_sms_register()) { ?>
$("#sms_bind_key").bind("blur", function(){check_sms_bind_key()});
<?php } ?> <?php if(!$noemail) { ?>
$("#email_input").bind("blur",function (){check('email')});
<?php } ?>
$("#nickname_input").bind("blur",function (){check('nickname')});
$("#seccode_input").bind("blur",function (){check('seccode')});
function check(obj)
{
var _objList={email:'E-MAIL地址',nickname:'帐户/昵称'};
var _objValue=$('#'+obj+'_input').val();	
if(_objValue.length==0 || Validator.Validate(document.getElementById('member_register'),3,obj)!=true) {
$("#check_"+obj+"_result").hide();
return false;
}
$("#check_"+obj+"_result").html('正在检查'+_objList[obj]+'是否可注册...');
var myAjax = $.post(
'ajax.php?mod=member',
{
code:'check_'+obj,
check_value:_objValue
},
function (r) {
if(''!=r) {
$("#check_"+obj+"_result").html(r);
$("#check_"+obj+"_result").show();
$("#check_"+obj+"_result").addClass('error');
$('#'+obj+'_input').val('');
$('#'+obj+'_input').focus();
//对验证码的特殊处理
if (obj == 'seccode') {
updateSeccode();
}
} else {
$("#check_"+obj+"_result").hide();
}
}
);
}
function changeProvince(){
var province = document.getElementById("province").value;
var url = "ajax.php?mod=member&code=sel&province="+province;
var myAjax=$.post(
url,
function(d){
$('#' + "city").html(d);
document.getElementById("street").length = 1;
document.getElementById("area").length = 1;
document.getElementById("street").style.display = "none";
document.getElementById("area").style.display = "none";
}
);
}
changeProvince();
function changeCity(){
var city = document.getElementById("city").value;
var url = "ajax.php?mod=member&code=sel&city="+city;
var myAjax=$.post(
url,
function(d){
if(d){
document.getElementById("area").style.display = "block";
$('#' + "area").html(d);
}else{
document.getElementById("area").length = 1;
document.getElementById("area").style.display = "none";
}
document.getElementById("street").style.display = "none";
document.getElementById("street").length = 1;
}
);
}
function changeArea(){
var area = document.getElementById("area").value;
var url = "ajax.php?mod=member&code=sel&area="+area;
var myAjax=$.post(
url,
function(d){
if(d){
document.getElementById("street").style.display = "block";
$('#' + "street").html(d);
}else{
document.getElementById("street").length = 1;
document.getElementById("street").style.display = "none";
}
}
);
}
function check_submit(vobj, vtype)
{
return Validator.Validate(vobj, vtype)&&check('seccode')
<?php if($this->_sms_register()) { ?>
&&check_sms_bind_key()
<?php } ?>
;
}
function changedepartment(id){
var cid = 'undefined' == typeof(id) ? 0 : id;
var myAjax=$.post("ajax.php?mod=member&code=cp",{cid:cid},function(d){if(d){$('#' + "departmentselect").html(d);}});
}
</SCRIPT><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script type="text/javascript" src="templates/default/js/jsgst.js?build+20120829"></script> <div id="show_message_area"></div> <?php echo $this->js_show_msg(); ?> <?php echo $GLOBALS['schedule_html']; ?> <?php if($GLOBALS['jsg_schedule_mark'] || jsg_getcookie('jsg_schedule')) echo jsg_schedule(); ?> <div id="ajax_output_area"></div> <?php if(MEMBER_ID ==0) { ?> <style type="text/css">
.bottomLinks{width:930px;}
.bottomLinks .bL_info{width:180px;}
</style> <?php } ?> <div class="bottomLinks_R"> <div class="bottomLinks <?php echo $t_col_foot; ?> bottomLinks_reg"> <div class="bL_List"> <div class="bL_info bL_io1 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">找感兴趣的人</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=people">名人堂</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=media">媒体汇</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=top">排行榜</a></li> <li class="MIB_linkar"><a href="index.php?mod=profile&code=maybe_friend" rel="nofollow">猜你喜欢的</a></li> </ul> </div> <div class="bL_info bL_io2 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">精彩内容</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=live">微直播</a></li> <li class="MIB_linkar"><a href="index.php?mod=talk">微访谈</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=new">最新微博</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=recd">官方推荐</a></li> </ul> </div> <div class="bL_info bL_io3 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">应用热门</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=show&code=show">微博秀</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=photo">图片墙</a></li> <li class="MIB_linkar"><a href="index.php?mod=wall&code=control">上墙</a></li> <li class="MIB_linkar"><a href="index.php?mod=tools&code=qmd">图片签名档</a></li> </ul> </div> <div class="bL_info bL_io4 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">手机玩微博</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=other&code=wap">WAP访问</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=mobile" target=_blank>3G网页</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=android">android客户端</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=iphone">iphone客户端</a></li> </ul> </div> <div class="bL_info bL_io5 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">关于我们</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=other&code=contact">联系我们</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=vip_intro">申请V认证</a></li> <?php if(!empty($navigation_config['pluginmenu'])) { ?> <?php if(is_array($navigation_config['pluginmenu'])) { foreach($navigation_config['pluginmenu'] as $pmenus) { ?> <?php if(is_array($pmenus)) { foreach($pmenus as $pmenu) { ?> <?php if($pmenu['type'] == 2) { ?> <li><a href="<?php echo $pmenu['url']; ?>" target="<?php echo $pmenu['target']; ?>"><?php echo $pmenu['name']; ?></a></li> <?php } ?> <?php } } ?> <?php } } ?> <?php } ?> <li><?php echo $this->Config['tongji']; ?></li> <li class="MIB_linkar"> <a href="http://www.miibeian.gov.cn/" target="_blank" title="网站备案" rel="nofollow"><?php echo $this->Config['icp']; ?></a></li> <li class="MIB_linkar"> <?php $__server_execute_time = round(microtime(true) - $GLOBALS['_J']['time_start'], 5) . " Second "; ?> <?php $__gzip_tips = ((defined('GZIP') && GZIP) ? "&nbsp;Gzip Enable." : "Gzip Disable."); ?> <span title="<?php echo $__server_execute_time; ?>,<?php echo $__gzip_tips; ?>">网页执行信息</span> <?php echo upsCtrl()->Comlic(); ?></li> <li><?php echo $this->Config['copyright']; ?></li> </ul> </div> </div> </div></div> <script type="text/javascript">
$(document).ready(function(){
//图片延迟加载
$("ul.imgList img, div.avatar img.lazyload").lazyload({
skip_invisible : false,
threshold : 200,
effect : "fadeIn"
});
$('.goTop').click(function(e){
e.stopPropagation();
$('html, body').animate({scrollTop: 0},300);
backTop();
return false;
});
});
</script> <div id="backtop" class="backTop"><a href="/#" class="goTop" title="返回顶部"></a></div> <script type="text/javascript">
window.onscroll=backTop;
function backTop(){
var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
if(scrollTop==0){
document.getElementById('backtop').style.display="none";
}else{
document.getElementById('backtop').style.display="block";
}
}
backTop();
</script> </body> </html> <?php echo $GLOBALS['iframe']; ?>