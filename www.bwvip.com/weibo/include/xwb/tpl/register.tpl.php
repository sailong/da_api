<?php if (!defined('IS_IN_XWB_PLUGIN')) {die('access deny!');}?><?php if('UTF-8' != strtoupper($GLOBALS['jsg_sys_config']['charset'])){@header("Content-type: text/html; charset=utf-8");}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册页面</title>
<link type="text/css" rel="stylesheet" href="<?php echo XWB_plugin::getPluginUrl('images/xwb_'. XWB_S_VERSION .'.css');?>" />
<script language="javascript">
	document.onkeydown = function (e){
			var ev =  e || window.event ;
			if(ev.keyCode==13 && parent.XWBcontrol.isEndReg){
				parent.XWBcontrol.close('reg');
				window.top.location='index.php';
			}
	}

	function $id (id) {return document.getElementById(id);}
	
	function xwbSetTips(msg){
		/// 注册成功
		if (msg[0]>0){
			// 是否提示成功
			if (msg[2]==1){
                var regBox = $id('regBox');
                var action = 'none' == regBox.style.display ? 'bind' : 'reg';
                var head = 'none' == regBox.style.display ? 'forBindH3' : 'forRegH3';
                var forHead = $id(head);
                forHead.style.display = 'none';
				$id(action+'SuccessBox').style.display	= '';
				$id(action+'Box').style.display			= 'none';
				$id(action+'SuccessTips').innerHTML		= msg[1];
				parent.XWBcontrol.isEndReg = true;
				parent.XWBcontrol.ck.del('xwb_tips_type');
			}else{
				window.top.location='index.php';
			}
		}else{
			setError(msg[1]);
		}
	}
	
	function submitForm(id){
        clearError();
		$id(id).submit();
	}
	
	function setError(msg){
		clearError();
        var action = 'none' == $id('regBox').style.display ? 'bind' : 'reg';
		$id(action+'ErrorTips').style.display = '';
		$id(action+'ErrorTips').innerHTML = msg;
	}
	
	function clearError(){
        var action = 'none' == $id('regBox').style.display ? 'bind' : 'reg';
		$id(action+'ErrorTips').style.display = 'none';
	}

    function forTurn(action) {
        $id(action+'ErrorTips').style.display = 'none';
        var title = parent.document.getElementById('_xwb_dlg_tle');
        var forRegH3 = $id('forRegH3');
        var forBindH3 = $id('forBindH3');
        var regBox = $id('regBox');
        var bindBox = $id('bindBox');
        //var inputIds = new Array('siteRegName', 'siteRegEmail', 'regPwd', 'siteBindName', 'bindPwd', 'questionid', 'questionanswer');
        //var x;
        //for (x in inputIds) {
            //if ('questionid' == inputIds[x]) {
                //$id(inputIds[x]).value = 0;
            //} else {
                //$id(inputIds[x]).value = ''
            //}
        //};

        if ('reg' == action) {
            title.innerHTML = '用户登录';
            forBindH3.style.display = 'none';
            bindBox.style.display = 'none';
            forRegH3.style.display = '';
            regBox.style.display = '';
        } else {
            title.innerHTML = '帐号绑定';
            forRegH3.style.display = 'none';
            regBox.style.display = 'none';
            forBindH3.style.display = '';
            bindBox.style.display = '';
        }
    }
</script>
</head>

<body id="xwb-plugin-register-layer" class="xwb-plugin" style="display:block;">
<h3 id="forRegH3" class="xwb-plugin-layer-title">您现在正在用<a class="xwb-plugin-td-right-a" href="http://t.sina.com.cn/<?php echo $sina_user_info['id'];?>" target="_blank">新浪微博</a>登录，为了让您更好地使用<?php echo XWB_S_TITLE ;?>提供的服务，请设置以下信息：</h3>
<h3 id="forBindH3" class="xwb-plugin-layer-title" style="display:none;">输入已有的<?php echo XWB_S_TITLE ;?>帐号，绑定<a class="xwb-plugin-td-right-a" href="http://t.sina.com.cn/<?php echo $sina_user_info['id'];?>" target="_blank">新浪微博帐号</a>。</h3>
<div id="regBox" class="xwb-plugin-form" style="display:block;">
    
    <?php if($GLOBALS['jsg_sys_config'][invite_enable] && !$GLOBALS['jsg_sys_config'][invite_by_admin]) { ?>
    
       非常抱歉，本站目前需要有邀请链接才能注册；<a href="javascript:void(function(){})" class="xwb-plugin-td-right-a" onclick="forTurn('bind')" tabindex="-1"><span><strong>请点此绑定已有的帐号</strong></span></a>
                
    <?php } else { ?>
    
    <form action="<?php echo XWB_plugin::URL("xwbSiteInterface.doReg");?>" id="siteRegFrom"  method="post" target="xwbSiteRegister"  >
		<input type="hidden" name="FORMHASH" value="<?php echo FORMHASH; ?>" />
        <table class="xwb-plugin-table">
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="regPwd"> 设置用户名：</label></td>
                <td class="xwb-plugin-td-input">
                    <input type="text" name="siteRegName"	id="siteRegName" class="xwb-plugin-input-a" value="<?php if(preg_match('~^[\w\d\.\-\_]+$~i',$sina_user_info['screen_name'])) echo $sina_user_info['screen_name'];?>" />
                    <br />用于登录本站，只允许字母数字
                </td>
                <td rowspan="4" class="xwb-plugin-td-right-msg">
                    已经有<?php echo XWB_S_TITLE ;?>帐号？<a href="javascript:void(function(){})" class="xwb-plugin-td-right-a" onclick="forTurn('bind')" tabindex="-1"><strong>点击这里进行登录</strong></a>
                </td>
            </tr>
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="siteRegEmail"> 昵称：</label></td>
                <td class="xwb-plugin-td-input">
                    <input type="text" name="siteRegNickname"	id="siteRegNickname" class="xwb-plugin-input-a" value="<?php echo $sina_user_info['screen_name'];?>" />
                    <br />用于显示、@通知和发送站内短信
                </td>
            </tr>
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="siteRegEmail"> 邮箱：</label></td>
                <td class="xwb-plugin-td-input">
                    <input type="text" name="siteRegEmail"	id="siteRegEmail" class="xwb-plugin-input-a" />
                </td>
            </tr>
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="regPwd"> 密码：</label></td>
                <td class="xwb-plugin-td-input">
                    <input name="regPwd" type="password"  class="xwb-plugin-input-a" id="regPwd" maxlength="256" />
                </td>
            </tr>
            <tr class="xwb-plugin-tr-btn">
                <td colspan="3" class="xwb-plugin-td-btn">
                    <span class="xwb-plugin-btn">
                        <input name="registerBt1" type="button" onclick="submitForm('siteRegFrom')" id="registerBt1" value="提 交" />
                    </span>
                </td>
            </tr>
            <tr class="xwb-plugin-tr-error">
                <td colspan="3">
                    <em id="regErrorTips" class="xwb-plugin-error" style="display:none;"></em>
                </td>
            </tr>
        </table>
    </form>
    
    <?php } ?>
    
</div>

<div id="bindBox" class="xwb-plugin-form" style="display:none;">
    <form action="<?php echo XWB_plugin::URL("xwbSiteInterface.doBindAtNotLog");?>" id="siteBindFrom"  method="post" target="xwbSiteRegister"  >
		<input type="hidden" name="FORMHASH" value="<?php echo FORMHASH; ?>" />
        <table class="xwb-plugin-table">
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="bindPwd"> 用户名：</label></td>
                <td class="xwb-plugin-td-input">
                    <input type="text" name="siteBindName"	id="siteBindName" class="xwb-plugin-input-a" value="" />
                    <br />请输入您在 <?php echo XWB_S_TITLE; ?> 的用户名
                </td>
                <td rowspan="4" class="xwb-plugin-td-right-msg">
                    没有<?php echo XWB_S_TITLE ;?>帐号？<a href="javascript:void(function(){})" class="xwb-plugin-td-right-a" onclick="forTurn('reg')" tabindex="-1"><strong>点击这里进行注册</strong></a>
                </td>
            </tr>
            <tr class="xwb-plugin-tr">
                <td class="xwb-plugin-td-msg"><label for="bindPwd"> 密码：</label></td>
                <td class="xwb-plugin-td-input">
                    <input name="bindPwd" type="password" class="xwb-plugin-input-a" id="bindPwd" maxlength="256" />
                    <br />请输入对应的密码
                </td>
            </tr>
            <tr class="xwb-plugin-tr-btn">
                <td colspan="3" class="xwb-plugin-td-btn">
                    <span class="xwb-plugin-btn">
                        <input name="bindBt" type="button" onclick="submitForm('siteBindFrom')" id="bindBt" value="绑 定" />
                    </span>
                </td>
            </tr>
            <tr class="xwb-plugin-tr-error">
                <td colspan="3">
                    <em id="bindErrorTips" class="xwb-plugin-error" style="display:none;"></em>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="regSuccessBox" style="display:none;" class="xwb-plugin-reg-successBox">
	<div class="xwb-plugin-reg-successBox-forIE6">
        <p class="xwb-plugin-regSuccess xwb-plugin-reg-successBox-p">
            <strong>恭喜，创建成功!</strong>
            <span id="regSuccessTips" ></span>
        </p>
    </div>
    <div class="xwb-plugin-reg-successBox-btn">
        <span class="xwb-plugin-btn">
            <input name="registerBt2" type="button" onclick="window.top.location='index.php';" id="registerBt2" value="确 定" />
        </span>
    </div>
</div>

<div id="bindSuccessBox" style="display:none;" class="xwb-plugin-reg-successBox">
	<div class="xwb-plugin-reg-successBox-forIE6">
        <p class="xwb-plugin-regSuccess xwb-plugin-reg-successBox-p">
            <strong>恭喜，绑定成功!</strong>
            <span id="bindSuccessTips" ></span>
        </p>
    </div>
    <div class="xwb-plugin-reg-successBox-btn">
        <span class="xwb-plugin-btn">
            <input name="bindBt2" type="button" onclick="window.top.location='index.php';" id="bindBt2" value="确 定" />
        </span>
    </div>
</div>
<iframe src="" name="xwbSiteRegister" frameborder="0" height="0" width="0"></iframe>
</body>
</html>
