/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Package JishiGou $
 *
 * @Filename share.js $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2012-04-23 17:49:35 1297685122 1850594445 37910 $
 *******************************************************************/


if(typeof Sina=="undefined"){Sina={}}Sina.pkg=function(ns){if(!ns||!ns.length){return null}var levels=ns.split(".");var nsobj=Sina;for(var i=(levels[0]=="Sina")?1:0;i<levels.length;++i){nsobj[levels[i]]=nsobj[levels[i]]||{};nsobj=nsobj[levels[i]]}return nsobj};function $E(oID){var node=typeof oID=="string"?document.getElementById(oID):oID;if(node!=null){return node}else{}return null}function $C(tagName){return document.createElement(tagName)}function $N(name){return document.getElementsByName(name)}function $G(){}function $G2(){}function v5SendLog(){}try{document.execCommand("BackgroundImageCache",false,true)}catch(e){}(function(){var funcName="trace";var _traceList=[];var _startTime=new Date().valueOf();var _curTime=new Date().valueOf();var _runTime;var _trace=function(sText,oOption,sBgColor){oOption=oOption||{};if(typeof oOption=="string"){oOption={"color":oOption};if(typeof sBgColor!="undefined"&&typeof sBgColor=="string"){oOption.bgColor=sBgColor}}_traceList[_traceList.length]=[sText,oOption]};var _traceError=function(oError){_trace(oError,{"color":"#F00"})};_trace.error=_traceError;_trace.traceList=_traceList;_trace.toString=function(){return"Trace调试已关闭"};window[funcName]=_trace;window.traceError=_traceError})();Sina.pkg("Core");if(typeof Core=="undefined"){Core=Sina.Core}Sina.pkg("Core.Array");Core.Array.each=function(ar,insp){var r=[];for(var i=0;i<ar.length;i++){var x=insp(ar[i],i);if(x!==null){r.push(x)}}return r};function Jobs(){this._jobTable=[]}Jobs.prototype={_registedJobTable:{},initialize:function(){},_registJob:function(jobName,rel){this._registedJobTable[jobName]=rel},add:function(jobName){this._jobTable.push(jobName)},start:function(){var jobs=this._jobTable;var regJobs=this._registedJobTable;var i=0;var joblen=this._jobTable.length;var getTime=function(){return new Date().valueOf()};var interNum=window.setInterval(function(){if(i>=joblen){clearInterval(interNum);return}var jobName=jobs[i];var job=regJobs[jobName];i++;if(typeof job=="undefined"){trace("<b>Job["+jobName+"] is undefiend!!!</b>",{"color":"#900","bgColor":"#FFF;"});return}var _try=true;var _start=getTime();try{job.call()}catch(e){trace("<b>Job["+jobName+"] failed!!!</b>",{"color":"#900","bgColor":"#666;"});traceError(e);_try=false}finally{if(_try){var _end=getTime();trace("<b>Job["+jobName+"] done in "+(_end-_start)+"ms.</b>",{"color":"#0F0","bgColor":"#666;"})}}},10)},call:function(jobName,args){if(typeof this._registedJobTable[jobName]!="undefined"){this._registedJobTable[jobName].apply(this,args)}else{trace("<b>Job["+jobName+"] is undefined!!!</b>",{"color":"#900","bgColor":"#FFF;"})}}};$registJob=function(name,rel){Jobs.prototype._registJob(name,rel)};$callJob=function(name){var args=[];if(arguments.length>1){Core.Array.foreach(arguments,function(v,i){args[i]=v});args.shift()}Jobs.prototype.call(name,args)};if(typeof App=="undefined"){var App={}}Sina.pkg("Core.String");Core.String.trimHead=function(str){return str.replace(/^(\u3000|\s|\t)*/gi,"")};Core.String.trimTail=function(str){return str.replace(/(\u3000|\s|\t)*$/gi,"")};Core.String.trim=function(str){return Core.String.trimHead(Core.String.trimTail(str))};Sina.pkg("Core.Events");Core.Events.addEvent=function(elm,func,evType,useCapture){var _el=$E(elm);if(_el==null){trace("addEvent 找不到对象："+elm);return}if(typeof useCapture=="undefined"){useCapture=false}if(typeof evType=="undefined"){evType="click"}if(_el.addEventListener){_el.addEventListener(evType,func,useCapture);return true}else{if(_el.attachEvent){var r=_el.attachEvent("on"+evType,func);return true}else{_el["on"+evType]=func}}};Sina.pkg("Core.Base");(function(){var Detect=function(){var ua=navigator.userAgent.toLowerCase();this.$IE=/msie/.test(ua);this.$OPERA=/opera/.test(ua);this.$MOZ=/gecko/.test(ua);this.$IE5=/msie 5 /.test(ua);this.$IE55=/msie 5.5/.test(ua);this.$IE6=/msie 6/.test(ua);this.$IE7=/msie 7/.test(ua);this.$SAFARI=/safari/.test(ua);this.$winXP=/windows nt 5.1/.test(ua);this.$winVista=/windows nt 6.0/.test(ua);this.$FF2=/Firefox\/2/i.test(ua)};Core.Base.detect=new Detect()})();Core.Events.getEvent=function(){return window.event};if(!Core.Base.detect.$IE){Core.Events.getEvent=function(){if(window.event){return window.event}var o=arguments.callee.caller;var e;var n=0;while(o!=null&&n<40){e=o.arguments[0];if(e&&(e.constructor==Event||e.constructor==MouseEvent)){return e}n++;o=o.caller}return e}}Core.Events.fixEvent=function(e){if(typeof e=="undefined"){e=window.event}if(!e.target){e.target=e.srcElement;e.pageX=e.x;e.pageY=e.y}if(typeof e.layerX=="undefined"){e.layerX=e.offsetX}if(typeof e.layerY=="undefined"){e.layerY=e.offsetY}return e};Core.Events.getEventTarget=function(ev){ev=ev||Core.Events.getEvent();Core.Events.fixEvent(ev);return ev.target};Core.String.byteLength=function(str){if(typeof str=="undefined"){return 0}var aMatch=str.match(/[^\x00-\x80]/g);return(str.length+(!aMatch?0:aMatch.length))};Core.String.leftB=function(str,len){var s=str.replace(/\*/g," ").replace(/[^\x00-\xff]/g,"**");str=str.slice(0,s.slice(0,len).replace(/\*\*/g," ").replace(/\*/g,"").length);if(Core.String.byteLength(str)>len){str=str.slice(0,str.length-1)}return str};Sina.pkg("Core.Dom");Core.Dom.getLeft=function(element){var left=0;var el=$E(element);if(el.offsetParent){while(el.offsetParent){left+=el.offsetLeft;el=el.offsetParent}}else{if(el.x){left+=el.x}}return left};Core.Dom.getTop=function(element){var top=0;var el=$E(element);if(el.offsetParent){while(el.offsetParent){top+=el.offsetTop;el=el.offsetParent}}else{if(el.y){top+=el.y}}return top};Sina.pkg("Utils");if(typeof Utils=="undefined"){Utils=Sina.Utils}Sina.pkg("Utils.Io");Utils.Url=function(url){url=url||"";this.url=url;this.query={};this.parse()};Utils.Url.prototype={parse:function(url){if(url){this.url=url}this.parseAnchor();this.parseParam()},parseAnchor:function(){var anchor=this.url.match(/\#(.*)/);anchor=anchor?anchor[1]:null;this._anchor=anchor;if(anchor!=null){this.anchor=this.getNameValuePair(anchor);this.url=this.url.replace(/\#.*/,"")}},parseParam:function(){var query=this.url.match(/\?([^\?]*)/);query=query?query[1]:null;if(query!=null){this.url=this.url.replace(/\?([^\?]*)/,"");this.query=this.getNameValuePair(query)}},getNameValuePair:function(str){var o={};str.replace(/([^&=]*)(?:\=([^&]*))?/gim,function(w,n,v){if(n==""){return}o[n]=v||""});return o},getParam:function(sPara){return this.query[sPara]||""},clearParam:function(){this.query={}},setParam:function(name,value){if(name==null||name==""||typeof(name)!="string"){throw new Error("no param name set")}this.query=this.query||{};this.query[name]=value},setParams:function(o){this.query=o},serialize:function(o){var ar=[];for(var i in o){if(o[i]==null||o[i]==""){ar.push(i+"=")}else{ar.push(i+"="+o[i])}}return ar.join("&")},toString:function(){var queryStr=this.serialize(this.query);return this.url+(queryStr.length>0?"?"+queryStr:"")+(this.anchor?"#"+this.serialize(this.anchor):"")},getHashStr:function(forceSharp){return this.anchor?"#"+this.serialize(this.anchor):(forceSharp?"#":"")}};Core.String.encodeDoubleByte=function(str){if(typeof str!="string"){return str}return encodeURIComponent(str)};Utils.Io.Ajax={createRequest:function(){var request=null;try{request=new XMLHttpRequest()}catch(trymicrosoft){try{request=new ActiveXObject("Msxml2.XMLHTTP")}catch(othermicrosoft){try{request=ActiveXObject("Microsoft.XMLHTTP")}catch(failed){}}}if(request==null){trace("create request failed")}else{return request}},request:function(url,option){option=option||{};option.onComplete=option.onComplete||function(){};option.onException=option.onException||function(){};option.returnType=option.returnType||"txt";option.method=option.method||"get";option.data=option.data||{};if(typeof option.GET!="undefined"&&typeof option.GET.url_random!="undefined"&&option.GET.url_random==0){this.rand=false;option.GET.url_random=null}this.loadData(url,option)},loadData:function(url,option){var request=this.createRequest(),tmpArr=[];var _url=new Utils.Url(url);if(option.POST){for(var postkey in option.POST){var postvalue=option.POST[postkey];if(postvalue!=null){tmpArr.push(postkey+"="+Core.String.encodeDoubleByte(postvalue))}}}var sParameter=tmpArr.join("&")||"";if(option.GET){for(var key in option.GET){if(key!="url_random"){_url.setParam(key,Core.String.encodeDoubleByte(option.GET[key]))}}}if(this.rand!=false){_url.setParam("rnd",Math.random())}request.onreadystatechange=function(){if(request.readyState==4){var response,type=option.returnType;try{switch(type){case"txt":response=request.responseText;break;case"xml":if(Core.Base.detect.$IE){response=request.responseXML}else{var Dparser=new DOMParser();response=Dparser.parseFromString(request.responseText,"text/xml")}break;case"json":response=eval("("+request.responseText+")");break}option.onComplete(response)}catch(e){option.onException(e.message,_url);return false}}};try{if(option.POST){request.open("POST",_url,true);request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");trace(sParameter);request.send(sParameter)}else{request.open("GET",_url,true);request.send(null)}}catch(e){option.onException(e.message,_url);return false}}};Core.Events.fireEvent=function(oElement,sEvent){oElement=$E(oElement);if($IE){oElement.fireEvent("on"+sEvent)}else{var evt=document.createEvent("HTMLEvents");evt.initEvent(sEvent,true,true);oElement.dispatchEvent(evt)}};Core.Dom.setStyle=function(el,property,val){switch(property){case"opacity":el.style.filter="alpha(opacity="+(val*100)+")";if(!el.currentStyle||!el.currentStyle.hasLayout){el.style.zoom=1}break;case"float":property="styleFloat";default:el.style[property]=val}};if(!Core.Base.detect.$IE){Core.Dom.setStyle=function(el,property,val){if(property=="float"){property="cssFloat"}el.style[property]=val}}Sina.pkg("Core.System");Core.System.winSize=function(_target){var w,h;if(_target){target=_target.document}else{target=document}if(self.innerHeight){if(_target){target=_target.self}else{target=self}w=target.innerWidth;h=target.innerHeight}else{if(target.documentElement&&target.documentElement.clientHeight){w=target.documentElement.clientWidth;h=target.documentElement.clientHeight}else{if(target.body){w=target.body.clientWidth;h=target.body.clientHeight}}}return{width:w,height:h}};Core.System.pageSize=function(_target){if(_target){target=_target.document}else{target=document}var _rootEl=(target.compatMode=="CSS1Compat"?target.documentElement:target.body);var xScroll,yScroll;if(window.innerHeight&&window.scrollMaxY){xScroll=_rootEl.scrollWidth;yScroll=window.innerHeight+window.scrollMaxY}else{if(_rootEl.scrollHeight>_rootEl.offsetHeight){xScroll=_rootEl.scrollWidth;yScroll=_rootEl.scrollHeight}else{xScroll=_rootEl.offsetWidth;yScroll=_rootEl.offsetHeight}}var win_s=Core.System.winSize(_target);if(yScroll<win_s.height){pageHeight=win_s.height}else{pageHeight=yScroll}if(xScroll<win_s.width){pageWidth=win_s.width}else{pageWidth=xScroll}return[pageWidth,pageHeight,win_s.width,win_s.height]};App.iframeMask=function(zIndex,fResize){var IM={};var oParent=IM.oParent=document.getElementsByTagName("body")[0];var oMask=IM.oMask=oParent.appendChild($C("div"));var oProtective=IM.oProtective=oParent.appendChild($C("iframe"));oProtective.frameborder=0;var oMStyle=oMask.style;var oPStyle=oProtective.style;var oPStyle=oProtective.style;oMStyle.top=oPStyle.top="0px";oMStyle.left=oPStyle.left="0px";oMStyle.overflow=oPStyle.overflow="hidden";oMStyle.border=oPStyle.border="0px";oMStyle.position=oPStyle.position="absolute";oMStyle.display=oPStyle.display="none";oMStyle.backgroundColor=oPStyle.backgroundColor="#000000";oMStyle.zIndex=zIndex||799;oPStyle.zIndex=(zIndex-1)||798;Core.Dom.setStyle(oMask,"opacity","0.15");Core.Dom.setStyle(oProtective,"opacity","0");IM.oMaskResize=(function(p){return function(){var pageSize=Core.System.pageSize();p.oMask.style.width=p.oProtective.style.width=Math.max(document.body.scrollWidth,(document.documentElement)?document.documentElement.scrollWidth:0)+"px";p.oMask.style.height=p.oProtective.style.height=pageSize[1]+"px";if(fResize){fResize(pageSize)}}})(IM);IM.hidden=(function(p){return function(){p.oMask.style["display"]=p.oProtective.style["display"]="none"}})(IM);IM.show=(function(p){return function(){p.oMask.style["display"]=p.oProtective.style["display"]="block"}})(IM);IM.oMaskResize();Core.Events.addEvent(window,IM.oMaskResize,"resize");return IM};Sina.pkg("Utils.Cookie");Utils.Cookie.getCookie=function(name){name=name.replace(/([\.\[\]\$])/g,"\\$1");var rep=new RegExp(name+"=([^;]*)?;","i");var co=document.cookie+";";var res=co.match(rep);if(res){return res[1]||""}else{return""}};Utils.Cookie.setCookie=function(name,value,expire,path,domain,secure){var cstr=[];cstr.push(name+"="+escape(value));if(expire){var dd=new Date();var expires=dd.getTime()+expire*3600000;dd.setTime(expires);cstr.push("expires="+dd.toGMTString())}if(path){cstr.push("path="+path)}if(domain){cstr.push("domain="+domain)}if(secure){cstr.push(secure)}document.cookie=cstr.join(";")};Utils.Cookie.deleteCookie=function(name){document.cookie=name+"=;"+"expires=Fri, 31 Dec 1999 23:59:59 GMT;"};$registJob("miniblog_share",function(){if(!/\((iPhone|iPad|iPod)/i.test(navigator.userAgent)){(function(w,d){var dw,dh,de=d.documentElement;dw=(de&&de.clientWidth)?de.clientWidth:d.bod.y.clientWidth;dh=(de&&de.clientWidth)?de.clientHeight:d.body.clientHeight;if(dw<615||dh<605){window.resizeTo(615,605)}})(window,document)}scope.ispic=scope.ispic==null||scope.ispic==1;var _allow,picLstPageSize=scope.$uid.length?5:3;var curPage=1;var layer=$E("errorLayer");var layerConn=$E("rl_conn");if(layer){layer.style.display="none";layer.style.position="absolute";layer.style.zIndex=1000}function showErrorMsg(msg){if($E("repeatTip")){$E("repeatTip").style.display="";$E("repeatTip").innerHTML=msg}else{alert(msg)}}function hideErrorMsg(){if($E("repeatTip")){$E("repeatTip").style.display="none"}}var p={submit:$E("btn_send"),content:$E("fw_content"),limitTip:$E("txt_count_msg"),btnForward:$E("btn_forward"),picLst:$E("pic_lst"),picLstUL:$E("pic_lst_ul"),sharePic:$E("share_pic"),btnNext:$E("btn_next"),btnPrev:$E("btn_prev"),btnCancel:$E("btn_cancel")};var allow=function(b){if(!b){p.submit.removeAttribute("href")}else{p.submit.setAttribute("href","javascript:;")}_allow=b};function showPicLst(page,pageSize){if(scope.picLst.length==0){return}var startIdx=(page-1)*pageSize;var buff=[];var val=p.sharePic.value;for(var i=0;i<pageSize;i++){var item=scope.picLst[startIdx+i];if(item==null){break}else{if(val.length){if(val==item){buff.push('<li class="on" rel="'+item+'"><a hidefocus="true" href="javascript:void(0);" onclick="return false;"><em><img src="'+item+'"  class="pic"/><img src="images/shareimg/transparent.gif" class="ico_slt" alt=""></em></a></li>')}else{buff.push('<li rel="'+item+'"><a hidefocus="true" href="javascript:void(0);" onclick="return false;"><em><img src="'+item+'"  class="pic"/><img src="images/shareimg/transparent.gif" class="ico_slt" alt=""></em></a></li>')}}else{buff.push('<li rel="'+item+'"><a hidefocus="true" href="javascript:void(0);" onclick="return false;"><em><img src="'+item+'"  class="pic"/><img src="images/shareimg/transparent.gif" class="ico_slt" alt=""></em></a></li>')}}}p.picLstUL.innerHTML=buff.join("");$E("btn_prev").style.display="";$E("btn_next").style.display="";var lastPage=scope.picLst.length%picLstPageSize?Math.floor(scope.picLst.length/picLstPageSize)+1:Math.floor(scope.picLst.length/picLstPageSize);if(page==1){$E("btn_prev").style.display="none"}if(lastPage==page){$E("btn_next").style.display="none"}}function chkMiniBlogAndSend(){Utils.Io.Ajax.request("/share/aj_isopent.php",{onComplete:function(json){switch(json.code){case 0:startSend();break;case -1:setTimeout(function(){location.href="/widget/full_info.php?uid="+scope.$uid+"&type=4&r="+encodeURIComponent(scope.rr_url)},800);break;case -2:break}},onException:function(){},timeout:30000,"returnType":"json"})}var flashTime=0;function flashContent(){if(flashTime==7){flashTime=0;return}p.content.style.borderColor=flashTime%2?"red":"";flashTime++;setTimeout(flashContent,300)}function startSend(){var content=Core.String.trim(p.content.value);var waitIcon;if(!$E("waitIcon")){waitIcon=$C("div");waitIcon.innerHTML='<img src="images/shareimg/loading.gif"/>';waitIcon.style.visibility="hidden";waitIcon.style.position="absolute";waitIcon.style.zIndex=800;waitIcon.id="waitIcon";document.body.appendChild(waitIcon)}waitIcon=$E("waitIcon");var rePos=(function(wi){return function(aPos){wi.style.left=(aPos[2]-wi.offsetWidth)/2+"px";wi.style.top=(aPos[3]-wi.offsetHeight)/2+"px"}})(waitIcon);var contentMask=App.iframeMask(799,rePos);var obj=new Utils.Url(window.location.href);if(content){allow(false);waitIcon.style.visibility="visible";contentMask.show();var pic_val="";if(scope.ispic&&$E("pic_lst").style.display==""){var lis=$E("pic_lst_ul").getElementsByTagName("LI");for(var i=0;i<lis.length;i++){if(lis[i].className=="on"){pic_val=lis[i].getAttribute("rel");break}}if(pic_val.length==0){pic_val=p.sharePic.value}}var params={"content":content,"styleid":1,"from":scope["$pageid"],"share_pic":pic_val,"sourceUrl":scope.source?decodeURIComponent(obj.query["sourceUrl"]):"","source":scope.source?scope.source:$CLTMSG["CD0022"]};if(scope.appkey.length){params.appkey=scope.appkey}if(obj&&obj.query&&obj.query.appkey){params["appkey"]=obj.query.appkey}Utils.Io.Ajax.request("/mblog/aj_share.php",{"POST":params,"onComplete":function(json){if(json.code=="A00006"){setTimeout(function(){window.location.href="/share/success.php?urlpara="+encodeURIComponent(document.URL)},800)}else{if(json.code=="M01155"){waitIcon.style.visibility="hidden";contentMask.hidden();showErrorMsg("不要太贪心哦，发一次就够啦。")}else{if(json.code=="M00006"){waitIcon.style.visibility="hidden";contentMask.hidden();showErrorMsg("请不要发表违法和不良信息。")}else{setTimeout(function(){window.location.href="/share/fail.php?urlpara="+encodeURIComponent(document.URL)},800)}}}allow(true)},"onException":function(){},"returnType":"json"})}}function doSubmit(){var content=Core.String.trim(p.content.value);if(Core.String.byteLength(content)>280){flashContent();return}if(content.length==0){showErrorMsg("您提交的内容不能为空。");return}if(!_allow){return}if(scope.$uid.length==0){var uname=Core.String.trim($E("uname").value);var upass=Core.String.trim($E("upass").value);var autologin=$E("autologin").checked?"7":"0";if(uname.length==0){showMsg('<p class="bigtxt">请输入登录名</p>');return}if(upass.length==0){showMsg('<p class="bigtxt">请输入密码</p>');return}var login=window.sinaSSOController;login.customLoginCallBack=function(res){if(res.result){scope.$uid=res.userinfo.uniqueid;login.customLoginCallBack=function(){};Utils.Cookie.setCookie("un",uname,240,"/","v.t.sina.com.cn");chkMiniBlogAndSend()}else{$E("upass").value="";showMsg('<p class="bigtxt">登录名或密码错误</p>					            <p class="stxt2">1. 如果登录名是电子邮箱地址，</p>					  			<p class="stxt3">请输入全称，例如<span class="f11">demo@sina.com.cn</span></p> 								<p class="stxt2">2. 请检查登录名大小写是否正确。</p>								<p class="stxt2">3. 请检查密码大小写是否正确。</p>');return}};login.login(uname,upass,autologin)}else{startSend()}return false}if(p.submit&&p.content){allow(false);Core.Events.addEvent(p.submit,doSubmit,"click");if(scope.ispic){Core.Events.addEvent(p.btnForward,function(){p.picLst.style.display="";var lis=p.picLstUL.getElementsByTagName("LI");for(var i=0;i<lis.length;i++){if(lis[i].className=="on"){p.sharePic.value=lis[i].getAttribute("rel")}}p.btnForward.innerHTML="请选择一张图片转发";p.btnForward.className="noLink";if($E("layer_tip")){$E("layer_tip").style.display="none"}},"click");Core.Events.addEvent(p.btnCancel,function(){p.picLst.style.display="none";p.sharePic.value="";p.btnForward.innerHTML="转发图片";p.btnForward.className=""},"click");Core.Events.addEvent(p.picLstUL,function(){var target=Core.Events.getEventTarget();var i=0;switch(target.tagName){case"LI":target=target.childNodes[0];case"A":target=target.childNodes[0];case"EM":target=target.childNodes[0];case"IMG":break;default:return}var lis=p.picLstUL.getElementsByTagName("LI");if(target.parentNode.parentNode.parentNode.className=="on"){}else{for(i=0;i<lis.length;i++){lis[i].className=""}target.parentNode.parentNode.parentNode.className="on";p.sharePic.value=target.parentNode.parentNode.parentNode.getAttribute("rel")}},"click");Core.Events.addEvent(p.btnPrev,function(){if(scope.picLst.length==0||curPage==1){return}curPage=curPage-1;showPicLst(curPage,picLstPageSize)},"click");Core.Events.addEvent(p.btnNext,function(){if(scope.picLst.length==0){return}var lastPage=scope.picLst.length%picLstPageSize?Math.floor(scope.picLst.length/picLstPageSize)+1:Math.floor(scope.picLst.length/picLstPageSize);if(curPage==lastPage){return}curPage=curPage+1;showPicLst(curPage,picLstPageSize)},"click")}if($E("uname")){Core.Events.addEvent($E("uname"),function(){var evt=Core.Events.getEvent();if(evt.keyCode==13){l;$E("upass").focus()}},"keypress");Core.Events.addEvent($E("upass"),function(){var evt=Core.Events.getEvent();if(evt.keyCode==13){doSubmit()}},"keypress")}var listener=(function(c,b,t){return function(){var sLength=280;var value=Core.String.trim(c.value);var snapLength=Core.String.byteLength(value);if(snapLength>sLength||snapLength==0){var txt="";if(snapLength>0){txt="已超出<em>#{len}</em>字".replace(/#\{len\}/,Math.ceil((snapLength-sLength)/2));t.className="red"}else{txt="还可以输入<em>#{len}</em>字".replace(/#\{len\}/,sLength/2);t.className=""}t.innerHTML=txt;allow(false)}else{t.innerHTML="还可以输入<em>#{len}</em>字".replace(/#\{len\}/,Math.ceil((sLength-snapLength)/2));t.className="";allow(true)}}})(p.content,p.submit,p.limitTip);Core.Events.addEvent(p.content,listener,"focus");Core.Events.addEvent(p.content,listener,"blur");Core.Events.addEvent(p.content,listener,"keyup");Core.Events.addEvent(p.content,function(event){if((event.ctrlKey==true&&event.keyCode=="13")||(event.altKey==true&&event.keyCode=="83")){p.submit.blur();Core.Events.fireEvent(p.submit,"click")}},"keyup");p.content.focus();if($IE){var oSelector=p.content.createTextRange();oSelector.moveStart("character",p.content.value.length);oSelector.select()}if(scope.ispic){$E("pic_count").innerHTML="共"+(scope.picLst.length?scope.picLst.length:0)+"张";if(scope.picLst.length){showPicLst(1,picLstPageSize);var lis=p.picLstUL.getElementsByTagName("LI");for(var i=0;i<lis.length;i++){lis[i].className=""}var li=lis[0];li.className="on";p.sharePic.value=li.getAttribute("rel");$E("nopic").style.display="none"}else{$E("pic_lst_ul").style.display="none";$E("nopic").style.display="";$E("btn_prev").style.display="none";$E("btn_next").style.display="none"}if($E("pic_lst").style.display=="none"){p.btnForward.innerHTML="转发图片";p.btnForward.className="";if($E("layer_tip")){$E("layer_tip").style.display=""}}else{p.btnForward.innerHTML="请选择一张图片转发";p.btnForward.className="noLink";if($E("layer_tip")){$E("layer_tip").style.display="none"}}}if($E("layer_tip_close")){Core.Events.addEvent($E("layer_tip_close"),function(){$E("layer_tip").style.display="none"},"click")}}if($E("btn_closeLayer")){Core.Events.addEvent($E("btn_closeLayer"),function(){layer.style.display="none"},"click")}function showMsg(msg){layerConn.innerHTML=msg;layer.style.display="";layer.style.left=Core.Dom.getLeft($E("uname"))+"px";layer.style.top=(Core.Dom.getTop($E("uname"))-layer.offsetHeight+6)+"px"}if($E("uname")){$E("uname").value=unescape(Utils.Cookie.getCookie("un"));$E("upass").style.display="none";$E("password_text").style.display="";$E("password_text").value="请输入密码";Core.Events.addEvent($E("password_text"),function(){$E("password_text").style.display="none";$E("upass").style.display="";$E("upass").focus()},"focus");Core.Events.addEvent($E("upass"),function(){var val=Core.String.trim($E("upass").value);if(val.length==0){$E("password_text").style.display="";$E("upass").style.display="none";$E("upass").value=""}},"blur");Core.Events.addEvent($E("uname"),function(){$E("uname").style.color="#333"},"focus")}});$registJob("start_suda",function(){try{GB_SUDA._S_pSt()}catch(e){}});var SSL={Config:{},Space:function(str){var a=str,o=null;a=a.split(".");o=SSL;for(i=0,len=a.length;i<len;i++){o[a[i]]=o[a[i]]||{};o=o[a[i]]}return o}};SSL.Space("Global");SSL.Space("Core.Dom");SSL.Space("Core.Event");SSL.Space("App");SSL.Global={win:window||{},doc:document,nav:navigator,loc:location};SSL.Core.Dom={get:function(id){return document.getElementById(id)}};SSL.Core.Event={on:function(){}};SSL.App={_S_gConType:function(){var ct="";try{SSL.Global.doc.body.addBehavior("#default#clientCaps");ct=SSL.Global.doc.body.connectionType}catch(e){ct="unkown"}return ct},_S_gKeyV:function(src,k,e,sp){if(src==""){return""}if(sp==""){sp="="}k=k+sp;var ps=src.indexOf(k);if(ps<0){return""}ps=ps+k.length;var pe=src.indexOf(e,ps);if(pe<ps){pe=src.length}return src.substring(ps,pe)},_S_gUCk:function(ckName){if((undefined==ckName)||(""==ckName)){return""}return SSL.App._S_gKeyV(SSL.Global.doc.cookie,ckName,";","")},_S_sUCk:function(ckName,ckValue,ckDays,ckDomain){if(ckValue!=null){if((undefined==ckDomain)||(null==ckDomain)){ckDomain="sina.com.cn"}if((undefined==ckDays)||(null==ckDays)||(""==ckDays)){SSL.Global.doc.cookie=ckName+"="+ckValue+";domain="+ckDomain+";path=/"}else{var now=new Date();var time=now.getTime();time=time+86400000*ckDays;now.setTime(time);time=now.getTime();SSL.Global.doc.cookie=ckName+"="+ckValue+";domain="+ckDomain+";expires="+now.toUTCString()+";path=/"}}},_S_gJVer:function(_S_NAV_,_S_NAN_){var p,appsign,appver,jsver=1,isN6=0;if("MSIE"==_S_NAN_){appsign="MSIE";p=_S_NAV_.indexOf(appsign);if(p>=0){appver=parseInt(_S_NAV_.substring(p+5));if(3<=appver){jsver=1.1;if(4<=appver){jsver=1.3}}}}else{if(("Netscape"==_S_NAN_)||("Opera"==_S_NAN_)||("Mozilla"==_S_NAN_)){jsver=1.3;appsign="Netscape6";p=_S_NAV_.indexOf(appsign);if(p>=0){jsver=1.5}}}return jsver},_S_gFVer:function(nav){var ua=SSL.Global.nav.userAgent.toLowerCase();var flash_version=0;if(SSL.Global.nav.plugins&&SSL.Global.nav.plugins.length){var p=SSL.Global.nav.plugins["Shockwave Flash"];if(typeof p=="object"){for(var i=10;i>=3;i--){if(p.description&&p.description.indexOf(" "+i+".")!=-1){flash_version=i;break}}}}else{if(ua.indexOf("msie")!=-1&&ua.indexOf("win")!=-1&&parseInt(SSL.Global.nav.appVersion)>=4&&ua.indexOf("16bit")==-1){for(var i=10;i>=2;i--){try{var object=eval("new ActiveXObject('ShockwaveFlash.ShockwaveFlash."+i+"');");if(object){flash_version=i;break}}catch(e){}}}else{if(ua.indexOf("webtv/2.5")!=-1){flash_version=3}else{if(ua.indexOf("webtv")!=-1){flash_version=2}}}}return flash_version},_S_gMeta:function(MName,pidx){var pMeta=SSL.Global.doc.getElementsByName(MName);var idx=0;if(pidx>0){idx=pidx}return(pMeta.length>idx)?pMeta[idx].content:""},_S_gHost:function(sUrl){var r=new RegExp("^http(?:s)?://([^/]+)","im");if(sUrl.match(r)){return sUrl.match(r)[1].toString()}else{return""}},_S_gDomain:function(sHost){var p=sHost.indexOf(".sina.");if(p>0){return sHost.substr(0,p)}else{return sHost}},_S_gTJMTMeta:function(){return SSL.App._S_gMeta("mediaid")},_S_gTJZTMeta:function(){var zt=SSL.App._S_gMeta("subjectid");zt.replace(",",".");zt.replace(";",",");return zt},_S_isFreshMeta:function(){var ph=SSL.Global.doc.documentElement.innerHTML.substring(0,1024);var reg=new RegExp("<meta\\s*http-equiv\\s*=((\\s*refresh\\s*)|('refresh')|(\"refresh\"))s*contents*=","ig");return reg.test(ph)},_S_isIFrameSelf:function(minH,minW){if(SSL.Global.win.top==SSL.Global.win){return false}else{try{if(SSL.Global.doc.body.clientHeight==0){return false}if((SSL.Global.doc.body.clientHeight>=minH)&&(SSL.Global.doc.body.clientWidth>=minW)){return false}else{return true}}catch(e){return true}}},_S_isHome:function(curl){var isH="";try{SSL.Global.doc.body.addBehavior("#default#homePage");isH=SSL.Global.doc.body.isHomePage(curl)?"Y":"N"}catch(e){isH="unkown"}return isH}};function SUDA(config,ext1,ext2){var SG=SSL.Global,SSD=SSL.Core.Dom,SSE=SSL.Core.Event,SA=SSL.App;var _S_JV_="webbug_meta_ref_mod_noiframe_async_fc_:9.10c",_S_DPID_="-9999-0-0-1";var _S_NAN_=SG.nav.appName.indexOf("Microsoft Internet Explorer")>-1?"MSIE":SG.nav.appName;var _S_NAV_=SG.nav.appVersion;var _S_PURL_=SG.loc.href.toLowerCase();var _S_PREF_=SG.doc.referrer.toLowerCase();var _SP_MPID_="";var _S_PID_="",_S_UNA_="SUP",_S_MI_="",_S_SID_="Apache",_S_GID_="SINAGLOBAL",_S_LV_="ULV",_S_UO_="UOR",_S_UPA_="_s_upa",_S_IFW=320,_S_IFH=240,_S_GIDT=0,_S_EXT1="",_S_EXT2="",_S_SMC=0,_S_SMM=10000,_S_ET=0,_S_ACC_="_s_acc";var _S_HTTP=_S_PURL_.indexOf("https")>-1?"https://":"http://",_S_BCNDOMAIN="beacon.sina.com.cn",_S_CP_RF=_S_HTTP+_S_BCNDOMAIN+"/a.gif",_S_CP_RF_D=_S_HTTP+_S_BCNDOMAIN+"/d.gif",_S_CP_RF_E=_S_HTTP+_S_BCNDOMAIN+"/e.gif",_S_CP_FC=_S_HTTP+_S_BCNDOMAIN+"/fc.html";var _S_T1=100,_S_T2=500;var _S_TEntry="_s_tentry";var handler={_S_sSID:function(){handler._S_p2Bcn("",_S_CP_RF_D)},_S_gsSID:function(){var sid=SA._S_gUCk(_S_SID_);if(""==sid){handler._S_sSID()}return sid},_S_sGID:function(gid){if(""!=gid){SA._S_sUCk(_S_GID_,gid,3650)}},_S_gGID:function(){return SA._S_gUCk(_S_GID_)},_S_gsGID:function(){if(""!=_S_GID_){var gid=SA._S_gUCk(_S_GID_);if(""==gid){handler._S_IFC2GID()}return gid}else{return""}},_S_IFC2GID:function(){var _S_ifc=SSD.get("SUDA_FC");if(_S_ifc){_S_ifc.src=_S_CP_FC+"?a=g&n="+_S_GID_+"&r="+Math.random()}},_S_gCid:function(){try{var metaTxt=SA._S_gMeta("publishid");if(""!=metaTxt){var pbidList=metaTxt.split(",");if(pbidList.length>0){if(pbidList.length>=3){_S_DPID_="-9999-0-"+pbidList[1]+"-"+pbidList[2]}return pbidList[0]}}else{return"0"}}catch(e){return"0"}},_S_gAEC:function(){return SA._S_gUCk(_S_ACC_)},_S_sAEC:function(eid){if(""==eid){return}var acc=handler._S_gAEC();if(acc.indexOf(eid+",")<0){acc=acc+eid+","}SA._S_sUCk(_S_ACC_,acc,7)},_S_p2Bcn:function(q,u){var scd=SSD.get("SUDA_CS_DIV");if(null!=scd){var now=new Date();scd.innerHTML="<img width=0 height=0 src='"+u+"?"+q+"&gUid_"+now.getTime()+"' border='0' alt='' />"}},_S_gSUP:function(){if(_S_MI_!=""){return _S_MI_}var sup=unescape(SA._S_gUCk(_S_UNA_));if(sup!=""){var ag=SA._S_gKeyV(sup,"ag","&","");var user=SA._S_gKeyV(sup,"user","&","");var uid=SA._S_gKeyV(sup,"uid","&","");var sex=SA._S_gKeyV(sup,"sex","&","");var bday=SA._S_gKeyV(sup,"dob","&","");_S_MI_=ag+":"+user+":"+uid+":"+sex+":"+bday;return _S_MI_}else{return""}},_S_gsLVisit:function(sid){var lvi=SA._S_gUCk(_S_LV_);var lva=lvi.split(":");var lvr="";if(lva.length>=6){if(sid!=lva[4]){var lvn=new Date();var lvd=new Date(parseInt(lva[0]));lva[1]=parseInt(lva[1])+1;if(lvn.getMonth()!=lvd.getMonth()){lva[2]=1}else{lva[2]=parseInt(lva[2])+1}if(((lvn.getTime()-lvd.getTime())/86400000)>=7){lva[3]=1}else{if(lvn.getDay()<lvd.getDay()){lva[3]=1}else{lva[3]=parseInt(lva[3])+1}}lvr=lva[0]+":"+lva[1]+":"+lva[2]+":"+lva[3];lva[5]=lva[0];lva[0]=lvn.getTime();SA._S_sUCk(_S_LV_,lva[0]+":"+lva[1]+":"+lva[2]+":"+lva[3]+":"+sid+":"+lva[5],360)}else{lvr=lva[5]+":"+lva[1]+":"+lva[2]+":"+lva[3]}}else{var lvn=new Date();lvr=":1:1:1";SA._S_sUCk(_S_LV_,lvn.getTime()+lvr+":"+sid+":",360)}return lvr},_S_gUOR:function(){var uoc=SA._S_gUCk(_S_UO_);var upa=uoc.split(":");if(upa.length>=2){return upa[0]}else{return""}},_S_sUOR:function(){var uoc=SA._S_gUCk(_S_UO_),uor="",uol="",up_t="",up="";var re=/[&|?]c=spr(_[A-Za-z0-9]{1,}){3,}/;var ct=new Date();if(_S_PURL_.match(re)){up_t=_S_PURL_.match(re)[0]}else{if(_S_PREF_.match(re)){up_t=_S_PREF_.match(re)[0]}}if(up_t!=""){up_t=up_t.substr(3)+":"+ct.getTime()}if(uoc==""){if(SA._S_gUCk(_S_LV_)==""&&SA._S_gUCk(_S_LV_)==""){uor=SA._S_gDomain(SA._S_gHost(_S_PREF_));uol=SA._S_gDomain(SA._S_gHost(_S_PURL_))}SA._S_sUCk(_S_UO_,uor+","+uol+","+up_t,365)}else{var ucg=0,uoa=uoc.split(",");if(uoa.length>=1){uor=uoa[0]}if(uoa.length>=2){uol=uoa[1]}if(uoa.length>=3){up=uoa[2]}if(up_t!=""){ucg=1}else{var upa=up.split(":");if(upa.length>=2){var upd=new Date(parseInt(upa[1]));if(upd.getTime()<(ct.getTime()-86400000*30)){ucg=1}}}if(ucg){SA._S_sUCk(_S_UO_,uor+","+uol+","+up_t,365)}}},_S_gRef:function(){var re=/^[^\?&#]*.swf([\?#])?/;if((_S_PREF_=="")||(_S_PREF_.match(re))){var ref=SA._S_gKeyV(_S_PURL_,"ref","&","");if(ref!=""){return ref}}return _S_PREF_},_S_MEvent:function(){if(_S_SMC==0){_S_SMC++;var c=SA._S_gUCk(_S_UPA_);if(c==""){c=0}c++;if(c<_S_SMM){var re=/[&|?]c=spr(_[A-Za-z0-9]{2,}){3,}/;if(_S_PURL_.match(re)||_S_PREF_.match(re)){c=c+_S_SMM}}SA._S_sUCk(_S_UPA_,c)}},_S_gMET:function(){var c=SA._S_gUCk(_S_UPA_);if(c==""){c=0}return c},_S_gCInfo_v2:function(){var now=new Date();return"sz:"+screen.width+"x"+screen.height+"|dp:"+screen.colorDepth+"|ac:"+SG.nav.appCodeName+"|an:"+_S_NAN_+"|cpu:"+SG.nav.cpuClass+"|pf:"+SG.nav.platform+"|jv:"+SA._S_gJVer(_S_NAV_,_S_NAN_)+"|ct:"+SA._S_gConType()+"|lg:"+SG.nav.systemLanguage+"|tz:"+now.getTimezoneOffset()/60+"|fv:"+SA._S_gFVer(SG.nav)},_S_gPInfo_v2:function(pid,ref){if((undefined==pid)||(""==pid)){pid=handler._S_gCid()+_S_DPID_}return"pid:"+pid+"|st:"+handler._S_gMET()+"|et:"+_S_ET+"|ref:"+escape(ref)+"|hp:"+SA._S_isHome(_S_PURL_)+"|PGLS:"+SA._S_gMeta("stencil")+"|ZT:"+escape(SA._S_gTJZTMeta())+"|MT:"+escape(SA._S_gTJMTMeta())+"|keys:"},_S_gUInfo_v2:function(vid){return"vid:"+vid+"|sid:"+handler._S_gsSID()+"|lv:"+handler._S_gsLVisit(handler._S_gsSID())+"|un:"+handler._S_gSUP()+"|uo:"+handler._S_gUOR()+"|ae:"+handler._S_gAEC()},_S_gEXTInfo_v2:function(ext1,ext2){_S_EXT1=(undefined==ext1)?_S_EXT1:ext1;_S_EXT2=(undefined==ext2)?_S_EXT2:ext2;return"ex1:"+_S_EXT1+"|ex2:"+_S_EXT2},_S_pBeacon:function(pid,ext1,ext2){try{var gid=handler._S_gsGID();if(""==gid){if(_S_GIDT<1){setTimeout(function(){handler._S_pBeacon(pid,ext1,ext2)},_S_T2);_S_GIDT++;return}else{gid=handler._S_gsSID();handler._S_sGID(gid)}}var sVer="V=2";var sCI=handler._S_gCInfo_v2();var sPI=handler._S_gPInfo_v2(pid,handler._S_gRef());var sUI=handler._S_gUInfo_v2(gid);var sEX=handler._S_gEXTInfo_v2(ext1,ext2);var lbStr=sVer+"&CI="+sCI+"&PI="+sPI+"&UI="+sUI+"&EX="+sEX;handler._S_p2Bcn(lbStr,_S_CP_RF)}catch(e){}},_S_acTrack_i:function(eid,p){if((""==eid)||(undefined==eid)){return}handler._S_sAEC(eid);if(0==p){return}var s="AcTrack||"+handler._S_gGID()+"||"+handler._S_gsSID()+"||"+handler._S_gSUP()+"||"+eid+"||";handler._S_p2Bcn(s,_S_CP_RF_E)},_S_uaTrack_i:function(acode,aext){var s="UATrack||"+handler._S_gGID()+"||"+handler._S_gsSID()+"||"+handler._S_gSUP()+"||"+acode+"||"+aext+"||";handler._S_p2Bcn(s,_S_CP_RF_E)},_S_sTEntry:function(){var e="-";if(""==SA._S_gUCk(_S_TEntry)){if(""!=_S_PREF_){e=SA._S_gHost(_S_PREF_)}SA._S_sUCk(_S_TEntry,e,"","t.sina.com.cn")}var vlogin=/t.sina.com.cn\/reg.php/;if(_S_PURL_.match(vlogin)){var sharehost=SA._S_gKeyV(unescape(_S_PURL_),"sharehost","&","");var appkey=SA._S_gKeyV(unescape(_S_PURL_),"appkey","&","");if(""!=sharehost){SA._S_sUCk(_S_TEntry,sharehost,"","t.sina.com.cn")}SA._S_sUCk("appkey",appkey,"","t.sina.com.cn")}},_S_gSPR:function(){var uoc=handler._S_gUOR();var upa=uoc.split(",");if(upa.length>=3){return upa[2]}else{return""}},_S_upExt1:function(){var reg_arr=new Array(/t.sina.com.cn\/reg.php/,/t.sina.com.cn\/reg\/reg_succ.php/,/t.sina.com.cn\/reg\/reg_active.php/,/t.sina.com.cn\/person\/full_info.php\?.*type=3.*/,/t.sina.com.cn\/person\/guide_interest.php\?.*type=3.*/,/t.sina.com.cn\/person\/guide_invite.php\?.*type=3.*/,/t.sina.com.cn\/person\/full_info.php\?.*type=2.*/,/t.sina.com.cn\/person\/guide_interest.php\?.*type=2.*/,/t.sina.com.cn\/person\/guide_invite.php\?.*type=2.*/,/t.sina.com.cn\/reg_sinamail.php/,/t.sina.com.cn\/person\/full_info.php\?.*type=1.*/,/t.sina.com.cn\/person\/guide_interest.php\?.*type=1.*/,/t.sina.com.cn\/person\/guide_invite.php\?.*type=1.*/,/v.t.sina.com.cn\/widget\/full_info.php\?.*type=4.*/,/v.t.sina.com.cn\/share\/share.php\?.*type=4.*/);var pos_arr=new Array("reg_input","reg_succ","reg_active","reg_full_info","reg_interest","reg_invite","act_fullinfo","act_interest","act_invite","mail_act","mail_full_info","mail_interest","mail_invite","wgt_full_info","wgt_succ");var pos="";var ral=reg_arr.length;var rpl=pos_arr.length;var spr=handler._S_gSPR();try{for(var i=0;i<ral&&i<rpl;i++){if(_S_PURL_.match(reg_arr[i])){pos=spr+",flw,"+pos_arr[i];break}}}catch(e){}return pos}};if(_S_SMC==0){if("MSIE"==_S_NAN_){SSL.Global.doc.attachEvent("onclick",handler._S_MEvent);SSL.Global.doc.attachEvent("onmousemove",handler._S_MEvent);SSL.Global.doc.attachEvent("onscroll",handler._S_MEvent)}else{SSL.Global.doc.addEventListener("click",handler._S_MEvent,false);SSL.Global.doc.addEventListener("mousemove",handler._S_MEvent,false);SSL.Global.doc.addEventListener("scroll",handler._S_MEvent,false)}}handler._S_sUOR();handler._S_sTEntry();return{_S_pSt:function(pid,ext1,ext2){try{if((SA._S_isFreshMeta())||(SA._S_isIFrameSelf(_S_IFH,_S_IFW))){return}if(_S_ET>0){return}++_S_ET;setTimeout(function(){handler._S_gsSID()},_S_T1);setTimeout(function(){handler._S_pBeacon(pid,((undefined==ext1)?handler._S_upExt1():ext1),ext2,0)},_S_T2)}catch(e){}},_S_pStM:function(pid,ext1,ext2){++_S_ET;handler._S_pBeacon(pid,((undefined==ext1)?handler._S_upExt1():ext1),ext2)},_S_acTrack:function(eid,p){try{if((undefined!=eid)&&(""!=eid)){setTimeout(function(){handler._S_acTrack_i(eid,p)},_S_T1)}}catch(e){}},_S_uaTrack:function(acode,aext){try{if(undefined==acode){acode=""}if(undefined==aext){aext=""}if((""!=acode)||(""!=aext)){setTimeout(function(){handler._S_uaTrack_i(acode,aext)},_S_T1)}}catch(e){}}}}var GB_SUDA;if(GB_SUDA==null){GB_SUDA=new SUDA({})}var _S_PID_="";function _S_pSt(pid,ext1,ext2){GB_SUDA._S_pSt(pid,ext1,ext2)}function _S_pStM(pid,ext1,ext2){GB_SUDA._S_pStM(pid,ext1,ext2)}function _S_acTrack(eid){GB_SUDA._S_acTrack(eid,1)}function _S_uaTrack(acode,aext){GB_SUDA._S_uaTrack(acode,aext)}function main(){var jobs=new Jobs();jobs.add("miniblog_share");jobs.add("start_suda");jobs.start()};