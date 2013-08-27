//  Version 1.2
//  2005/05/03
resetModulNaviTimer = 5000;
hideCo2LayerTimer   = 5000;
var i, e;
var browserAtributeLength, browserId, platform;
var imgCountTotal, lowImageSrc, highImageSrc, currentImg, currentState, currentAct, currentPerm, checkLoad;
var slideAmount;
var divNum, documentLeftScroll, documentTopScroll, mouseX, mouseY, loopDragging;
var speedHorizontal, speedVertical, currentBack, goup, godown, speed, currentObjNo;
var setDivPosition, setBackPosition, currentObjId, currentSpeed, currenDirection, currentDelay;
var diffWidth,diffHeight, lastWidth, lastHeight, currentDiv;
var popup_window, winUrl;
var ua                  = navigator.userAgent.toLowerCase();
var an                  = navigator.appName.toLowerCase();
var currentStep         = 0;
var windowWidth         = 0;
var windowHeight        = 0;
var browserVersion      = 0;
var loaded              = 0;
var divLeft             = 0;
var divTop              = 0;
var looping             = -1;
var slideCount          = -1;
var writeBrowser        = "";
var tempAct             = "";
var slideNumber         = "";
var slideDescription    = "";
var preLoadArray        = new Array();
var preLoadCounter      = new Array();
var highImages          = new Array();
var lowImages           = new Array();
var slideText           = new Array();
var permanentActive     = new Array();
var slideImages         = new Array();
var allowedDomain       = new Array();
    allowedDomain[0]    = "://www.bmw.";
    allowedDomain[1]    = "://bmw.";
    allowedDomain[2]    = "://origin.bmw.";
    allowedDomain[3]    = "://secure.bmw.";
    allowedDomain[4]    = "://wcms10.bmwgroup.com";
    allowedDomain[5]    = "://liintra.muc";
    allowedDomain[6]    = "://bmw-opencms.";
    allowedDomain[7]    = "://bmw-opencms-dev.";
    allowedDomain[8]    = "://bmw-cn-edit.";
    allowedDomain[9]    = "://bmw-cn-live.";
    allowedDomain[10]    = "://www2.bmw.";
var supportedOS         = false;
var supportedVersion    = false;
var currentLoop         = false;
var dragAllowed         = false;
var divIsMoving         = false;
var flashversion        = false;
var topFrame            = null;
var contentFrame        = null;
var bottomFrame         = null;
var historyFrame        = null;
var hiddenFrame         = null;
var allowClose          = true;
var modulNaviOverImage  = true;
var idmodulsSpecial     = "";
var minFlashVersion     = 7;
var query = new Object();
var parameterArray = new Array();
var scrollerDefaultSize=978;
var scrollerCheckElements = new Array();
var scrollerSize = scrollerDefaultSize;
var scrollerBgImage;
var scrollerSliderImage;
var scrollerImageUp;
var scrollerImageDown;
if (typeof browser != 'object') {
  browser     = new Array();
  browser[0]  = new Array('Opera',    'opera ',     '6.5', '',        '',             '');
  browser[1]  = new Array('Safari',   'safari/',    '125', '',        'mac os x',     '');
  browser[2]  = new Array('Netscape', 'netscape/',  '7.1', 'windows', 'mac os x',     'other');
  browser[3]  = new Array('Firefox',  'firefox/',   '1.0', 'windows', 'mac os x',     'other');
  browser[4]  = new Array('Mozilla',  'rv:',        '1.7', 'windows', 'mac os x',     'other');
  browser[5]  = new Array('MSIE',     'msie ',      '5.5', 'windows', '',             '');
  browser[6]  = new Array('Netscape4','mozilla/',   '4.0',  '',       '',             '');
}
function checkClient() {
  var browserLength = browser.length;
  for (i = 0; i < browserLength; i++) {
    browserAtributeLength = browser[i].length;
    if (ua.indexOf(browser[i][1]) != -1) {
      browserId = browser[i][0];
      for (e = 3; e < browserAtributeLength; e++) {
        if (browser[i][e] != '' && (ua.indexOf(browser[i][e]) != -1 || browser[i][e] == 'other')) {
            supportedOS = true;
            platform = browser[i][e];
            break;
        } else {
          supportedOS = false;
        }
      }
      browserVersion = ua.split(browser[i][1]);
      browserVersion = parseFloat(browserVersion[1].slice(0,3));
      if (browserVersion >= browser[i][2]) {
        supportedVersion = true;
      } else {
        supportedVersion = false;
      }
      break;
    } else {
      browserId = 'unknown';
    }
  }
}
function checkBrowser(incompatibleBrowserUrl) {
  if(document.cookie && document.cookie.indexOf('bmwDisableBrowserCheck=true') != -1) {
    return true;
  }
  checkClient();
  if(supportedVersion == false || supportedOS == false){
     parent.location.href = buildValidServerRelativeUrl(incompatibleBrowserUrl) + "?" + self.location.href.replace("?","&");
    return false;
  } else {
    return true;
  }
}
function checkFrameset(){
  if(self.name!="frameContent"){
     if(typeof confCountryTopic!='undefined' && confCountryTopic!=null && typeof confLanguageTopic!='undefined' && confLanguageTopic!=null){
      var basePath            = self.location.href.substring(0, self.location.href.indexOf("/" + confCountryTopic + "/" + confLanguageTopic + "/"));
      var countryLanguagePath = basePath + "/" + confCountryTopic + "/" + confLanguageTopic + "/";
      self.location.href =  framesetPage + "?content=" + self.location.href.replace("?","&");
    }
  }
}
function pageHandler() {
  splitSearchString();
  if(query.content){
    if(query.content.indexOf("://") != -1) {
      var domainIsAllowed=false;
      for (i = 0; i < allowedDomain.length; i++) {
        if(query.content.indexOf(allowedDomain[i]) != -1) {
          domainIsAllowed = true;
          break;
        }
      }
      if(domainIsAllowed) {
        initContentURL = query.content;
        var parameters="";
        for(x in query){
          if(x=="content") continue;
          if(parameters=="") {
             parameters+="?";
          } else {
             parameters+="&";
          }
         parameters+=x+"="+query[x];
        }
	initContentURL +=parameters;
      }
    }
    else {
      initContentURL = query.content;
      var parameters="";
      for(x in query){
        if(x=="content") continue;
        if(parameters=="") {
           parameters+="?";
        } else {
           parameters+="&";
        }
       parameters+=x+"="+query[x];
      }
      initContentURL +=parameters;
    }
  }
}
function setFrameVariables(){
  contentFrame = frames[0];
  bottomFrame  = frames[1];
  historyFrame = frames[2];
  hiddenFrame  = frames[3];
}
function getFrameset(version){
  var frameSetSource   = "";
  pageHandler();
  if(version == "flash" || version == "html"){
    if(version == "html"){
      initHistoryURL = initHiddenURL;
    }
    frameSetSource += '<frameset rows="*,29" frameborder="no" border="0" onload="setFrameVariables()">';
    frameSetSource += '<frame src="' + initContentURL + '" name="frameContent" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="auto" />';
    frameSetSource += '<frameset cols="*,1,1" frameborder="no" border="0">';
    frameSetSource += '  <frame src="' + initBottomURL  + '" name="frameBottom"  frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="no" />';
    frameSetSource += '  <frame src="' + initHistoryURL + '" name="frameHistory" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="no" />';
    frameSetSource += '  <frame src="' + initHiddenURL  + '" name="hiddenFrame"  frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="no" />';
    frameSetSource += '</frameset>';
  } else if(version == "shortcut") {
    frameSetSource += '<frameset rows="99%,1%" frameborder="no" border="0">';
    frameSetSource += ' <frame src="' + initContentURL + '" name="frameContent" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="auto" />';
    frameSetSource += ' <frame src="' + initHiddenURL  + '" name="hiddenFrame"  frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="no" />';
  } else if(version == "popup") {
    frameSetSource += '<frameset rows="96,*" frameborder="no" border="0">';
    frameSetSource += ' <frame src="' + initTopURL  + '" name="frameTop"  frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="no" />';
    frameSetSource += ' <frame src="' + initContentURL + '" name="frameContent" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize" scrolling="auto" />';
  } else {
    frameSetSource = "Error: Function getFrameset called with illegal parameter (" + version + ")";
  }
  frameSetSource += '</frameset>';
  return frameSetSource;
}
function preload() {
  if (typeof slideImagesCollection != 'undefined') {
    slideAmount = slideImagesCollection.length;
  }
  for (i = 0; i < slideAmount; i++) {
    slideImages[i] = new Image();
    slideImages[i].src = slideImagesCollection[i];
  }
  loaded = 2;
  imgCountTotal = document.images.length;
  for (i = 0; i < imgCountTotal; i++) {
    if (typeof document.getElementsByTagName('img')[i].getAttribute('preload') == 'string') {
      lowImageSrc = document.getElementsByTagName('img')[i].src;
      if (document.getElementsByTagName('img')[i].getAttribute('preload').indexOf('/') != -1) {
        highImageSrc = document.getElementsByTagName('img')[i].getAttribute('preload');
      } else {
        var highImageUrl = lowImageSrc.split('/');
        var fileLevel = highImageUrl.length;
        var highImagePath = '';
        for (e = 0; e < fileLevel - 1; e++) {
          highImagePath += highImageUrl[e] + '/';
        }
        highImageSrc = highImagePath + document.getElementsByTagName('img')[i].getAttribute('preload');
      }
      highImages[document.images[i].id] = new Image();
      highImages[document.images[i].id].src = highImageSrc;
      lowImages[document.images[i].id] = new Image();
      lowImages[document.images[i].id].src = lowImageSrc;
    }
    if (i < imgCountTotal - 1) {
      loaded = 3;
    }
    if (i == imgCountTotal - 1) {
      loaded = 1;
    }
  }
}
function switchImage(imgId,state,act,permanent,dropPerm) {
  currentImg = imgId;
  currentState = state;
  currentAct= act;
  currentPerm = permanent;
  if (typeof dropPerm == 'string' && dropPerm != 'all') {
    document.getElementsByTagName('img')[dropPerm].src = lowImages[dropPerm].src;
    delete permanentActive[dropPerm];
    if (dropPerm == tempAct) {
      tempAct = '';
    }
  } else if (dropPerm == 'all') {
    dropPermanentAll ();
  }
  if (loaded == 1) {
    clearTimeout(checkLoad);
    if (tempAct != '' && imgId != tempAct && act == 1 && !permanentActive[tempAct]) {
      document.getElementsByTagName('img')[tempAct].src = lowImages[tempAct].src;
    }
    if ((tempAct == '' || imgId != tempAct) && !permanentActive[imgId]) {
      if (state == 1) {
        document.getElementsByTagName('img')[imgId].src = highImages[imgId].src;
      } else {
        document.getElementsByTagName('img')[imgId].src = lowImages[imgId].src;
      }
    }
    if (act == 1) {
      tempAct = imgId;
    }
    if (permanent == 1) {
      permanentActive[imgId] = imgId;
    }
  } else if (loaded == 2){
    checkLoad = setTimeout('switchImage(currentImg,currentState,currentAct,currentPerm)',50);
  } else if (loaded == 3){
    preload();
    checkLoad = setTimeout('switchImage(currentImg,currentState,currentAct,currentPerm)',50);
  } else if (loaded == 0){
    preload();
    checkLoad = setTimeout('switchImage(currentImg,currentState,currentAct,currentPerm)',50);
  }
}
function dropPermanentAll () {
  for (var dropImg in permanentActive) {
    document.getElementsByTagName('img')[dropImg].src = lowImages[dropImg].src;
    delete permanentActive[dropImg];
  }
  if (tempAct != '') {
    document.getElementsByTagName('img')[tempAct].src = lowImages[tempAct].src;
    tempAct = '';
  }
}
function setSlideshow(direction,delay) {
  currenDirection = direction;
  currentDelay = delay;
  if (direction == "forward") {
    slideCount ++;
    if (slideCount > slideAmount - 1) {
      slideCount = 0;
    }
  } else if (direction == "backward") {
    slideCount --;
    if (slideCount < 0) {
      slideCount = slideAmount - 1;
    }
  } else {
    slideCount = 0;
  }
  if (delay) {
    looping = setTimeout("setSlideshow(currenDirection,currentDelay)",currentDelay);
  } else {
    clearTimeout(looping);
    looping = -1;
  }
  document.getElementById('slideshow').src = slideImages[slideCount].src;
}
function toggleSlideshow(direction,delay) {
  if (!direction) {
    direction = currenDirection;
  }
  if (!delay) {
    delay = currentDelay;
  }
  if (looping > -1) {
    clearTimeout(looping);
    looping = -1;
  } else {
    setSlideshow(direction,delay);
  }
}
function setClassName(tagId,nameOfClass) {
  if (typeof tagId != 'object') {
    tagId = document.getElementById(tagId);
  }
  if (tagId) {
    tagId.className = nameOfClass;
  }
}
function setColor(objId,color) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    objId.style.color = color;
  }
}
function addClassName(tagType, tagIndex, additionalClassName){
  var oldClassName=(document.getElementsByTagName(tagType)[tagIndex].className)?document.getElementsByTagName(tagType)[tagIndex].className:'';
  document.getElementsByTagName(tagType)[tagIndex].className=oldClassName+' '+additionalClassName;
}
function getAbsoluteLeft(obj) {
  if (typeof obj != 'object') {
    obj = document.getElementById(obj);
  }
  var x = 0;
  if (obj){
    while (obj.offsetParent !== null) {
      x += obj.offsetLeft;
      obj = obj.offsetParent;
    }
    x += obj.offsetLeft;
  }
  return x;
}
function getAbsoluteTop(obj) {
  if (typeof obj != 'object') {
    obj = document.getElementById(obj);
  }
  var y = 0;
  if (obj){
    while (obj.offsetParent !== null) {
      y += obj.offsetTop;
      obj = obj.offsetParent;
    }
    y += obj.offsetTop;
  }
  return y;
}
function getDivInformation(objId,attribute) {
  divInformation = new Array();
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId){
    divInformation['offsetLeft']  = objId.offsetLeft;
    divInformation['offsetTop']   = objId.offsetTop;
    divInformation['styleLeft']   = parseInt(objId.style.left);
    divInformation['styleTop']    = parseInt(objId.style.top);
    divInformation['width']       = objId.offsetWidth;
    divInformation['height']      = objId.offsetHeight;
    divInformation['visibility']  = objId.style.visibility;
    divInformation['display']     = objId.style.display;
    divInformation['zIndex']      = objId.style.zIndex;
    return divInformation[attribute];
  }
}
function writeIntoLayer(objId,content) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId){
    objId.innerHTML = content;
  }
}
lastPositions     = new Array();
currentPositions  = new Array();
currentPositions['navigation'] = [,];
function moveObject(objId,left,top,speed,backLink) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    if (left) {
      divLeft = left;
    } else if ((typeof left == 'undefined' || typeof left == 'string') && (typeof backLink == 'undefined' || backLink == 0)) {
      divLeft = getDivInformation(objId,'offsetLeft');
    }
    if (top) {
      divTop = top;
    } else if ((typeof top == 'undefined' || typeof top == 'string') && (typeof backLink == 'undefined' || backLink == 0)) {
      divTop = getDivInformation(objId,'offsetTop');
    }
    if (!lastPositions[objId.id]) {
      lastPositions[objId.id] = [,];
    }
    if (typeof backLink != 'undefined' && backLink == 1 && left == lastPositions[objId.id][0] && top == lastPositions[objId.id][1]) {
      currentBack = backLink;
      divLeft = currentPositions[objId.id][0];
      divTop  = currentPositions[objId.id][1];
    }
    if (!divIsMoving) {
      currentPositions[objId.id] = [getDivInformation(objId,'offsetLeft'),getDivInformation(objId,'offsetTop')];
    }
    if (speed) {
      var horizontalRange = currentPositions[objId.id][0] - divLeft;
      var verticalRange   = currentPositions[objId.id][1] - divTop;
      currentObjId = objId;
      currentSpeed = speed;
      currentStep ++;
      if (left != '' || left == 0) {
        if (horizontalRange > 0) {
          objId.style.left = (currentPositions[objId.id][0] - Math.round(currentStep * speed)) + 'px';
          if (divLeft - getDivInformation(objId,'offsetLeft') > 5) {
            objId.style.left = divLeft + 'px';
          }
        } else if (horizontalRange < 0) {
          objId.style.left = (currentPositions[objId.id][0] + Math.round(currentStep * speed)) + 'px';
          if (divLeft - getDivInformation(objId,'offsetLeft') < 5) {
            objId.style.left = divLeft + 'px';
          }
        }
      }
      if (top != '' || top == 0) {
        if (verticalRange > 0) {
          objId.style.top = (currentPositions[objId.id][1] - Math.round(currentStep * speed)) + 'px';
          if (divTop - getDivInformation(objId,'offsetTop') > 5) {
            objId.style.top = divTop + 'px';
          }
        } else if (verticalRange < 0) {
          objId.style.top = (currentPositions[objId.id][1] + Math.round(currentStep * speed)) + 'px';
          if (divTop - getDivInformation(objId,'offsetTop') < 5) {
            objId.style.top = divTop + 'px';
          }
        }
      }
      if (getDivInformation(objId,'offsetLeft') == left && getDivInformation(objId,'offsetTop') == top) {
        divIsMoving = false;
        currentStep = 0;
        currentBack = 0;
        divLeft     = 0;
        divTop      = 0;
        lastPositions[objId.id]=[left,top];
        clearTimeout(setDivPosition);
      } else {
        divIsMoving = true;
        setDivPosition = setTimeout('moveObject(currentObjId,divLeft,divTop,currentSpeed)',10);
      }
    } else {
      if (divLeft != '' || divLeft == 0) {
        objId.style.left = divLeft + 'px';
      }
      if (divTop != '' || divTop == 0) {
        objId.style.top = divTop + 'px';
      }
      currentBack = 0;
      divLeft     = 0;
      divTop      = 0;
      lastPositions[objId.id]=[left,top];
    }
  }
}
function mousePosition(currentevent){
  if(window.event) {
    currentevent = window.event;
  }
  mouseX = currentevent.clientX;
  mouseY = currentevent.clientY;
}
function getWindowInformation(value) {
  windowInformation = new Array();
  windowInformation['winWidth'] = document.body.clientWidth;
  if (document.body.clientHeight == 0) {
    windowInformation['winHeight'] = window.innerHeight;
  } else {
    windowInformation['winHeight'] = document.body.clientHeight;
  }
  windowInformation['docWidth'] = document.body.scrollWidth;
  windowInformation['docHeight'] = document.body.scrollHeight;
  windowInformation['scrollLeft'] = document.body.scrollLeft;
  windowInformation['scrollTop'] = document.body.scrollTop;
  return windowInformation[value];
}
function getCurrentStyle(nodeObject,propertyName) {
  var propertyValue;
  if (document.documentElement && document.defaultView) {
    propertyValue = document.defaultView.getComputedStyle(nodeObject,"").getPropertyValue(propertyName);
  }
  else if (document.documentElement && document.documentElement.currentStyle) {
    var regX = /([a-z]*)\-([a-z])([a-z]*)/;
    while (regX.test(propertyName)) {
      regX.exec(propertyName);
      propertyName = RegExp.$1 + RegExp.$2.toUpperCase() + RegExp.$3;
    }
    propertyValue = nodeObject.currentStyle[propertyName];
  }
  return propertyValue;
}
var currentState, currentDisplayState;
function setVisibility(objId,visibility,display,initialSet) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    if (typeof visibility == 'undefined' && typeof display == 'undefined') {
      currentState        = getDivInformation(objId,'visibility');
      currentDisplayState = getDivInformation(objId,'display');
      if (currentState == '') {
        if (initialSet) {
          currentState = 'visible';
        } else {
          currentState = 'hidden';
        }
      }
      if (currentDisplayState == '') {
        if (initialSet) {
          currentDisplayState = initialSet;
        } else {
          currentDisplayState = 'none';
        }
      }
      if (currentState == 'hidden') {
        objId.style.visibility = 'visible';
      } else if (currentState == 'visible'){
        objId.style.visibility = 'hidden';
      }
      if (currentDisplayState == 'none') {
        objId.style.display = 'block';
        objId.style.visibility = 'visible';
      } else if (currentDisplayState == 'block' || currentDisplayState == 'inline') {
        objId.style.display = 'none';
      }
    } else if(visibility == 1) {
        objId.style.visibility = 'visible';
    } else if(visibility == 0) {
      objId.style.visibility = 'hidden';
    }
    if(display) {
      objId.style.display = display;
    }
  }
}
function setZIndex(obj,n) {
  if (typeof obj != 'object') {
    obj = document.getElementById(obj);
  }
  if (obj) {
    obj.style.zIndex = n;
  }
}
function resizeLayer(objId, newWidth, newHeight) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    currentDiv = objId;
    lastWidth = getDivInformation(objId,'width');
    lastHeight = getDivInformation(objId,'height');
    if (newWidth) {
      if (typeof newWidth == 'string') {
        objId.style.width = newWidth;
      } else {
        objId.style.width = newWidth + 'px';
      }
    }
    if (newHeight) {
      if (typeof newHeight == 'string') {
        objId.style.height = newHeight;
      } else {
        objId.style.height = newHeight + 'px';
      }
    }
  }
}
function clipLayer(objId, top, right, bottom, left) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    currentDiv = objId;
    lastWidth = getDivInformation(objId,'width');
    lastHeight = getDivInformation(objId,'height');
    objId.style.clip = "rect("+top+"px "+right+"px "+bottom+"px "+left+"px)";
  }
}
function getClipping(objId) {
  if (typeof objId != 'object') {
    objId = document.getElementById(objId);
  }
  if (objId) {
    return objId.style.clip;
  }
}
function restoreLayer () {
  if (typeof currentDiv == 'object') {
    if (typeof currentDiv.style.width != 'undefined') {
      currentDiv.style.width = lastWidth + 'px';
    }
    if (typeof currentDiv.style.height != 'undefined') {
      currentDiv.style.height = lastHeight + 'px';
    }
    if (typeof currentDiv.style.clip != 'undefined') {
      currentDiv.style.clip = "rect("+0+"px "+lastWidth+"px "+lastHeight+"px "+0+"px)";
    }
  }
}
function centerPopup(popup_url,popup_name,popup_with,popup_height,reopen,myScrollbar,myLeftPos,myTopPos) {
  if(!myLeftPos)  {myLeftPos    = 5;}
  if(!myTopPos)   {myTopPos     = 15;}
  if(!myScrollbar){myScrollbar  = 0;}
  var popup_left                = (window.screen.width/2)  - (popup_with/2 + myLeftPos);
  var popup_top                 = (window.screen.height/2) - (popup_height/2 + myTopPos);
  if ((typeof popup_window != 'object') || (typeof popup_window == 'object' && popup_window.closed)) {
    if (document.all) {
      var xyPos                 = 'left=' + popup_left + ',top=' + popup_top;
    } else {
      var xyPos                 = 'screenX=' + popup_left + ',screenY=' + popup_top;
    }
    popup_window                = window.open(popup_url, popup_name, "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=" + myScrollbar + ",resizable=no,width=" + popup_with + ",height=" + popup_height + ",copyhistory=no," + xyPos + "");
    popup_window.opener         = self;
    popup_window.focus();
    winUrl                      = popup_url;
    windowWidth                 = popup_with;
    windowHeight                = popup_height;
  } else {
    if ((winUrl != popup_url) || reopen) {
      popup_window.location.href = popup_url;
    }
    if ((windowWidth + windowHeight > 0) && (popup_with != windowWidth || popup_height != windowHeight || myLeftPos != diffWidth || myTopPos != diffHeight)) {
      var newWidth              = popup_with  - windowWidth;
      var newHeight             = popup_height - windowHeight;
      popup_window.resizeBy(newWidth,newHeight);
      popup_window.moveTo(popup_left,popup_top);
    }
    popup_window.focus();
    winUrl                      = popup_url;
    windowWidth                 = popup_with;
    windowHeight                = popup_height;
  }
  diffWidth                     = myLeftPos;
  diffHeight                    = myTopPos;
}
function openPopupLink(url,popupString){
  var params=popupString.split(",");
  if(params.length==3){
    centerPopup(url,params[0],params[1],params[2],false,false);
  } else {
    centerPopup(url,"searchwin",800,600,false,false);
  }
}
function splitSearchString() {
 if (self.location.search.indexOf("=") == -1) return;
  parameterArray = self.location.search.substring(1).split("&");
  for (i=0;i<parameterArray.length;i++){
    pair= parameterArray[i].split("=");
    query[unescape(pair[0])]=(pair[1]?unescape(pair[1]):"");
  }
}
function getCookieValue(name) {
  var arg = name + "=";
  var alen = arg.length;
  var i = 0;
  while (i < document.cookie.length) {
    var j = i + alen;
    if (document.cookie.substring(i, j) == arg) {
      var endstr = document.cookie.indexOf (";", j);
      if (endstr == -1) {
        endstr = document.cookie.length;
      }
      return unescape(document.cookie.substring(j, endstr));
    }
    i = document.cookie.indexOf(" ", i) + 1;
    if (i == 0) {
      break;
    }
  }
  return false;
}
function isCookiesEnabled(){
  document.cookie="bmwCookieEnabled=true";
  if(document.cookie.indexOf("bmwCookieEnabled=true")!= -1) {
    var expire=new Date();
    document.cookie = "bmwCookieEnabled=;expires=" + expire.toGMTString();
    return true;
  } else {
    return false;
  }
}
function onLoadFunctions(){
}
function onUnloadFunctions(){
}
function checkWindowSize(){
  if(getWindowInformation('winWidth') < scrollerSize){
    if(document.getElementById('mainNavi')){
      resizeLayer('mainNavi', 1024);
    }
    for(i = 0;i < scrollerCheckElements.length;i++){
      if (document.getElementById(scrollerCheckElements[i])){
        resizeLayer(scrollerCheckElements[i], 1024);
      }
    }
  } else{
    if(document.getElementById('mainNavi')){
      resizeLayer('mainNavi', '100%');
    }
    for(i=0;i < scrollerCheckElements.length;i++){
      if (document.getElementById(scrollerCheckElements[i])){
        resizeLayer(scrollerCheckElements[i], '100%');
      }
    }
  }
}
function changeToHiEndVersion() {
var currentHighlightNew;
  if (currentMatch){
     currentHighlight = currentMatch;
     pos1 = 0;
     pos2a = currentHighlight.lastIndexOf('.');
     currentHighlightNew = currentHighlight.slice(pos1,pos2a);
     pos2b = currentHighlightNew.lastIndexOf('/');
     htmlName = currentHighlight.slice(currentHighlight.lastIndexOf('/')+1,pos2a);
     currentHighlightNew = currentHighlightNew.slice(pos1,pos2b) + "/_highend/xml/" + htmlName;
     xmlMetaNew =currentHighlightNew;
  } else {
     currentHighlight = self.location.href;
     pos1 = currentHighlight.indexOf(confCountryTopic + "/" + confLanguageTopic);
     pos2a = currentHighlight.lastIndexOf('.');
     currentHighlightNew = currentHighlight.slice(pos1,pos2a);
     pos2b = currentHighlightNew.lastIndexOf('/');
     htmlName = currentHighlight.slice(currentHighlight.lastIndexOf('/')+1,pos2a);
     currentHighlightNew = "/" + currentHighlightNew.slice(0,pos2b) + "/_highend/xml/" + htmlName;
     xmlMetaNew =currentHighlightNew;
  }
// SAFARI BUG
  if (document.getElementsByTagName("meta") && !document.getElementsByTagName('meta')['target-url-swf']) {
    var name;
    var metatags = document.getElementsByTagName("meta");
    for(var i=0;i<metatags.length;i++){
      name=metatags.item(i).getAttribute("name");
      if (name && name=='target-url-swf' && metatags.item(i).getAttribute("content")) {
        xmlMeta=metatags.item(i).getAttribute("content");break;
      }
    }
    if (xmlMeta.indexOf('../') != -1) {
      pos1 = xmlMeta.lastIndexOf('../')+2;
      pos2 = xmlMeta.lastIndexOf('.')
      xmlMetaNew = buildValidServerRelativeUrl(xmlMeta.slice(pos1,pos2));
    }
  }
// all Browsers
  if (document.getElementsByTagName('meta')['target-url-swf']) {
    xmlMeta = document.getElementsByTagName('meta')['target-url-swf'].content;
    if (xmlMeta.indexOf('../') != -1) {
      pos1 = xmlMeta.lastIndexOf('../')+2;
      pos2 = xmlMeta.lastIndexOf('.')
      xmlMetaNew = buildValidServerRelativeUrl(xmlMeta.slice(pos1,pos2));
    }
  }
  if (xmlMetaNew != currentHighlightNew) {
    currentHighlightNew = (currentHighlightNew.indexOf('/bmw_edit/') != -1) ? currentHighlightNew.replace("/bmw_edit/","/") : currentHighlightNew;
    currentHighlightNew = (currentHighlightNew.indexOf('/bmw_qa/') != -1) ? currentHighlightNew.replace("/bmw_qa/","/") :  currentHighlightNew;
    currentHighlightNew = (currentHighlightNew.indexOf('/bmw_prod/') != -1) ? currentHighlightNew.replace("/bmw_prod/","/") : currentHighlightNew;
    xmlMeta = "../.." + currentHighlightNew + ".xml";
  }
  if(typeof confCountryTopic != 'undefined' && confCountryTopic != null && typeof confLanguageTopic != 'undefined' && confLanguageTopic != null){
    var basePath            = self.location.href.substring(0, self.location.href.indexOf("/" + confCountryTopic + "/" + confLanguageTopic + "/"));
    var countryLanguagePath = basePath + "/" + confCountryTopic + "/" + confLanguageTopic + "/";
    parent.location.href= countryLanguagePath + framesetPageHighend +'?prm_content='+xmlMeta;
  }
  parent.location.href= framesetPageHighend +'?prm_content='+xmlMeta;
}
function closeChooseBandLayer() {
  if (parent.frameContent.document.getElementById('changeToHighend')) {
    oldDiv=parent.frameContent.document.getElementById('changeToHighend');
    parent.frameContent.document.getElementsByTagName("body")[0].removeChild(oldDiv);
    parent.frameBottom.document.getElementById('changeVersionLink').className = "menu";
    if ((parent.frameContent.useCurtain) && (parent.frameContent.useCurtain == "true")){
       setVisibility(parent.frameContent.document.getElementById('iFrameContainer'),1);
       moveObject(parent.frameContent.document.getElementById('iFrameContainer'),0);
       setVisibility(parent.frameContent.document.getElementById('curtain'),null,'none');
    }
  }
}
function buildLinkList(){
  var links=new Array(document.getElementsByTagName('a').length);
  for (var i = 0; i < document.getElementsByTagName('a').length; i++){
    links[i]=document.getElementsByTagName('a')[i].href;
  }
  return links;
}
function highlightBottomNavigation(){
 if(self.name=="frameContent"){
    if(parent.frames[1] && parent.frameBottom.bottomNavigationLoaded==true){
      parent.frameBottom.highlightBottomNavigation(self.location.href);
    } else {
      setTimeout('highlightBottomNavigation()',300);
    }
  }
}
function resetBottomNavigation(){
  if(self.name=="frameContent"){
    parent.frameBottom.resetBottomNavigation(self.location.href);
  }
}
function evaluateHighlighting(contentUrl, linkList){
  var navLinkFull="";
  var navLinkPath="";
  var navLinkFile="";
  var navLinkQuery="";
  var navLinkPathParts=new Array();
  var contentLinkFull="";
  var contentLinkPath="";
  var contentLinkFile="";
  var contentLinkQuery="";
  var contentLinkPathParts=new Array();
  var evaluatedLinks=new Array();
  if(contentUrl.indexOf('?')!=-1){
    contentLinkFull=contentUrl.substring(0,contentUrl.lastIndexOf('?'));
    contentLinkQuery=contentUrl.substring(contentUrl.lastIndexOf('?'),contentUrl.length);
    if(contentLinkQuery.indexOf("&")!=-1){
      contentLinkQuery=contentLinkQuery.substring(0,contentLinkQuery.indexOf("&"));
    }
  } else {
    contentLinkFull=contentUrl;
  }
  if(contentLinkFull.charAt(contentLinkFull.length-1)=='/') {
    contentLinkFull=contentLinkFull.substring(0,contentLinkFull.length-1);
  }
  if(contentLinkFull.lastIndexOf('/') < contentLinkFull.lastIndexOf('.')){
    contentLinkFile=contentLinkFull.substring(contentLinkFull.lastIndexOf('/')+1,contentLinkFull.length);
    contentLinkPath=contentLinkFull.substring(0, contentLinkFull.lastIndexOf('/'));
  } else {
    contentLinkPath=contentLinkFull;
    contentLinkFile="";
  }
  contentLinkPathParts=contentLinkPath.split('/');
  for(i=0; i < linkList.length;i++){
    navLinkFull=linkList[i];
    if(navLinkFull.indexOf('javascript:')!=-1 || navLinkFull=='') {
       evaluatedLinks.push(999);
      continue;
    }
    if(navLinkFull.indexOf('?')!=-1){
      navLinkQuery=navLinkFull.substring(navLinkFull.lastIndexOf('?'),navLinkFull.length);
      if(navLinkQuery.indexOf("&")!=-1){
        navLinkQuery=navLinkQuery.substring(0,navLinkQuery.indexOf("&"));
      }
      navLinkFull=navLinkFull.substring(0,navLinkFull.lastIndexOf('?'));
    } else {
       navLinkQuery="";
    }
    if(navLinkFull.charAt(navLinkFull.length-1)=='/') {
      navLinkFull=navLinkFull.substring(0,navLinkFull.length-1);
    }
    if(navLinkFull.lastIndexOf('/') < navLinkFull.lastIndexOf('.')){
      navLinkFile=navLinkFull.substring(navLinkFull.lastIndexOf('/')+1,navLinkFull.length);
      navLinkPath=navLinkFull.substring(0, navLinkFull.lastIndexOf('/'));
    } else {
      navLinkPath=navLinkFull;
      navLinkFile="";
    }
    navLinkPathParts=navLinkPath.split('/');
    var contentIndex=0;
    var navIndex=0;
    var bestmatchFound=false;
    var charMatch=null;
    while(navLinkPathParts[navIndex]==contentLinkPathParts[contentIndex]){
      navIndex++;
      contentIndex++;
      if(contentIndex==contentLinkPathParts.length && navIndex==navLinkPathParts.length){
        if(navLinkFile==contentLinkFile){
           if(navLinkQuery==contentLinkQuery){
             evaluatedLinks.push(-2);
             bestmatchFound=true;
           } else {
            evaluatedLinks.push(-1);
          }
        } else {
          charMatch=stringCompare(navLinkFile,contentLinkFile);
         evaluatedLinks.push(0.99-(charMatch/100));
        }
        break;
      } else if(contentIndex==contentLinkPathParts.length){
        evaluatedLinks.push(999);
        break;
      } else if(navIndex==navLinkPathParts.length){
         if(  (confCountryTopic != null
              && confLanguageTopic != null
              && navLinkPathParts.length >= 2
              && navLinkPathParts[navLinkPathParts.length-1] == confLanguageTopic
              && navLinkPathParts[navLinkPathParts.length-2] == confCountryTopic) ||
              (navLinkPathParts.length >= 1 
              && navLinkPathParts[navLinkPathParts.length-1] == "en")
              ){
              // no exact match reached yet but we are in confCountryTopic/confLanguageTopic Directory or in cms directory now
           evaluatedLinks.push(999);
         } else if(confCountryTopic != null && navLinkPath.indexOf("/"+confCountryTopic+"/")==-1 && navLinkPath.indexOf("/en/")==-1){
           // navlink has neither a country topic nor cms topic -> navlink should never be highlighted
           evaluatedLinks.push(999);
         } else { 
          evaluatedLinks.push(contentLinkPathParts.length-contentIndex);
        }
        break;
      } else if(navLinkPathParts[navIndex]!=contentLinkPathParts[contentIndex]){
        evaluatedLinks.push(999);
        break;
      }
    }
    if(bestmatchFound){
      break;
    }
  }
  return evaluatedLinks;
}



function stringCompare(comparator1, comparator2){
   var shorter=null;
   var longer=null;
   if(comparator1.length > comparator2.length){
     longer=comparator1.toLowerCase();
     shorter=comparator2.toLowerCase();
   } else {
     longer=comparator2.toLowerCase();
     shorter=comparator1.toLowerCase();
   }
   var matchCount=0;
   for(var x=0;x < shorter.length;x++){
     if(shorter.charAt(x)==longer.charAt(x)){
       matchCount++;
     }  else {
       break;
     }
   }
   return matchCount;
}
function buildValidServerRelativeUrl(simpleServerRelativeUrl){
   if(simpleServerRelativeUrl==''){
     return '';
   }
   var validServerRelativeUrl='';
   var simpleSeverrelativeUrlNoParams='';
   if(simpleServerRelativeUrl.indexOf('?')!=-1){
     simpleSeverrelativeUrlNoParams=simpleServerRelativeUrl.substring(0,simpleServerRelativeUrl.indexOf('?'));
  } else {
     simpleSeverrelativeUrlNoParams=simpleServerRelativeUrl;
  }
  if(self.location.href.indexOf('/bmw_edit/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/bmw_edit/')==-1){
    validServerRelativeUrl ='/bmw_edit'+simpleServerRelativeUrl;
  } else if(self.location.href.indexOf('/bmw_qa/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/bmw_qa/')==-1){
    validServerRelativeUrl ='/bmw_qa'+simpleServerRelativeUrl;
  } else if(self.location.href.indexOf('/bmw_prod/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/bmw_prod/')==-1){
    validServerRelativeUrl ='/bmw_prod'+simpleServerRelativeUrl;
  //} else if(self.location.href.indexOf('/cms/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/cms/')==-1){
  //  validServerRelativeUrl ='/cms'+simpleServerRelativeUrl;
  } else if(self.location.href.indexOf('/bmw-cn-live/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/bmw-cn-live/')==-1){
    validServerRelativeUrl ='/bmw-cn-live'+simpleServerRelativeUrl;
  } else if(self.location.href.indexOf('/bmw.com.cn/') != -1 && simpleSeverrelativeUrlNoParams.indexOf('/bmw.com.cn/')==-1){
    validServerRelativeUrl ='/bmw.com.cn'+simpleServerRelativeUrl;
  } else {
     validServerRelativeUrl=simpleServerRelativeUrl;
  }
  return validServerRelativeUrl;
}
function getFullPath(basePath,relativePath) {
  var fullPath = basePath.substring(0,(basePath.lastIndexOf("/")+1));
  var regXHostPath = /((^(https{0,1}\:\/\/[^\/]*\/))|(^(file\:\/\/[^\:]*\:\/))|(^([a-z]+\:\\))|(^([a-z]+\:\/))|(^(\\\\))|(^(\/\/)))/i;
  if (regXHostPath.test(relativePath)) {
    fullPath = relativePath;
  } else {
    var regXGoingUp = /(\.\.\/)/g, goingUpArr = [], i;
    if (regXGoingUp.test(relativePath)) {
      goingUpArr = relativePath.match(regXGoingUp);
    }
    for (i=0; i<goingUpArr.length; ++i) {
      fullPath = fullPath.substring(0,(fullPath.lastIndexOf("/",(fullPath.length-2))+1));
    }
    fullPath += relativePath.replace(regXGoingUp,"");
  }
  return fullPath;
}
function setModuleHeader(hasLink) {
 var headerHTML="";
   if(hasLink){
     headerHTML='<a href="javascript:moveMenu();" style="position:relative;display:block;margin-top:1px;">'+moduleHeader+'</a>';
   } else {
    headerHTML='<span style="position:relative;display:block;margin-top:1px;">'+moduleHeader+'</span>';
  }
  if (typeof document.getElementsByTagName('div')['moduleHeaderContainer'] == 'object') {
    writeIntoLayer('moduleHeaderContainer',headerHTML);
    setVisibility('moduleHeaderContainer',1);
  } else {
    moveObject(document.getElementsByTagName('div')['naviClipArea'],null,0);
  }
}
function closeMainNavigation() {
	 try{
  if (window.frames && allowClose == true) {
    if (parent.frames['frameContent'] && parent.frames['frameContent'].window.frames['mainNavigationIFrame'] && parent.frames['frameContent'].window.frames['mainNavigationIFrame'].menuOpen != false){
      parent.frames['frameContent'].window.frames['mainNavigationIFrame'].closeMenu('close');
    } else if (parent.frames['mainNavigationIFrame'] && parent.frames['mainNavigationIFrame'] && parent.frames['mainNavigationIFrame'].menuOpen != false){
      parent.frames['mainNavigationIFrame'].closeMenu('close');
    }
  }
  }catch(err){void(null);}
}
if (window.frames) {
  if (self.location.href.indexOf('https://')==-1) {
    document.onclick = closeMainNavigation;
  }
}

function writeFramesetTitle(titleText) {
}
function preloader(ticketId) {
	
  preLoadCounter[ticketId] = 0;
  preload[ticketId] = new Array();
  for(j=0;j<preLoadArray[ticketId].length;j++) {
    preload[ticketId][j]          = new Image();
    preload[ticketId][j].onabort  = function(){loadUpdate(ticketId,j);}
    preload[ticketId][j].onerror  = function(){loadUpdate(ticketId,j);}
    preload[ticketId][j].onload   = function(){loadUpdate(ticketId,j);}
    preload[ticketId][j].src      = preLoadArray[ticketId][j];
  }
}
function loadUpdate(ticketId,imageId) {
  preLoadCounter[ticketId]++;
  if(preLoadCounter[ticketId] == preLoadArray[ticketId].length) {
    preLoadReady(ticketId);
  }
}
function preLoadReady(ticketId) {
}
function showCo2() {
  setVisibility('co2HeaderOn',1);
  setVisibility('co2HeaderOff',0);
  setVisibility('co2body',1);
}
function hideCo2() {
  setVisibility('co2HeaderOn',0);
  setVisibility('co2HeaderOff',1);
  setVisibility('co2body',0);
}
/**
 * @param {String} popupUrl
 * @param {String} popupName
 * @param {String} popupParams
 */
function openPopupParams(popupUrl, popupName, popupParams){
  if ((typeof popupWindow != 'object') || (typeof popupWindow == 'object' && popupWindow.closed)) {
    if (popupParams) {
      popupWindow = window.open(popupUrl, popupName, popupParams);
    } else {
      popupWindow = window.open(popupUrl, popupName);
    }
    popupWindow.opener = self;
    popupWindow.focus();
    winUrl = popupUrl;
  } else {
    if (winUrl != popupUrl) {
      popupWindow.location.href = popupUrl;
    }
    popupWindow.focus();
    winUrl = popupUrl;
  }
}