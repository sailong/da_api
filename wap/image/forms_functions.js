var pdadadaGif              = "/wap/images/1x1_dadada.gif";
var pff0000Gif              = "/wap/images/1x1_ff0000.gif";
var transGif                = "/wap/images/1x1_trans.gif";
var pulldownGif             = "/wap/images/pulldown.gif";
var pulldownErrorGif        = "/wap/images/pulldown_error.gif";
var checkboxGif             = "/wap/images/checkbox.gif";
var checkboxGifHigh         = "/wap/images/checkbox-h.gif";
var checkboxErrorGif        = "/wap/images/checkbox_error.gif";
var checkboxErrorGifHigh    = "/wap/images/checkbox_error-h.gif";
var checkboxDisabledGif     = "/wap/images/checkbox_disabled.gif";
var checkboxDisabledGifHigh = "/wap/images/checkbox_disabled-h.gif";

  selectBoxes = new Array();
  directOrder = new Array();
  function selectBoxNotify(formField) {
  }

  function setOption(text, value, formField, notify, index) {
	//formFieldValue = $('<div/>').text(value).html();
	formFieldValue = value;	
    activeText = text;
    setVisibility('selectBoxContent'+formField,0,'none');
    writeIntoLayer('selectedValue'+formField, "&nbsp; " + text);
    if(document.forms.length > 0) {
      document.forms[0][formField].value = formFieldValue;
    }
    if(notify) {
      selectBoxNotify(formField,formFieldValue,index);
    }
  }
  function writeSelectBox(formField, keyValueArray, zIndex, elementWidth, visibleEntries, selectedValue, notify, error, readonly, direction, sublayerwidth) {
  //alert(formField +"=="+ keyValueArray +"=="+ zIndex +"=="+  
  //elementWidth +"=="+ visibleEntries +"=="+ selectedValue, 
  //notify +"=="+ error +"=="+ readonly +"=="+ direction +"=="+ sublayerwidth);
    var formValue = "";
    entryFound = false;
    var bgcolorBorder  = "#dadada";
    var bgcolorBorder1 = "#FF6600";
    var borderGif      = pdadadaGif;
    var pulldownImage  = pulldownGif;
    if(error == true) {
      bgcolorBorder = "#ff0000";
      pulldownImage = pulldownErrorGif;
      borderGif     = pff0000Gif;
    }
    for(i=0;i<keyValueArray.length;i++) {
      if(keyValueArray[i+1] == selectedValue) {
        selectText = keyValueArray[i];
        formValue  = keyValueArray[i+1];
        entryFound = true;
        break;
      }
      i++;
    }
    if(!entryFound) {
      selectText = keyValueArray[0];
      formValue  = keyValueArray[1];
    }
    selectBoxes.push(formField);
    directOrder[formField]=false;
    tdWidth = elementWidth-20;
    if(visibleEntries > (keyValueArray.length / 2)) {
      visibleEntries = (keyValueArray.length / 2);
    }
    deep = (visibleEntries * 16) + 14;
    selectBox = '';
    if (readonly == true) {
      selectBox += '<input type="text" class="defaultReadonly" readonly="readonly" value="' + selectText + '">';
      selectBox += '<input type="hidden" name="' + formField + '" value="' + formValue + '">';
    } else {
      selectBox += '<div style="position:relative;z-index:' + zIndex +';">';
      selectBox += '<table width="' + elementWidth + '" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">';
      selectBox += '<colgroup><col width="1"><col width="' + tdWidth + '"><col width="18"><col width="1"></colgroup>';
      selectBox += '<tr><td colspan="4" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + elementWidth +'" height="1"></td></tr>';
      selectBox += '<tr>';
      selectBox += '<td bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="1" height="16"></td>';
      selectBox += '<td valign="middle" onClick="setVisibility(\'selectBoxContent' + formField + '\');" onMouseover="directOrder[\'' + formField + '\']=true;" onMouseout="directOrder[\'' + formField + '\']=false;" style="cursor:pointer;"><span id="selectedValue' + formField + '">&nbsp; ' + selectText + '</span></td>';
      selectBox += '<td><a href="javascript:setVisibility(\'selectBoxContent' + formField + '\');" onMouseover="directOrder[\'' + formField + '\']=true;" onMouseout="directOrder[\'' + formField + '\']=false;"><img src="' + pulldownImage + '" width="18" height="16" border="0"></a></td>';
      selectBox += '<td  bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="1" height="16"></td>';
      selectBox += '</tr>';
      selectBox += '<tr><td colspan="4" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + elementWidth + '" height="1"></td></tr>';
      selectBox += '</table>';
      if (sublayerwidth) {
        if(direction == "above"){
    			selectBox += '<div id="selectBoxContent' + formField + '" class="selectBoxContent" style="width:'+ sublayerwidth +'; top:' + -(deep+1) + 'px;" >';
        } else {
        	selectBox += '<div id="selectBoxContent' + formField + '" class="selectBoxContent" style="width:'+ sublayerwidth +'; border-top:1px solid '+ bgcolorBorder +'; top:17px;">';
        }
        selectBox += '<table width="' + sublayerwidth + '" cellspacing="0" cellpadding="0" border="0">';
      } else {
        if(direction == "above"){
    			selectBox += '<div id="selectBoxContent' + formField + '" class="selectBoxContent" style="top:' + -(deep+1) + 'px;" >';
        } else {
        	selectBox += '<div id="selectBoxContent' + formField + '" class="selectBoxContent">';
        }
        selectBox += '<table width="' + elementWidth + '" cellspacing="0" cellpadding="0" border="0">';
      }
      if(direction == "above"){
        if (sublayerwidth) {
          selectBox += '<tr><td colspan="3" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + sublayerwidth + '" height="1"></td></tr>';
        } else {
          selectBox += '<tr><td colspan="3" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + elementWidth + '" height="1"></td></tr>'; 
        }
      }
      selectBox += '<tr>';
      selectBox += '<td width="1" bgcolor="' + bgcolorBorder + '"><img src="' + transGif + '" width="1" height="1"></td>';
      selectBox += '<td width="' + (tdWidth-2) + '" valign="top">';
      if (sublayerwidth) {
        selectBox += '<div style="width:' + (sublayerwidth-2) + 'px; height:' + deep + 'px; background-color:#ffffff; overflow:auto; overflow-x:hidden;">';
      }
      else {
        selectBox += '<div style="width:' + (elementWidth-2) + 'px; height:' + deep + 'px; background-color:#ffffff; overflow:auto; overflow-x:hidden;">'; 
      }
      selectBox += '<span id="vSpace" style="padding-bottom:7px;"></span>';
      for(i=0;i<keyValueArray.length;i++) {
        keyValueArray[i+1] = keyValueArray[i+1].replace(/'/g,"\\\'");
        var tempValue = keyValueArray[i].replace(/'/g,"\\\'");
        tempValue=tempValue.replace(/&/g,"&amp;amp;");
        selectBox += '<a href="javascript:setOption(\'' + tempValue + '\',\'' + keyValueArray[i+1] + '\',\'' + formField + '\',' + notify + ',' + zIndex +');" class="selectboxEntry">&nbsp; ' + keyValueArray[i] + '</a>';
        i++;
      }
      selectBox += '</div>';
      selectBox += '</td>';
      selectBox += '<td width="1" bgcolor="' + bgcolorBorder + '"><img src="' + transGif + '" width="1" height="1"></td>';
      selectBox += '</tr>';
      if (sublayerwidth) {
        selectBox += '<tr><td colspan="3" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + sublayerwidth + '" height="1"></td></tr>';
      }
      else {
        selectBox += '<tr><td colspan="3" bgcolor="' + bgcolorBorder + '"><img src="' + borderGif + '" width="' + elementWidth + '" height="1"></td></tr>';
      }
      selectBox += '</table>';
      selectBox += '</div>';
      selectBox += '</div>';
      selectBox += '<div style="width:2px;height:2px;overflow:hidden;"></div>';
      selectBox += '<input type="hidden" name="' + formField + '" value="' + formValue + '">';
    }
    //alert(selectBox);
    return selectBox;
    
  }
  function checkSelectBoxStatus() {
    for(j=0;j<selectBoxes.length;j++) {
      if(!directOrder[selectBoxes[j]]) {
        setVisibility('selectBoxContent'+selectBoxes[j],0,'none');
      }
    }
  }
  var scriptedCheckbox = "";
  function writeCheckbox(formField, description, boxInputValue, boxInputName, boxIndex, zIndex, elementWidth, notify, error, className, mandatory, readonly) {
  /*
  alert(formField + "==" + 
  "description " + description + "==" + 
  "boxInputValue" + boxInputValue + "==" + 
  "boxInputName " + boxInputName + "==" + 
  "boxIndex " + boxIndex + "==" + 
  "zIndex " + zIndex + "==" + 
  "elementWidth " + elementWidth + "==" + 
  "notify " + notify + "==" + 
  "error " + error + "==" + 
  "className " + className + "==" + 
  "mandatory " + mandatory + "==" + 
  "readonly" + readonly); */
    var currentGifDefault;
    var currentGifSwitch;
    var currentTextStyle  = "padding-left:23px;";
    var mandatoryStyle;
    if (boxInputValue+''     != "true") {
      currentGifDefault   = checkboxGif;
      currentGifSwitch    = checkboxGifHigh;
      if (error) {
        currentGifDefault = checkboxErrorGif;
        currentGifSwitch  = checkboxErrorGifHigh;
        currentTextStyle  = "padding-left:23px;color:#ff0000;";
      }
      if (readonly) {
        currentGifDefault = checkboxDisabledGif;
        currentGifSwitch  = checkboxDisabledGifHigh;
        currentTextStyle  = "padding-left:23px;color:#333333;";
        checkReadOnly.push(formField);
      } else {
        for (i = 0; i < checkReadOnly.length; i++) {
          if (checkReadOnly[i] == formField) {
            checkReadOnly.slice(i,1);
          }
        }
      }
    } else {
      currentGifDefault   = checkboxGifHigh;
      currentGifSwitch    = checkboxGif;
      if (error) {
        currentGifDefault = checkboxErrorGifHigh;
        currentGifSwitch  = checkboxErrorGif;
        currentTextStyle  = "padding-left:23px;color:#ff0000;";
      }
      if (readonly) {
        currentGifDefault = checkboxDisabledGifHigh;
        currentGifSwitch  = checkboxDisabledGif;
        currentTextStyle  = "padding-left:23px;color:#333333;";
        checkReadOnly.push(formField);
      } else {
        for (i = 0; i < checkReadOnly.length; i++) {
          if (checkReadOnly[i] == formField) {
            checkReadOnly.slice(i,1);
          }
        }
      }
    }
    if (mandatory) {
      mandatoryStyle = "display:inline;";
    } else {
      mandatoryStyle = "display:none;";
    }
    if (readonly){
    		onClickText="";
    } else {
    		onClickText='onClick="setCheckbox(this,\''+formField+'\',\''+boxInputName+'\');"';
  	}
    checkClient();
/*    if (browserId=='Safari') {
      var descriptionWidth = elementWidth - 19;
      scriptedCheckbox =  '<div id="vSpace'  + boxIndex + '" class="formOffset2"></div>'
                     +  '<div class="'+ className +'" style="position:relative; display: inline; width:' + elementWidth + 'px; height:auto; z-index:' + zIndex + ';">'
                     +    '<div style="position:absolute; display:inline; top:13px; left:0; width:23px;"><img src="' + currentGifDefault + '" vspace="1" style="cursor:pointer;" id="checkboxImage'+boxIndex+'" preload="' + currentGifSwitch + '" '+onClickText+'></div>'
                     +    '<div style="display:inline;float:left;"><div id="checkboxTextDiv'  + boxIndex + '" style="'+currentTextStyle+';display: block;width:'+descriptionWidth+'px;">'
                     +      description
                     +    '<span id="mandatory_'+formField+'" style="'+mandatoryStyle+'">*</span></div></div>'
                     +    '<input type="hidden" name="' + boxInputName + '" value="'+boxInputValue+'">'
                     +  '</div>';
    }else{
		*/
      var descriptionWidth = elementWidth - 19;
      scriptedCheckbox =  '<div id="vSpace'  + boxIndex + '" class="formOffset2"></div>'
                     +  '<div class="'+ className +'" style="position:relative; width:' + elementWidth + 'px; height:auto; z-index:' + zIndex + ';">'
                     +    '<div style="display:inline;"><div id="checkboxTextDiv'  + boxIndex + '" style="'+currentTextStyle+';display: block;width:'+descriptionWidth+'px;">'
                     +      description
                     +    '<span id="mandatory_'+formField+'" style="'+mandatoryStyle+'">*</span></div></div>'
                     +    '<div class="hack_rfi01" style="position:absolute; top:0; left:0; width:23px;"><img src="' + currentGifDefault + '" vspace="1" style="cursor:pointer;" id="checkboxImage'+boxIndex+'" preload="' + currentGifSwitch + '" '+onClickText+'></div>'
                     +    '<input type="hidden" name="' + boxInputName + '" value="'+boxInputValue+'">'
                     +  '</div>';
   // }
    return scriptedCheckbox;
  }
  var allowSend = true;
  function handleSubmit(cValue) {
   if (allowSend == true) {
     allowSend = false;
     if(cValue) {
       document.forms[0].elements['action'].value = cValue;
     }
     document.forms[0].submit();
   }
   return false;
  }
  function writeButton (formId, formName, className, currentValue, label) {
    var scriptedButton = '<a href="javascript:handleSubmit(\'' + currentValue + '\')" id="defaultAnchorButton">'+label+'</a>';
    return scriptedButton;
  }
  
  function writeMySelection(elementId, selectedValue, error, zIndex, readonly ,returnContent){
	if(!window.defaultSiloFormValues){window.defaultSiloFormValues = new Object();}
	if(!window.relatedChildItems){window.relatedChildItems = new Array();}
	window.defaultSiloFormValues[elementId] = selectedValue;
	if(selectionValues != null) {
		for(var i = 0; i < selectionValues.length;i++) {
			if(elementId == selectionValues[i][SELECTBOX_INDEX_FIELDNAME]){
				
				if(getParentItemId(elementId)){
					relatedChildItems.push(elementId);
					window.setTimeout("initializeChildItem('"+ elementId +"')",500); //initialize the current item , later,
					var currentItemValueList;
					if(defaultSiloFormValues[getParentItemId(elementId)]){
						currentItemValueList = selectionValues[i][SELECTBOX_INDEX_DEPENDANCE_ARRAY][defaultSiloFormValues[getParentItemId(elementId)]];
						if(typeof(currentItemValueList) == "undefined" || typeof(currentItemValueList) == "string"){
							currentItemValueList = selectionValues[i][SELECTBOX_INDEX_ARRAYNAME];
						}
					}else{
						currentItemValueList = ["---","0"];
					}
					
					var effValue= (typeof(selectedValue)=='undefined' || selectedValue==null)?selectionValues[i][SELECTBOX_INDEX_VALUE]:selectedValue;
					var effError= (typeof(error)=='undefined' || error==null)?selectionValues[i][SELECTBOX_INDEX_ERROR]:error;
					var effReadOnly= (typeof(readonly)=='undefined' || readonly==null)?selectionValues[i][SELECTBOX_INDEX_READONLY]:readonly;
					//var z= (typeof(zIndex)=='undefined' || zIndex==null)?selectionValues[i][SELECTBOX_INDEX_ZINDEX]:zIndex;
					var z= selectionValues[i][SELECTBOX_INDEX_ZINDEX];
					document.write(
						writeSelectBox(
							selectionValues[i][SELECTBOX_INDEX_FIELDNAME],
							currentItemValueList,
							z,
							selectionValues[i][SELECTBOX_INDEX_WIDTH],
							selectionValues[i][SELECTBOX_INDEX_ENTRIES],
							effValue,
							selectionValues[i][SELECTBOX_INDEX_NOTIFY],
							effError,
							effReadOnly,
							selectionValues[i][SELECTBOX_INDEX_DIRECTION],
							selectionValues[i][SELECTBOX_INDEX_LAYERWIDTH])
					);
				}else{
					var effValue= (typeof(selectedValue)=='undefined' || selectedValue==null)?selectionValues[i][SELECTBOX_INDEX_VALUE]:selectedValue;
					var effError= (typeof(error)=='undefined' || error==null)?selectionValues[i][SELECTBOX_INDEX_ERROR]:error;
					var effReadOnly= (typeof(readonly)=='undefined' || readonly==null)?selectionValues[i][SELECTBOX_INDEX_READONLY]:readonly;
					//var z= (typeof(zIndex)=='undefined' || zIndex==null)?selectionValues[i][SELECTBOX_INDEX_ZINDEX]:zIndex;
					var z= selectionValues[i][SELECTBOX_INDEX_ZINDEX];
					var resultContent =	writeSelectBox(
								selectionValues[i][SELECTBOX_INDEX_FIELDNAME],
								selectionValues[i][SELECTBOX_INDEX_ARRAYNAME],
								z,
								selectionValues[i][SELECTBOX_INDEX_WIDTH],
								selectionValues[i][SELECTBOX_INDEX_ENTRIES],
								effValue,
								selectionValues[i][SELECTBOX_INDEX_NOTIFY],
								effError,
								effReadOnly,
								selectionValues[i][SELECTBOX_INDEX_DIRECTION],
								selectionValues[i][SELECTBOX_INDEX_LAYERWIDTH]);
					if(returnContent){
						return resultContent;
					}else{
						document.write(resultContent);
					}
				}				
				break;
			}
		}
	}
}
			
			
function writeMyCheckbox(elementId, mandatory, value, className, error, readonly ){
	if(checkboxValues != null) {
		for(var i = 0; i < checkboxValues.length;i++) {
			if(elementId == checkboxValues[i][CHECKBOX_INDEX_FIELDNAME]){
				var effClass= (typeof(className)=='undefined' || className==null)?'default':className;
				var effError= (typeof(error)=='undefined' || error==null)?checkboxValues[i][CHECKBOX_INDEX_ERROR]:error;
				var effReadOnly= (typeof(readonly)=='undefined' || readonly==null)?checkboxValues[i][CHECKBOX_INDEX_READONLY]:readonly;
				var effValue = (typeof(value)=='undefined' || value==null)?checkboxValues[i][CHECKBOX_INDEX_VALUE]:value; 
				document.write(writeCheckbox(
					'form',
					checkboxValues[i][CHECKBOX_INDEX_DESCRIPTION],
					effValue,
					checkboxValues[i][CHECKBOX_INDEX_FIELDNAME],
					checkboxValues[i][CHECKBOX_INDEX_FIELDNAME],
					checkboxValues[i][CHECKBOX_INDEX_ZINDEX],
					checkboxValues[i][CHECKBOX_INDEX_WIDTH],
					checkboxValues[i][CHECKBOX_INDEX_NOTIFY],
					effError,
					effClass,
					mandatory,
					effReadOnly
				));
				break;
			}
		}
	}
}
			
			
			
function writeMyCheckboxAsSelection(elementId, elemIndex, mandatory, value, error, readonly, className){
	if(selectionValues != null) {
		for(var i = 0; i < selectionValues.length;i++) {
			if(elementId == selectionValues[i][SELECTBOX_INDEX_FIELDNAME]){
				var effValue= (typeof(value)=='undefined' || value==null)?false:value;
				var effError= (typeof(error)=='undefined' || error==null)?false:error;
				var effReadOnly= (typeof(readonly)=='undefined' || readonly==null)?selectionValues[i][SELECTBOX_INDEX_READONLY]:readonly;
				var effClass= (typeof(className)=='undefined' || className==null)?'default':className;
				
				document.write(writeCheckbox(
					'form',
					selectionValues[i][SELECTBOX_INDEX_ARRAYNAME][elemIndex*2],
					effValue,
					selectionValues[i][SELECTBOX_INDEX_FIELDNAME]+'.'+selectionValues[i][SELECTBOX_INDEX_ARRAYNAME][elemIndex*2+1],
					selectionValues[i][SELECTBOX_INDEX_FIELDNAME]+'.'+selectionValues[i][SELECTBOX_INDEX_ARRAYNAME][elemIndex*2+1],
					selectionValues[i][SELECTBOX_INDEX_ZINDEX],
					selectionValues[i][SELECTBOX_INDEX_WIDTH],
					selectionValues[i][SELECTBOX_INDEX_NOTIFY],
					effError,
					effClass,
					mandatory,
					effReadOnly
				));
				break;
			}
		}
	}
}  

function getParentItemId(itemId){
    if(typeof(dependSelections) != "undefined") {
        for(var i = 0; i < dependSelections.length; i++) {
            for(var j = 0; j < dependSelections[i][1].length; j++ ){
                if(itemId == dependSelections[i][1][j]){
                    return dependSelections[i][0];
                }
            }
        }
    }
    return "";       
}

function getChildItemsId(itemId){
    if(typeof(dependSelections) != "undefined") {
        for(var i = 0; i < dependSelections.length; i++) {
            if(itemId == dependSelections[i][0]){
                return dependSelections[i][1];
            }
        }
    }
    return "";       
}

function initializeChildItem(itemId){
	if(window.console){ console.log("initialize item ID :" + itemId); }

	if(getParentItemId(itemId)){
		var defaultValue = defaultSiloFormValues[getParentItemId(itemId)];
		if(window.console){ console.log("parent value:" + defaultValue ); }
		if(defaultValue == "" && defaultValue == null){
			//no default value
			if(jQuery("#" + getParentItemId(itemId) + "_form_item").css("display") == "block"){
				jQuery("#" + itemId + "_form_item").addClass("hidden");
			}
		}else if(defaultValue == "" || defaultValue == "-1" || defaultValue == "请选择" || defaultValue == "Please choose"){
			//default value is -1 [please select]
			//jQuery("#" + itemId + "_form_item").addClass("hidden");
			if(jQuery("#" + getParentItemId(itemId) + "_form_item").css("display") == "block"){
				jQuery("#" + itemId + "_form_item").addClass("hidden");
			}
		}else{
			if(jQuery("#" + getParentItemId(itemId) + "_form_item").css("display") == "block"){
				jQuery("#" + itemId + "_form_item").removeClass("hidden");
			}
		}
	}
}
