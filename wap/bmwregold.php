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

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<SCRIPT type=text/javascript>
var  formName = "golf_ticket";
</SCRIPT>

<SCRIPT type=text/javascript src="images/jquery.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/jquery.form.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/jqModal.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/bmw_scriptlib.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/forms_functions.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/silo_ecrm2_form.js"></SCRIPT>

<SCRIPT type=text/javascript src="images/golf_ticket_2012.js"></SCRIPT>
<LINK rel=stylesheet type=text/css href="images/ecrm2_default_zip.css"><LINK 
rel=stylesheet type=text/css href="images/golf_ticket.css">
<SCRIPT type=text/javascript src="images/golf_ticket_form.js"></SCRIPT>
<title></title> 
 <style>
 
 body{
	font:12px "黑体";
	text-align:center;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	color:#434343;
} 
 </style>
</head> 
<body  onclick=checkSelectBoxStatus();>

<table width="<?php echo $width;?>" border="0" align="left" cellpadding="0" cellspacing="0"><tr><td><img src="images/banner.jpg" width="<?php echo $width;?>" ></td>
</tr>
  <tr>
    <td><!-- add the info layer functionality here -->
      <h2>&nbsp;</h2>
      <h3 style="padding-left:10px;">观看2013 BMW大师赛（中国）。</h3>
      <p style="padding-left:10px;">2013年10月24日至27日，BMW大师赛将于上海举办。<br>
        即刻注册抢票，就有机会亲临现场，见证顶级赛事，观赏世界级球手的精彩表现。</p>
      <p style="padding-left:10px;">抢票以先到先得形式进行，每人每场比赛日可抢得2张亲临观赏票，观赛日期可多选。</p>
      <p style="padding-left:10px;">注册抢票即有机会获得惊喜抢票大奖。</p>
      <DIV id=formstatus class=firstRun>
<FORM id=form method=post action=/bw_api.php?mod=bwm_reg&ac=bwm_reg&no_token=1>
<DIV class=form_section>
<DIV class=below_dotted_line>
<DIV class=below_dotted_line_left>请检查红色方框内的信息是否输入正确。 </DIV>
<DIV class=below_dotted_line_right>*必填部分 </DIV></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_personal class=form_section>
<DIV id=common_personal_section_error class=section_error>请检查您输入的信息 </DIV>
<DIV id=common_personal_label class=form_section_label>姓名&nbsp; </DIV>
<DIV id=common_personal_items class=form_section_items>
<DIV id=gender_form_item class="form_item  ">
<DIV id=gender_form_item_label class=form_item_label>称谓<SPAN 
id=gender_form_item_mandatory>*</SPAN> </DIV>
<DIV id=gender_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("salutation", "请选择", false, 1988);

								</SCRIPT>
</DIV></DIV>
<DIV id=family_name_form_item class="form_item  ">
<DIV id=family_name_form_item_label class=form_item_label>姓<SPAN 
id=family_name_form_item_mandatory>*</SPAN> </DIV>
<DIV id=family_name_element class=form_item_element><INPUT id=family_name 
class=input_text maxLength=15 name=family_name> </DIV></DIV>
<DIV id=given_name_form_item class="form_item  ">
<DIV id=given_name_form_item_label class=form_item_label>名<SPAN 
id=given_name_form_item_mandatory>*</SPAN> </DIV>
<DIV id=given_name_element class=form_item_element><INPUT id=given_name 
class=input_text maxLength=30 name=given_name> </DIV></DIV>
<DIV id=birthday_form_item class=form_item>
<DIV id=birthday_form_item_label class=form_item_label>出生日期 </DIV>
<DIV style="WIDTH: 107px" id=birthday_element class=form_item_element>
<P class=text01>年</P>
<SCRIPT type=text/javascript>
									writeMySelection("edit_birthday_year", "请选择", false, 1987);

								</SCRIPT>
</DIV>
<DIV style="WIDTH: 107px" id=birthday_element class=form_item_element>
<P class=text01>月</P>
<SCRIPT type=text/javascript>
									writeMySelection("edit_birthday_month", "请选择", false, 1987);

								</SCRIPT>
</DIV>
<DIV style="WIDTH: 107px" id=birthday_element class=form_item_element>
<P class=text01>日</P>
<SCRIPT type=text/javascript>
									writeMySelection("edit_birthday_day", "请选择", false, 1987);

								</SCRIPT>
</DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_phone class=form_section>
<DIV id=common_phone_section_error class=section_error>请检查您输入的信息 </DIV>
<DIV id=common_phone_label class=form_section_label>联系信息&nbsp; </DIV>
<DIV id=common_phone_items class=form_section_items>
<DIV id=mobile_form_item class="form_item  ">
<DIV id=mobile_form_item_label class=form_item_label>手机号码<SPAN 
id=mobile_form_item_mandatory>*</SPAN> </DIV>
<DIV id=mobile_element class=form_item_element><INPUT id=mobile class=input_text 
maxLength=20 name=mobile> </DIV></DIV>
<DIV id=email_form_item class="form_item  ">
<DIV id=email_form_item_label class=form_item_label>电子邮件<SPAN 
id=email_form_item_mandatory>*</SPAN> </DIV>
<DIV id=email_element class=form_item_element><INPUT id=email class=input_text 
maxLength=35 name=email> </DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_postal class=form_section>
<DIV id=common_postal_section_error class=section_error>请检查您输入的信息 </DIV>
<DIV id=common_postal_label class=form_section_label>地址&nbsp; </DIV>
<DIV id=common_postal_items class=form_section_items>
<DIV id=province_form_item class="form_item  ">
<DIV id=province_form_item_label class=form_item_label>省份<SPAN 
id=province_form_item_mandatory>*</SPAN> </DIV>
<DIV id=province_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("province", "请选择", false, 1997);

								</SCRIPT>
</DIV></DIV>
<DIV id=city_form_item class="form_item  ">
<DIV id=city_form_item_label class=form_item_label>城市 / 城区<SPAN 
id=city_form_item_mandatory>*</SPAN> </DIV>
<DIV id=city_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("city", "请选择", false, 1996);

								</SCRIPT>
</DIV></DIV>
<DIV id=address_form_item class="form_item  ">
<DIV id=address_form_item_label class=form_item_label>地址<SPAN 
id=address_form_item_mandatory>*</SPAN> </DIV>
<DIV id=address_element class=form_item_element><TEXTAREA onKeyDown="if(this.value.length > 60) {this.value = this.value.substring(0, 60);} void(0);" id=address class=input_textarea onKeyUp="if(this.value.length > 60) {this.value = this.value.substring(0, 60);} void(0);" name=address></TEXTAREA> 
</DIV></DIV>
<DIV id=post_code_form_item class="form_item  ">
<DIV id=post_code_form_item_label class=form_item_label>邮政编码<SPAN 
id=post_code_form_item_mandatory>*（海外用户烦请亲临现场取票）</SPAN> </DIV>
<DIV id=post_code_element class=form_item_element><INPUT id=post_code 
class=input_text maxLength=10 name=post_code> </DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_ticket class=form_section>
<DIV id=common_ticket_section_error class=section_error>请检查您输入的信息 </DIV>
<DIV id=common_ticket_label class=form_section_label>观看比赛&nbsp; </DIV>
<DIV id=common_ticket_items class=form_section_items>
<DIV id=ticket_date_e_form_item class="form_item  ">
<DIV id=ticket_date_e_form_item_label class=form_item_label>请选择观看比赛的日期(可多选)<SPAN 
id=ticket_mob_form_item_mandatory>*</SPAN> </DIV>
<DIV style="COLOR: #4c4c4c; FONT-SIZE: 11px" id=ticket_date_e_form_item_label 
class=form_item_label>重复提交，以最后一次提交内容为准 </DIV>
<DIV id=ticket_date_e_element class=form_item_element>
<DIV class=ticket_date_element>
<SCRIPT type=text/javascript>
										writeMyCheckbox("ticket_date_e1", false, false);

									</SCRIPT>
</DIV>
<DIV class=ticket_date_element_r>
<SCRIPT type=text/javascript>
										writeMyCheckbox("ticket_date_e2", false, false);

									</SCRIPT>
</DIV>
<DIV class=ticket_date_element>
<SCRIPT type=text/javascript>
										writeMyCheckbox("ticket_date_e3", false, false);

									</SCRIPT>
</DIV>
<DIV class=ticket_date_element_r>
<SCRIPT type=text/javascript>
										writeMyCheckbox("ticket_date_e4", false, false);

									</SCRIPT>
</DIV></DIV></DIV><!--
						<div id="ticket_form_item" class="form_item  ">
							<div id="ticket_form_item_label" class="form_item_label">
								请选择票数<span id="ticket_form_item_mandatory" class="">*</span>
							</div>
							<div id="ticket_element" class="form_item_element">
								<script type="text/javascript">
									writeMySelection("ticket", "请选择", false, 2001);

								</script>
							</div>
						</div>
						--></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_purchase_intention class=form_section>
<DIV id=common_purchase_intention_section_error class=section_error>请检查您输入的信息 
</DIV>
<DIV id=common_purchase_intention_label class=form_section_label>您的购车计划&nbsp; 
</DIV>
<DIV id=common_purchase_intention_items class=form_section_items>
<DIV id=bmw_owner_form_item class="form_item  ">
<DIV id=bmw_owner_form_item_label class=form_item_label>是否是BMW车主？<SPAN 
id=bmw_owner_form_item_mandatory>*</SPAN> </DIV>
<DIV id=bmw_owner_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("bmw_owner", "请选择", false, 2000);

								</SCRIPT>
</DIV></DIV>
<DIV id=intended_series_form_item class=form_item>
<DIV id=intended_series_form_item_label class=form_item_label>您感兴趣的BMW车系<SPAN 
id=intended_series_form_item_mandatory>*</SPAN> </DIV>
<DIV id=intended_series_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("intended_series", "请选择", false, 1998);

								</SCRIPT>
</DIV></DIV>
<DIV id=intended_date_form_item class="form_item ">
<DIV id=intended_date_form_item_label class=form_item_label>您打算何时购买新车<SPAN 
id=intended_date_form_item_mandatory>*</SPAN> </DIV>
<DIV id=intended_date_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("intended_date", "请选择", false, 1996);

								</SCRIPT>
</DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_information_source class=form_section>
<DIV id=common_information_source_section_error class=section_error>请检查您输入的信息 
</DIV>
<DIV id=common_information_source_label class=form_section_label>了解渠道 </DIV>
<DIV style="WIDTH: 324px" id=common_information_source_items 
class=form_section_items>
<DIV style="WIDTH: 324px" id=bmw_owner_form_item class=form_item>
<DIV style="WIDTH: 324px" id=bmw_owner_form_item_label 
class=form_item_label>您从何处得到我们的信息?<SPAN 
id=information_source_form_item_mandatory>*</SPAN> </DIV>
<DIV style="WIDTH: 324px" id=information_source_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMySelection("data_information", "请选择", false, 1891);

								</SCRIPT>
</DIV>
<DIV id=information_source_element class=form_item_element><INPUT 
style="DISPLAY: none" id=information_source class=input_text maxLength=40 
name=information_source> </DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=common_data_usage class=form_section>
<DIV id=common_data_usage_section_error class=section_error>请检查您输入的信息 </DIV>
<DIV id=common_data_usage_label class=form_section_label> </DIV>
<DIV id=common_data_usage_items class=form_section_items>
<DIV id=dealer_contact_usage_form_item class="form_item  ">
<DIV id=dealer_contact_usage_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMyCheckbox("dealer_contact_usage", false, true);

								</SCRIPT>
</DIV></DIV>
<DIV id=data_usage_form_item class="form_item  ">
<DIV id=data_usage_element class=form_item_element>
<SCRIPT type=text/javascript>
									writeMyCheckbox("data_usage", true, true);

								</SCRIPT>
</DIV></DIV></DIV>
<DIV style="HEIGHT: 18px; CLEAR: both"></DIV>
<DIV class=section_seperator></DIV></DIV>
<DIV id=btn_save_form class=form_section>
<DIV class=form_section_label> </DIV>
<DIV class=form_section_items   ><A id=button_save_form class=standard_button_b 
onclick=submitForm(); href="javascript:;" style=" background:none;height:38px; width:145px; padding-bottom:10px;"><img src="/wap/images/button.jpg" width="145" height="38" /></A> </DIV></DIV></FORM></DIV>
<DIV id=washing class=loading><IMG 
style="BORDER-BOTTOM: black 1px solid; BORDER-LEFT: black 1px solid; BORDER-TOP: black 1px solid; BORDER-RIGHT: black 1px solid" 
src="images/loading.gif"> </DIV>
<DIV id=termInfoBox class="termInfoBox infoBoxLayer">
<DIV class=termInfoBoxBg></DIV>
<DIV class="termInfoText infoText">
<H1>提示</H1>
<DIV id=termInfoContent class=infoContent></DIV></DIV>
<DIV class=close><A onClick="$('.infoBoxLayer').css('display','none');" 
href="javascript:;"></A> </DIV></DIV>
<DIV 
style="Z-INDEX: 100; POSITION: absolute; WIDTH: 600px; DISPLAY: none; HEIGHT: 100px; TOP: 0px; LEFT: 0px" 
id=transparentMask><IMG 
style="BORDER-BOTTOM: 0px; BORDER-LEFT: 0px; BORDER-TOP: 0px; BORDER-RIGHT: 0px" 
src="images/1x1_trans.gif" width="100%" height="100%"> </DIV><IMG 
class=preload src="images/checkbox_error.gif"> <IMG class=preload 
src="images/checkbox_error-h.gif">  </td>
  </tr>
  <tr>
    <td><img src="images/bottom.jpg" width="<?php echo $width;?>" ></td>
  </tr>
  
</table>
</body>