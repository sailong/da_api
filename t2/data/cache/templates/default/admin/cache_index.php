<?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <?php $__my=$this->MemberHandler->MemberFields; ?> <?php $conf_charset=$this->Config['charset']; ?> <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $conf_charset; ?>" /> <link href="./templates/default/styles/admincp.css?build+20120829" rel="stylesheet" type="text/css" /> <script type="text/javascript">
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
</script> <script type="text/javascript" type="text/javascript" src="./templates/default/js/cookies.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/min.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/common.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/admin_script_common.js?build+20120829"></script> <script language="JavaScript">
function checkalloption(form, value) {
for(var i = 0; i < form.elements.length; i++) {
var e = form.elements[i];
if(e.value == value && e.type == 'radio' && e.disabled != true) {
e.checked = true;
}
}
}
function checkallvalue(form, value, checkall) {
var checkall = checkall ? checkall : 'chkall';
for(var i = 0; i < form.elements.length; i++) {
var e = form.elements[i];
if(e.type == 'checkbox' && e.value == value) {
e.checked = form.elements[checkall].checked;
}
}
}
function zoomtextarea(objname, zoom) {
zoomsize = zoom ? 10 : -10;
obj = $(objname);
if(obj.rows + zoomsize > 0 && obj.cols + zoomsize * 3 > 0) {
obj.rows += zoomsize;
obj.cols += zoomsize * 3;
}
}
function redirect(url) {
window.location.replace(url);
}
function checkall(form, prefix, checkall) {
var checkall = checkall ? checkall : 'chkall';
for(var i = 0; i < form.elements.length; i++) {
var e = form.elements[i];
if(e.name != checkall && (!prefix || (prefix && e.name.match(prefix)))) {
e.checked = form.elements[checkall].checked;
}
}
}
var collapsed = Cookies.getCookie('guanzhu_collapse');
function collapse_change(menucount) {
if(document.getElementById('menu_' + menucount).style.display == 'none') {
document.getElementById('menu_' + menucount).style.display = '';collapsed = collapsed.replace('[' + menucount + ']' , '');
$('menuimg_' + menucount).src = './templates/default/images/admincp/menu_reduce.gif';
} else {
document.getElementById('menu_' + menucount).style.display = 'none';collapsed += '[' + menucount + ']';
$('menuimg_' + menucount).src = './templates/default/images/admincp/menu_add.gif';
}
Cookies.setCookie('guanzhu_collapse', collapsed, 2592000);
}
function advance_search(o)
{
o.innerHTML=$('advance_search').visible()?"高级搜索":"简单搜索";
$('advance_search').toggle();
return false;
}
</script> </head> <body> <div id="show_message_area"></div> <table width="100%" border="0" cellpadding="2" cellspacing="6" style="_margin-left:-10px; "> <tr> <td><table width="100%" border="0" cellpadding="2" cellspacing="6"> <tr> <td> <?php if($__is_messager!=true) { ?> <div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;"> <div style="float:left;"><a href="admin.php?mod=index&code=home">控制面板首页</a>&nbsp;&raquo;&nbsp;
<?php if($pluginconfig && $pluginname) { ?> <?php echo $pluginconfig; ?>&nbsp;&raquo;&nbsp;<?php echo $pluginname; ?> <?php } elseif($this->pluginconfig && $this->pluginname) { ?> <?php echo $this->pluginconfig; ?>&nbsp;&raquo;&nbsp;<?php echo $this->pluginname; ?> <?php } else { ?> <?php echo $this->actionName(); ?> <?php } ?> </div> <?php if($this->RoleActionId) { ?> <div style="float: right;"><a title="查看谁操作过这个页面" href="admin.php?mod=logs&role_action_id=<?php echo $this->RoleActionId; ?>"><b style="color:red">查看当前页操作记录</b></a></div> <?php } ?> </div> <?php } ?> <?php if($this->Config['company_enable']) { ?> <?php $d_c_name = $this->Config['default_company'] ? $this->Config['default_company'] : '单位'; $d_d_name = $this->Config['default_department'] ? $this->Config['default_department'] : '部门';  ?> <?php } ?> <?php $sub_menu_list = $_sub_menu_list?$_sub_menu_list:get_sub_menu(); ?> <?php if($sub_menu_list) { ?> <div class="nav3"> <ul class="cc"> <?php if(is_array($sub_menu_list)) { foreach($sub_menu_list as $value) { ?> <?php if($value['type'] == '1' && PLUGINDEVELOPER < 1)continue; ?> <li 
<?php if($value['current']) { ?>
class="current"
<?php } ?>
> <?php if($this->pluginid) { ?> <a href="<?php echo $value['link']; ?>&id=<?php echo $this->pluginid; ?>"> <?php } else { ?><a href="<?php echo $value['link']; ?>"> <?php } ?> <?php echo $value['name']; ?></a> </li> <?php } } ?> </ul> </div> <?php } ?> <br /> <table cellspacing="1" cellpadding="4" width="100%" align="center"
class="tableborder"> <tr class="header"> <td>技巧提示</td> </tr> <tr class="altbg1"> <td> <ul> <li>当站点进行了数据恢复、升级或者工作出现异常的时候，您可以使用本功能重新生成缓存。更新缓存的时候，可能让服务器负载升高，请尽量避开会员访问的高峰时间</li> <li>模板缓存：更新站点模板、风格等缓存文件，当您修改了模板或者风格，但是没有立即生效的时候使用</li> <li>数据缓存：更新站点的全部数据缓存</li> </ul> </td> </tr> </table> <br> <form method="post"  name="settings"
action="admin.php?mod=cache&code=do_clean"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center"
class="tableborder"> <tr class="header"> <td>清空缓存操作</td> </tr> <tr> <td> <center><label><input type="checkbox" name="type[]"
value="tpl" checked />模板缓存</label> <label><input type="checkbox"
name="type[]" value="data" />数据缓存</label></center> </td> </tr> </table> <br> <center><input type="submit" class="button"
name="settingsubmit" value="提 交"></center> <br> </form> <br>