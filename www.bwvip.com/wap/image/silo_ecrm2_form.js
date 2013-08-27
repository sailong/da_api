
var transGif           = "/wap/images/1x1_trans.gif";
var pdadadaGif         = "/wap/images/1x1_dadada.gif";
var pulldownGif        = "/wap/images/ecrm2_pulldown.gif";          //point to the new image
var pulldownErrorGif   = "/wap/images/ecrm2_pulldown_error.gif";    //point to the new image

var pff0000Gif		= "/wap/images/1x1_ff0000.gif";

var checkboxGif           = "/wap/images/checkbox.gif";
var checkboxGifHigh       = "/wap/images/checkbox-h.gif";
var checkboxErrorGif      = "/wap/images/checkbox_error.gif";
var checkboxErrorGifHigh  = "/wap/images/checkbox_error-h.gif";
var checkboxDisabledGif   = "/wap/images/checkbox_disabled.gif";
var checkboxDisabledGifHigh = "/wap/images/checkbox_disabled-h.gif";

var en_please_choose = "Please choose";
var zh_please_choose = "请选择";
var please_choose = window.location.href.indexOf("/en/") != -1 ? en_please_choose : zh_please_choose;
var checkReadOnly = new Array();

var formstatus = -1;

var prelodImg = ["/wap/images/ecrm2_top_info_box_bg.png",
		"/wap/images/ecrm2_info_box_bg.png",
		"/wap/images/ecrm2_info_box_bg2.png",
		"/wap/images/ecrm2_info_box_bg_small.png",
		"/wap/images/ecrm2_terms_info_box_bg.png"];

jQuery(document).ready(function(){

   	$(document).mousemove(function(e){

  		globalX=e.pageX;

  		globalY=e.pageY;

  	});

	if( formName == "bmw_ecrm_profile"){
		if(window.jSon && typeof(jSon) == "object"){

			if($('#editProfileContainer') != null) {
				$('#editProfileContainer').css("display","none");
			}
			
			if (formName=="bmw_ecrm_profile" && isLoggedIn){
				$("#formstatus").removeClass("firstRun");
				formstatus=1;
		 		setFormAction("save");
			}
			
			processJson(jSon, 'ok');
			
			$("#button_save_form").get(0).onclick = formSubmit;
		}
	}else{
		var oldOnclick =  $("#button_save_form").get(0).onclick ;
		$("#button_save_form").get(0).onclick = function(){
			showLoading();
			oldOnclick();
		};
		$("#button_save_form").get(0).href="javascript:void(0);";

		if(jQuery("#button_save_form_2").length > 0 && jQuery("#btn_show_profile").css("display") == "block"){
		//if the submit button NO2 (the one beside 'show your profile' button ) exist.
		//this is for the logged in user.
			jQuery("#button_save_form").css("display","none");
		}
	}
	
	//preload the images
	for(var x in prelodImg){
		jQuery(document.body).append('<img class="hidden" src="'+ prelodImg[x] +'" />');
	}
	
	jQuery(document.body).css("padding-left","0px");

});


var rfi_next_checkbox_is_right = false;
function writeMyCheckboxAsSelection(elementId, elemIndex, mandatory, value, error, readonly, className){
	if(selectionValues != null) {
		for(var i = 0; i < selectionValues.length;i++) {
			if(elementId == selectionValues[i][SELECTBOX_INDEX_FIELDNAME]){
				var effValue= (typeof(value)=='undefined' || value==null)?false:value;
				var effError= (typeof(error)=='undefined' || error==null)?false:error;
				var effReadOnly= (typeof(readonly)=='undefined' || readonly==null)?selectionValues[i][SELECTBOX_INDEX_READONLY]:readonly;
				var effClass= (typeof(className)=='undefined' || className==null)?'default':className;
				//console.log(selectionValues[i][SELECTBOX_INDEX_ARRAYNAME][elemIndex*2]);
				
				var stringLength = '';
				if(window.location.href.indexOf("/zh/") != -1){
					stringLength = 6;
				}else{
					stringLength = 11;
				}
				
				var divType = "";
				if(rfi_next_checkbox_is_right == false){
					rfi_next_checkbox_is_right = true; // switch the flag to left side
					var divType = "rfi_left_checkbox";
				}else{
					if(selectionValues[i][SELECTBOX_INDEX_ARRAYNAME][elemIndex*2].length > stringLength){
						document.write('<div class="rfi_right_checkbox"></div>');
						var divType = "rfi_left_checkbox";
						rfi_next_checkbox_is_right = true;  // keep the right side flag
					}else{
						var divType = "rfi_right_checkbox";
						rfi_next_checkbox_is_right = false; // switch the flag to left side
					}
				}
				document.write('<div class="' + divType + '">' + writeCheckbox(
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
				) + '</div>');
				break;
			}
		}
	}
}

  function writeSelectBox(formField, keyValueArray, zIndex, elementWidth, visibleEntries, selectedValue, notify, error, readonly, direction, sublayerwidth) {
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
      selectBox += '<div class="bmwSelectionBoxReadOnly">'+ selectText +'</div>';
      selectBox += '<input type="hidden" name="' + formField + '" value="' + formValue + '">';
    } else {
      selectBox += '<div style="position:relative;z-index:' + zIndex +';">';
      selectBox += '<table class="bmwSelectBoxTableA" width="' + elementWidth + '" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">';
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
        selectBox += '<div style="width:' + (sublayerwidth-2) + 'px; height:' + deep + 'px; background-color:#ffffff; overflow-x:hidden; overflow-y:auto;">';
      }
      else {
        selectBox += '<div style="width:' + (elementWidth-2) + 'px; height:' + deep + 'px; background-color:#ffffff;  overflow-x:hidden; overflow-y:auto;">';
      }
      selectBox += '<span id="vSpace" style="padding-bottom:7px;"></span>';
      for(i=0;i<keyValueArray.length;i++) {
        keyValueArray[i+1] = keyValueArray[i+1].replace(/'/g,"\\\'");
        var tempValue = keyValueArray[i].replace(/'/g,"\\\'");
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
    return selectBox;
  }

/**
* It's for the form associate drop down selection box.
* rewrite this function for the readonly selectionbox.
*/
 function selectBoxNotify(formField,formFieldValue,index){
 	if("" == formField){
 		return;
 	}
 	
 	
 	
	if(formField=="sns_account_type") {
		var new_sns_account_type = formFieldValue;
		var sns_code = '';
		if(new_sns_account_type == '93001'){
			sns_code="SINA"
		}else if(new_sns_account_type == '93002'){
			sns_code="RENREN"
		}else if(new_sns_account_type == '93003'){
			sns_code="QQ"
		}else if(new_sns_account_type == '93004'){
			sns_code="DOUBAN"
		}
		if(sns_code) { openSnsLayer(sns_code); }
	}
 	
 	
 	
 	
 	
 	hasGomez = false;
 	if(typeof(gomez) != "undefined") {
	 	var gomezFormField="";
	 	if(formField.indexOf("intended_series")>=0){
	 		hasGomez = true;
	 		gomezFormField = "dd_intended_series";
	 		gomez.startInterval(gomezFormField);
	 	}else if(formField.indexOf("dealer_city")>=0){
	 		hasGomez = true;
	 		gomezFormField = "dd_tda_dealer_city";
	 		gomez.startInterval(gomezFormField);
	 	}
 	}
 	
 	if(typeof(dependSelections) != "undefined") {
		for(var i = 0; i < dependSelections.length; i++) {
			if(formField == dependSelections[i][0]){
				//find the dependency
				if(dependSelections[i].length > 1 && dependSelections[i][1]){
					for(var j = 0 ; j < dependSelections[i][1].length ; j++){
						
						for(var k = 0; k < selectionValues.length;k++) {
							if(dependSelections[i][1][j] == selectionValues[k][0]){
								if(selectionValues[k][11][formFieldValue]){
								
									jQuery("#" + dependSelections[i][1][j] + "_element").html(writeSelectBox(selectionValues[k][0],selectionValues[k][11][formFieldValue],selectionValues[k][2],selectionValues[k][3],selectionValues[k][4],selectionValues[k][5],
									selectionValues[k][6],selectionValues[k][7],false,selectionValues[k][9],selectionValues[k][10]));
									jQuery("#" + dependSelections[i][1][j] + "_form_item").removeClass("confirm_only");
									jQuery("#" + dependSelections[i][1][j] + "_form_item").removeClass("error");
									jQuery("#" + dependSelections[i][1][j] + "_form_item").removeClass("hidden");
									jQuery("#" + dependSelections[i][1][j] + "_form_item").removeClass("readonly");
								}else{
									jQuery("#" + dependSelections[i][1][j] + "_element").html(writeSelectBox(selectionValues[k][0],[please_choose,'-1'],selectionValues[k][2],selectionValues[k][3],selectionValues[k][4],selectionValues[k][5],
									selectionValues[k][6],selectionValues[k][7],true,selectionValues[k][9],selectionValues[k][10]));
									jQuery("#" + dependSelections[i][1][j] + "_form_item").addClass("readonly");
								}
								
								if(hasGomez){
									gomez.endInterval(gomezFormField);
								}
								
								break;
							}
						}						
					}
				}
				break;
			}
		}
	}
	if("tda_intended_series" == formField || "tda_intended_model" == formField) {
		var elems = new Array();
		elems[0] =new Array("tda_intended_series","intended_series","11510","700");
		elems[1] =new Array("tda_intended_model","intended_model","11511","710");
		//fillItemValue(elems);
	}
	if("tda_dealer_city" == formField || "tda_dealer" == formField) {
		var elems = new Array();
		elems[0] =new Array("tda_dealer_city","dealer_city","11503","602");
		elems[1] =new Array("tda_dealer","preferred_dealer","11504","605");
		//fillItemValue(elems);
	}
	

	//2012-03-14 by David Li, for BMW Experience Day 2012
	if('ee2012_dealer_province' == formField){
		jQuery('#ee2012_dealer_form_item').addClass('readonly');
		jQuery('#ee2012_event_station_form_item').addClass('readonly');

		// formField, keyValueArray, zIndex, elementWidth, visibleEntries, selectedValue, notify, error, readonly, direction, sublayerwidth
		jQuery("#ee2012_dealer_element").html( writeSelectBox('ee2012_dealer',[please_choose,-1],1992 , 400, 6,-1,true,false,true));
		jQuery("#ee2012_event_station_element").html( writeSelectBox('ee2012_event_station',[please_choose,-1],1991 , 400, 6,-1,true,false,true));
		
	}else if('ee2012_dealer_city' == formField){
		jQuery('#ee2012_event_station_form_item').addClass('readonly');
		jQuery("#ee2012_event_station_element").html( writeSelectBox('ee2012_event_station',[please_choose,-1],1991 , 400, 6,-1,true,false,true));
	}
	
 }
 

 //rewrite this function for the readonly bmw selections.
function initializeChildItem(itemId){
	if(getParentItemId(itemId)){
		var defaultValue = defaultSiloFormValues[getParentItemId(itemId)];
		if(defaultValue == null || defaultValue == "" || defaultValue == "-1" || defaultValue == "请选择" || defaultValue == "Please choose"){
			//don't have proper value
			//default value is -1 [please select]
			if(jQuery("#" + getParentItemId(itemId) + "_form_item").css("display") == "block"){
				//jQuery("#" + itemId + "_form_item").addClass("hidden");
				//rewrite child selection , as readonly.
				writeReadonlyDependChildSelection(itemId);
				jQuery("#" + itemId + "_form_item").addClass("readonly");
			}
		}else{
			if(jQuery("#" + getParentItemId(itemId) + "_form_item").css("display") == "block"){
				jQuery("#" + itemId + "_form_item").removeClass("hidden");
			}
		}
	}
} 

function writeReadonlyDependChildSelection(childFieldName){
 	if(childFieldName != null && childFieldName != "" && typeof(dependSelections) != "undefined") {
		for(var k = 0; k < selectionValues.length;k++) {
			if(childFieldName == selectionValues[k][0]){
				jQuery("#" + childFieldName + "_element").html(writeSelectBox(selectionValues[k][0],[please_choose,'-1'],selectionValues[k][2],selectionValues[k][3],selectionValues[k][4],selectionValues[k][5],
									selectionValues[k][6],selectionValues[k][7],true,selectionValues[k][9],selectionValues[k][10]));
			}
		}
	}
}


 function fillItemValue(elements) {
	if(elements != null) {
		for(var i = 0; i < elements.length; i++) {
			var original = document.getElementsByName(elements[i][0]);
			var target = document.getElementsByName(elements[i][1]);				
			if(original != null && original[0].value != "" && target != null) {
				target[0].value = elements[i][3] + original[0].value.substring(elements[i][2].length);
			}
		}
	}
}
/*
function showInfoLayer(infoId, infoMsg){
	infoLayer='    	<span>'+infoMsg+'</span>';
	
	jQuery('.infoBoxLayer').css('display',"none");
	
	if(infoId == "data_usage_hint" || infoId == "data_usage"){
		jQuery('#termInfoContent').html(infoLayer);
		jQuery('#termInfoBox').css('top',jQuery('#'+infoId+'_element').offset().top - 310 + "px");
		jQuery('#termInfoBox').css('display',"block");
	}else if(infoId == "email"){
		jQuery('#finfobox').css("height","136px");
		jQuery('.infoBoxBg').remove();
		jQuery('.smallInfoBoxBg').remove();
		jQuery('#f_infobox_item').before('<div class="smallInfoBoxBg"></div>');
		jQuery('#finfoContent').html('<div style="position:relative; margin-top:-10px;">' + infoLayer + '</div>');
		var topPosition = jQuery('#'+infoId+'_element').offset().top - 136;
		if (topPosition < 0){
			topPosition = 0;
		}
		jQuery('#finfobox').css('top',topPosition + "px");
		jQuery('#finfobox').css('display',"block");
	}else{
		jQuery('#finfobox').css("height","176px");
		jQuery('.infoBoxBg').remove();
		jQuery('.smallInfoBoxBg').remove();
		jQuery('#f_infobox_item').before('<div class="infoBoxBg"></div>');
		jQuery('#finfoContent').html(infoLayer);
		var topPosition = jQuery('#'+infoId+'_element').offset().top - 176;
		if (topPosition < 0){
			topPosition = 0;
		}
		jQuery('#finfobox').css('top',topPosition + "px");
		jQuery('#finfobox').css('display',"block");
	}
}
*/

function showInfoLayer(infoId, infoMsg){
	infoLayer='    	<span>'+infoMsg+'</span>';
	
	jQuery('.infoBoxLayer').css('display',"none");
	
	if(infoId == "data_usage_hint" || infoId == "data_usage"){
		jQuery('#termInfoContent').html(infoLayer);
		jQuery('#termInfoBox').css('top',jQuery('#'+infoId+'_element').offset().top - 310 + "px");
		jQuery('#termInfoBox').css('display',"block");
	}else{
		var originalTop = jQuery('#'+infoId+'_element').offset().top;
		var topPosition = originalTop - 176;
		if (topPosition > 0){
			jQuery('#finfobox').css('top',topPosition + "px");
			jQuery('.infoBoxBg').remove();
			jQuery('.infoBoxBg2').remove();
			jQuery('.smallInfoBoxBg').remove();
			jQuery('#f_infobox_item').attr('style',"");
			jQuery('#f_infobox_item').before('<div class="infoBoxBg"></div>');
			jQuery('#finfoContent').html(infoLayer);

			jQuery('#finfobox').css('display',"block");
		}else{
			//don't have enough space, put the info box to the bottom.
			jQuery('#finfobox').css('top',originalTop + 16 + "px");
			jQuery('.infoBoxBg').remove();
			jQuery('.infoBoxBg2').remove();
			jQuery('.smallInfoBoxBg').remove();
			jQuery('#f_infobox_item').css('padding-top',"5px");
			jQuery('#f_infobox_item').before('<div class="infoBoxBg2"></div>');
			jQuery('#finfoContent').html(infoLayer);


			jQuery('#finfobox').css('display',"block");
		}
	}
}

function fillRepeatEmail() {
	var email = document.getElementById("email");
	var email_repeat = document.getElementById("email_repeat");
	var email_hidden = document.getElementById("email_hidden");
	var email_change_flag = document.getElementById("email_change_flag");

	
	if(document.getElementById("email_repeat") != null && document.getElementById("email")!= null) {
		document.getElementById("email_repeat").value = document.getElementById("email").value;
	}
	

	if(formName == "bmw_ecrm_profile") {
		if(document.getElementById("email_hidden") != null 
			&& document.getElementById("email") != null 
			&& document.getElementById("email_change_flag") != null
			&& document.getElementById("email_hidden").value != document.getElementById("email").value) {
			document.getElementById("email_change_flag").value = 'true';
		}
	}
}	

 



function setDealerSiteDefaultValues(loginStatus,dealerCity,preferredDealer) {
			
	if(formName == "bmw_ecrm_profile") {		
		fillHiddenFormValues('data_source','91509');
		if(loginStatus == false) {
			if(dealerCity != null && dealerCity != '') {
				fillHiddenFormValues('dealer_city',dealerCity );	
			}
			if(preferredDealer!= null && preferredDealer != '') {
				fillHiddenFormValues('preferred_dealer',preferredDealer);
				fillHiddenFormValues('dsid',preferredDealer.replace("605","11504"));
			}
		} else {
			var profileDealer = document.getElementsByName("preferred_dealer");
			if(profileDealer != null && profileDealer[0] != null && profileDealer[0].value != "") {
				fillHiddenFormValues('dsid',profileDealer[0].value.replace("605","11504"));
			}
		}		
	} else {
		var tdaDealer = document.getElementsByName("tda_dealer");
		if(tdaDealer != null && tdaDealer[0] != null && tdaDealer[0].value != "") {
			fillHiddenFormValues('dsid',tdaDealer[0].value);	
		}
	}	
}

function fillHiddenFormValues(name,value) {
	var target_element = document.getElementsByName(name);				
	if(target_element != null && target_element[0] != null) {
		target_element[0].value = value;
	}
}


function showProfileSection() {
	var accountDiv = document.getElementById("common_account_existing");
	var personalDiv = document.getElementById("common_personal");
	var phoneDiv = document.getElementById("common_phone");
	var showProfileFlag = document.getElementById("showProfileFlag");		
	var showProfileButtonDiv = document.getElementById("btn_show_profile");
	
	if(accountDiv != null) {                
		accountDiv.className = accountDiv.className.replace("confirm_only","");
	}
	if(personalDiv != null) {                
		personalDiv.className = personalDiv.className.replace("confirm_only","");
	}
	if(phoneDiv != null) {                
		phoneDiv.className = phoneDiv.className.replace("confirm_only","");
	}			
	if(showProfileButtonDiv != null) {
		showProfileButtonDiv.style.display = "none";
	}
	if(showProfileFlag != null) {
		showProfileFlag.value = 'true';
	}
	jQuery("#button_save_form").css("display","block"); // show the bottom submit button.
	//callResizeFrame(false);
	autoPassParameter({height:true,loginStatus:true,scrollToTop:false});
}

function setCheckbox(imageObject,formElement,inputElement) {
		//
		if(jQuery(imageObject).attr("src").indexOf("checkbox-h.gif") == -1 &&  inputElement !=null && inputElement.indexOf("rfi_group_1.") != -1){
			if(jQuery("#rfi_group_1_element").find("input[value=true]").length >= 3){
				//have more than 3 , 
				return false;
			}
		}


	  var setReadonly   = false;
	  for ( var i = 0; i < checkReadOnly.length; i++ ) {
	    if ( checkReadOnly[ i ] == inputElement ) {
	      setReadonly = true;
	      break;
	    } else {
	      setReadonly = false;
	    }
	  }
	  
	var boxImgsrc;
	if(document.forms[formElement][inputElement].value != "true") {
	  document.forms[formElement][inputElement].value = "true";
	  if (document.images[imageObject.id].src.indexOf('-h') != -1) {
		boxImgsrc = document.images[imageObject.id].src;
	  } else {
		boxImgsrc = document.images[imageObject.id].src.split('.gif');
		boxImgsrc = boxImgsrc[0] + '-h.gif'
	  }
	  document.images[imageObject.id].src = boxImgsrc;
	} else {
	  document.forms[formElement][inputElement].value = "false";
	  if (document.images[imageObject.id].src.indexOf('-h') != -1) {
		boxImgsrc = document.images[imageObject.id].src.split('-h');
		boxImgsrc = boxImgsrc[0] + '.gif'
	  } else {
		boxImgsrc = document.images[imageObject.id].src;
	  }
	  document.images[imageObject.id].src = boxImgsrc;
	}
}


function processJson(responseText)  { 

	jSon = responseText;

	errorCount=0;
	jQuery.each( responseText.errors, function(j, m){
			errorCount++;
	});
	/*
 	//formstatus=1 --> section edit/cancel is possible	
 	if (errorCount==0 && formstatus==0){
 		if (isLoggedIn){
 			jQuery("#formstatus").addClass("secondRun"); 
 		}
 		jQuery("#formstatus").removeClass("firstRun"); 
 		formstatus=1; 		
 	}*/
	if (errorCount==0){
		jQuery("#page_error").removeClass("error"); 
	} else {
		jQuery("#page_error").addClass("error");
	}


	if (responseText.sections){
	 			jQuery.each( responseText.sections, function(j, m){
	 					checkFormSection(j,m);
	 			});

	}
	if (responseText.errors){
	 			jQuery.each( responseText.errors, function(j, m){
	 					checkFormErrors(j,m);
	 			});
	}
	if (responseText.dependencies){
	 			jQuery.each( responseText.dependencies, function(j, m){
	 					checkDependencies(j,m);
	 			});
	}

 	if (formstatus==-1){formstatus=0;}
}

function processEchoJson(responseText, statusText)  { 
	
	//checkStatus(responseText, "echo");
	
	if (responseText.sections){
	 			$.each( responseText.sections, function(j, m){
	 					checkFormSectionNoErrors(j,m);
	 			});
	}
	if (responseText.dependencies){
	 			$.each( responseText.dependencies, function(j, m){
	 					checkDependencies(j,m);
	 			});
	}
}



function checkFormSection(section, sectionContent, sectionToEdit){
	// check sections for errors
	objectSetError('#'+ section, sectionContent.hasError);
	objectSetVisiblity('#'+ section, sectionContent.visible);
	//objectSetEditmode('#'+ section, sectionContent.edit);

	if (formstatus>0){
			if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
			
			} else {
				objectSetEditmode('#'+ section, sectionContent.hasError);
			}
	}
	jQuery.each( sectionContent.items, function(item, itemContent){
		objectSetError('#'+ item + "_form_item", itemContent.hasError);
		objectSetVisiblity('#'+ item + "_form_item", itemContent.visible);
		objectSetVisiblity('#'+ item + "_form_item_mandatory", itemContent.mandatory);

		jQuery.each( itemContent.elements, function(element, elementContent){


			// insert value into text/textarea
			if (elementContent.type=="Text"){
				//jQuery("#"+elementContent.id).val(elementContent.value);
				newTextVaL="";
				newTextVaL=elementContent.value;
				newTextVaL=newTextVaL.replace(/&gt;/g,">");
				newTextVaL=newTextVaL.replace(/&lt;/g,"<");
				newTextVaL=newTextVaL.replace(/&quot;/g,'"');
				
				//jQuery("#"+elementContent.id).val(jQuery('<div/>').text(elementContent.value).text());
				jQuery("#"+elementContent.id).val(newTextVaL);
				
			}
			// build checkbox
			if (elementContent.type=="Checkbox"){
				if (formstatus>0){
					if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
						CheckBoxReadOnly=false;
					} else {
						CheckBoxReadOnly=!sectionContent.hasError;
					}
				} else {
						CheckBoxReadOnly=false;
				}	
				if (sectionToEdit){
					jQuery("#"+elementContent.id+"_form_item").html('<div class="form_item_element" id="'+elementContent.id+'_element">'+writeCheckbox('form',itemContent.label,elementContent.value,elementContent.id,elementContent.id,2000-itemContent.index,400,null,itemContent.hasError,'',itemContent.mandatory,false)+'</div>');
				} else {
					jQuery("#"+elementContent.id+"_form_item").html('<div class="form_item_element" id="'+elementContent.id+'_element">'+writeCheckbox('form',itemContent.label,elementContent.value,elementContent.id,elementContent.id,2000-itemContent.index,400,null,itemContent.hasError,'',itemContent.mandatory,CheckBoxReadOnly)+'</div>');
				}
			}
			// build selection
			if (elementContent.type=="ElementGroup"){
				//console.log("elementgroup");
				jQuery.each( itemContent.elements, function(subElement, subElementContent){
				//console.log(subElement,subElementContent);
				if (subElementContent.type=="Selection" && element==item){
						jQuery("#"+subElementContent.id+" option").remove();

						// fill select
						eval("myExtendedOptions="+subElementContent.options);
						var optLength=0;
						jQuery.each(myExtendedOptions, function(idOption, elementOption){
							optLength++;
						});
						var newOptions=new Array();
						var selectedItem="-1";
						for (i=0;i<optLength;i++){
							newOptions.push(myExtendedOptions[i].name);
							newOptions.push(myExtendedOptions[i].value);
							if (myExtendedOptions[i].selected==true){
								selectedItem=myExtendedOptions[i].value
							}
						}
		
						if (formstatus>0){
							if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
								CheckBoxReadOnly=false;
							} else {
								SelectBoxReadOnly=!sectionContent.hasError;
							}
						} else {
							SelectBoxReadOnly=false;
						}

						if (sectionToEdit){
							jQuery("#"+subElementContent.id+"_element").html(writeSelectBox(subElementContent.id,newOptions,getItemZindex(subElementContent.id), 98, 6,selectedItem,true,itemContent.hasError,false));
						} else {
							jQuery("#"+subElementContent.id+"_element").html(writeSelectBox(subElementContent.id,newOptions,getItemZindex(subElementContent.id), 98, 6,selectedItem,true,itemContent.hasError,SelectBoxReadOnly));
						}	
				}
			});	}
			
			
			

			if (elementContent.type=="Selection" && element==item){
				//should the selection displayed as checkbxes				
				if (elementContent.editor=="check"){
						jQuery("#"+elementContent.id+"_element").html("");
						if (formstatus>0){
							if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
								CheckBoxReadOnly=false;
							} else {
								CheckBoxReadOnly=!sectionContent.hasError;
							}
						} else {
							CheckBoxReadOnly=false;
						}			
						eval("myExtendedOptions="+elementContent.options);
						var optLength=0;
						jQuery.each(myExtendedOptions, function(idOption, elementOption){
							optLength++;
						});
						var newOptions=new Array();
						var selectedItem="-1";
						for (checkindex=0;checkindex<optLength;checkindex++){
							if (sectionToEdit){
								//jQuery("#"+elementContent.id+"_form_item").html(writeCheckbox('form',itemContent.label,elementContent.value,elementContent.id,elementContent.id,2000-itemContent.index,400,null,itemContent.hasError,'',itemContent.mandatory,false));
								jQuery("#"+elementContent.id+"_element").append(writeCheckbox('form',myExtendedOptions[checkindex].name, myExtendedOptions[checkindex].selected,elementContent.id+"."+myExtendedOptions[checkindex].value,elementContent.id+"."+myExtendedOptions[checkindex].value,2000-itemContent.index,400,null,itemContent.hasError,'',false,false));
							} else {
								jQuery("#"+elementContent.id+"_element").append(writeCheckbox('form',myExtendedOptions[checkindex].name, myExtendedOptions[checkindex].selected,elementContent.id+"."+myExtendedOptions[checkindex].value,elementContent.id+"."+myExtendedOptions[checkindex].value,2000-itemContent.index,400,null,itemContent.hasError,'',false,CheckBoxReadOnly));
							}
							//jQuery("#"+elementContent.id+"_element").append("<br />");
						}		
				} else {
				  // clear select
					jQuery("#"+elementContent.id+" option").remove();
	
					// fill select
					eval("myExtendedOptions="+elementContent.options);
					var optLength=0;
					jQuery.each(myExtendedOptions, function(idOption, elementOption){
						optLength++;
					});
					//console.log(optLength);
					var newOptions=new Array();
					var selectedItem="-1";
					for (i=0;i<optLength;i++){
						newOptions.push(myExtendedOptions[i].name);
						newOptions.push(myExtendedOptions[i].value);
						if (myExtendedOptions[i].selected==true){
							selectedItem=myExtendedOptions[i].value
						}
					}
	
					if (formstatus>0){
						if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
							SelectBoxReadOnly=false;
						} else {
							SelectBoxReadOnly=!sectionContent.hasError;
						}
					} else {
						SelectBoxReadOnly=false;
					}
					if (sectionToEdit){
						jQuery("#"+elementContent.id+"_element").html(writeSelectBox(elementContent.id,newOptions,getItemZindex(elementContent.id),400, 6,selectedItem,true,itemContent.hasError,false));
					} else {
						jQuery("#"+elementContent.id+"_element").html(writeSelectBox(elementContent.id,newOptions,getItemZindex(elementContent.id),400, 6,selectedItem,true,itemContent.hasError,SelectBoxReadOnly));
					}
				}
			}
		});	
	});
}

function checkFormSectionNoErrors(section, sectionContent, sectionToEdit){
	objectSetVisiblity('#'+ section, sectionContent.visible);
	/*if (formstatus>0){
			objectSetEditmode('#'+ section, sectionContent.hasError);
	}*/
	
	$.each( sectionContent.items, function(item, itemContent){
		//objectSetError('#'+ item + "_form_item", itemContent.hasError);
		objectSetVisiblity('#'+ item + "_form_item", itemContent.visible);
		objectSetVisiblity('#'+ item + "_form_item_mandatory", itemContent.mandatory);

		$.each( itemContent.elements, function(element, elementContent){


			// insert value into text/textarea
			if (elementContent.type=="Text"){
				$("#"+elementContent.id).val(elementContent.value);
			}
			// build checkbox
			if (elementContent.type=="Checkbox"){
				if (formstatus>0){
					if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
						CheckBoxReadOnly=false;
					} else {
						CheckBoxReadOnly=!sectionContent.hasError;
					}
				} else {
					CheckBoxReadOnly=false;
				}	
				if (sectionToEdit){
					$("#"+elementContent.id+"_form_item").html('<div class="form_item_element" id="'+elementContent.id+'_element">'+writeCheckbox('form',itemContent.label,elementContent.value,elementContent.id,elementContent.id,2000-itemContent.index,400,null,$('#'+ item + "_form_item").hasClass('error'),'',itemContent.mandatory,!($('#'+ section).hasClass('editmode') || $('#formstatus').hasClass('firstRun')))+'</div>');
				} else {
					$("#"+elementContent.id+"_form_item").html('<div class="form_item_element" id="'+elementContent.id+'_element">'+writeCheckbox('form',itemContent.label,elementContent.value,elementContent.id,elementContent.id,2000-itemContent.index,400,null,$('#'+ item + "_form_item").hasClass('error'),'',itemContent.mandatory,!($('#'+ section).hasClass('editmode') || $('#formstatus').hasClass('firstRun')))+'</div>');
				}
			}
			
			
			
			if (elementContent.type=="ElementGroup"){
				//console.log("elementgroup");
				$.each( itemContent.elements, function(subElement, subElementContent){
				if (subElementContent.type=="Selection" && element==item){
						$("#"+subElementContent.id+" option").remove();

						// fill select
						eval("myExtendedOptions="+subElementContent.options);
						var optLength=0;
						$.each(myExtendedOptions, function(idOption, elementOption){
							optLength++;
						});
						//console.log(optLength);
						var newOptions=new Array();
						var selectedItem="-1";
						for (i=0;i<optLength;i++){
							newOptions.push(myExtendedOptions[i].name);
							newOptions.push(myExtendedOptions[i].value);
							if (myExtendedOptions[i].selected==true){
								selectedItem=myExtendedOptions[i].value
							}
						}
		
						if (formstatus>0){
							if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
								SelectBoxReadOnly=false;
							} else {
								SelectBoxReadOnly=!sectionContent.hasError;
							}
						} else {
							SelectBoxReadOnly=false;
						}
							$("#"+subElementContent.id+"_element").html(writeSelectBox(subElementContent.id,newOptions,getItemZindex(subElementContent.id), 98, 6,selectedItem,false,$('#'+ item + "_form_item").hasClass('error'),!($('#'+ section).hasClass('editmode') || $('#formstatus').hasClass('firstRun'))));

					
				}
			});	}
			
			if (elementContent.type=="Selection" && element==item){
				//should the selection displayed as checkbxes				
				if (elementContent.editor=="check"){
						$("#"+elementContent.id+"_element").html("");
						if (formstatus>0){

							if (formName=="bmw_ecrm_ctb" && !isLoggedIn){
								CheckBoxReadOnly=false;
							} else {
								CheckBoxReadOnly=!sectionContent.hasError;
							}
						} else {
							CheckBoxReadOnly=false;
						}			
						eval("myExtendedOptions="+elementContent.options);
						var optLength=0;
						$.each(myExtendedOptions, function(idOption, elementOption){
							optLength++;
						});
						var newOptions=new Array();
						var selectedItem="-1";
						
						for (checkindex=0;checkindex<optLength;checkindex++){
							if (sectionToEdit){
								$("#"+elementContent.id+"_element").append(writeCheckbox('form',myExtendedOptions[checkindex].name, myExtendedOptions[checkindex].selected,elementContent.id+"."+myExtendedOptions[checkindex].value,elementContent.id+"."+myExtendedOptions[checkindex].value,2000-itemContent.index,400,null,$('#'+ item + "_form_item").hasClass('error'),'',false,false));
							} else {
								$("#"+elementContent.id+"_element").append(writeCheckbox('form',myExtendedOptions[checkindex].name, myExtendedOptions[checkindex].selected,elementContent.id+"."+myExtendedOptions[checkindex].value,elementContent.id+"."+myExtendedOptions[checkindex].value,2000-itemContent.index,400,null,$('#'+ item + "_form_item").hasClass('error'),'',false,CheckBoxReadOnly));
							}
							//$("#"+elementContent.id+"_element").append("<br />");
						}		
				} else {
				  // clear select
					$("#"+elementContent.id+" option").remove();
	
					// fill select
					eval("myExtendedOptions="+elementContent.options);
					var optLength=0;
					$.each(myExtendedOptions, function(idOption, elementOption){
						optLength++;
					});
					//console.log(optLength);
					var newOptions=new Array();
					var selectedItem="-1";
					for (i=0;i<optLength;i++){
						newOptions.push(myExtendedOptions[i].name);
						newOptions.push(myExtendedOptions[i].value);
						if (myExtendedOptions[i].selected==true){
							selectedItem=myExtendedOptions[i].value
						}
					}
	

					//$("#"+elementContent.id+"_element").html(writeSelectBox(elementContent.id,newOptions,getItemZindex(subElementContent.id),400, 6,selectedItem,true,$('#'+ item + "_form_item").hasClass('error'),!($('#'+ section).hasClass('editmode') || $('#formstatus').hasClass('firstRun')) ));
					$("#"+elementContent.id+"_element").html(writeSelectBox(elementContent.id,newOptions,1000,400, 6,selectedItem,true,$('#'+ item + "_form_item").hasClass('error'),!($('#'+ section).hasClass('editmode') || $('#formstatus').hasClass('firstRun')) ));
						

				}
			}
		});	
	});
}


function objectSetError(id, errormode){
		if (errormode==true) {
			jQuery(id).addClass("error");
			
			if(jQuery(id).hasClass("form_section")){
				jQuery(id).css("display","block");
				jQuery(id).removeClass("confirm_only");
			}

		} else {
			jQuery(id).removeClass("error");	
		}
}

function objectSetVisiblity(id, visible){
		if (visible==true) {
			jQuery(id).removeClass("hidden");	
		} else {
			jQuery(id).addClass("hidden");	
		}
}

function checkFormErrors(error, errorItem){
	//console.log(error, errorItem);
	insertErrorMessage('#'+ errorItem.element, errorItem.message);
}

function insertErrorMessage(id, message){
	//console.log(id, message);
	//jQuery(id).prepend(message);
}

function checkDependencies(id, nothing){
	jQuery('#'+id).unbind("change");
  /*
  jQuery('#'+id).change(function(e){

    var options = { 
        //target:        '#output2',   // target element(s) to be updated with server response 
        dataType:  'json',
        beforeSubmit:  startJson,  // pre-submit callback 
        success:       processEchoJson,  // post-submit callback   
 	    error: ajaxError,
	    timeout:50000
           };	
 		jQuery('#form').ajaxSubmit(options);
 	
		return false;
	});
	*/
}

function edit_section(id){
	if ($(".editmode").size()>0){
		$('#fwarnbox').css('top',$(id+'_section_edit').offset().top-184+"px");
		$('#fwarnbox').jqm({modal:false, overlay:0});
		$('#fwarnbox').jqmShow();		
		//alert("please open only one section for edit");
	} else {
		editFormSection(id.substring(1,id.length),jSon.sections[id.substring(1,id.length)]);
		objectSetEditmode(id, true);
	}
}
function editFormSection(section, sectionContent){
	checkFormSection(section, sectionContent, true)
}

function objectSetEditmode(id, edit){
	if (edit==true) {
		$(id).addClass("editmode");	
		$(id+" input, "+id+" textarea").each(function(i){
 				$(this).removeAttr("readonly") ;
		}); 
		$(id+" select").each(function(i){
			$(this).unbind("focus");
		});
	} else {
		$(id).removeClass("editmode");	
		$(id+" input, "+id+" textarea").each(function(i){
 			$(this).attr("readonly", true) ;
		}); 
		$(id+" select").each(function(i){
			$(this).focus(function(){
				this.blur(); 
				return false;
			});
		});
	}
}

function cancel_section(id){
	objectSetEditmode(id, false);
	//processJson(jSon);
	//globalY = $(id + '_section_edit').offset().top - 184;
	processEchoJson(jSon, "");

}

function confirm_section(id){
	objectSetEditmode(id, false);
	
	$("#form_action").attr("oldValue", $("#form_action").attr("value") ); // backup the current value
	$("#form_action").attr("value","validate_form");
	showLoading();
 	var options = {
        dataType:  'json',
        //beforeSubmit:  startJson,  // pre-submit callback 
        success:      function(data){
                             processJson(data,'confirm_section');
                             
                             $("#form_action").attr("value",$("#form_action").attr("oldValue")); //restore the old value
                             $("#form_action").attr("oldValue", "");
                             hideLoading();
                      } ,  // post-submit callback
    
        error: ajaxError,
        timeout:50000 
	};	
	$('#form').ajaxSubmit(options);		
	return false;
}

function showLoading(){
	jQuery("#washing").css("display","block");
	jQuery("#washing").css("top",globalY - 50 + "px");
	jQuery("#washing").css("left",globalX + "px");
}

function hideLoading(){
	jQuery("#washing").css("display","none");
}

function setFormAction(formaction){
		if(formaction=='save'){
			$('#btn_save_form').removeClass('hidden');
			$('#btn_validate_form').addClass('hidden');
		} else if(formaction=='validate'){
			$('#form_action').val('validate_form');
			$('#btn_validate_form').removeClass('hidden');
			$('#btn_save_form').addClass('hidden');
		}
}

function ajaxError(event){
	//ajax Error.
	if(window.console){
		console.log(event);
	}
}

function formSubmit(){
	
	if ($(".editmode").size()>0){
		if( globalY >= 230){
			$('#fwarnbox').css('top',globalY - 230+"px");
		}else{
			$('#fwarnbox').css('top',"0px");
		}
		$('#fwarnbox').jqm({modal:false,overlay:0});
		$('#fwarnbox').jqmShow();		
	} else {
		showLoading();
		document.forms[0].submit();
	}
	return false;
}

function getItemZindex(elementId){
	var zIndex;
	
	if(window.selectionValues) {
		for(var i = 0; i < selectionValues.length;i++) {
			if(elementId == selectionValues[i][0]){
				zIndex = selectionValues[i][2];
				break;
			}
		}
	}else{
		//get the zIndex from the old element.
		try{
			zIndex = jQuery("#"+ elementId +"_element").children("div")[0].style.zIndex;
			if (typeof(zIndex) == "undefined" || zIndex == ""){
				zIndex = "1000";
			}
		}catch(err){
		
		}
		
	}

	
	return zIndex;
}

function unmaskButton(){

}

/**
* automatically transmite the parameter to publick page via the controller.
*	parameter list:
*		1: height
*		2: isLoggedIn
*		3: scrollToTop
*/
function autoPassParameter(p){
	if(typeof(p) == "object" && p != null){
		var newP = new Object();
		if(typeof(p['height']) == 'boolean'){
			newP['height'] = p['height'];
		}else{
			newP['height'] = true;
		}
		if(typeof(p['loginStatus']) == 'boolean'){
			newP['loginStatus'] = p['loginStatus'];
		}else{
			newP['loginStatus'] = true;
		}
		if(typeof(p['scrollToTop']) == 'boolean'){
			newP['scrollToTop'] = p['scrollToTop'];
		}else{
			newP['scrollToTop'] = true;
		}
		window.autoPassParameterP = newP;
	}else{
		window.autoPassParameterP = {height:true,loginStatus:true,scrollToTop:true};
	}
	//delay the execute time, make sure the page was initialized.
	window.setTimeout(function(){
		for(var i = 0;  i < jQuery("iframe").length ; i++ ){
			if(jQuery("iframe")[i].src.indexOf("controller") != -1){
				var newParameters = "";
				
				if(autoPassParameterP['height']){

					var selfHeight;
					if(jQuery("#formstatus").length > 0){
						selfHeight = jQuery("#formstatus").height();
					}else{
						selfHeight = jQuery(document.body).height();
					}
					selfHeight += 200;
					newParameters += "?processRequest=true&height=" + selfHeight;
				}

				if(autoPassParameterP['loginStatus']){

					if(typeof(isLoggedIn) == "boolean"){
						if(isLoggedIn){							
							newParameters += (newParameters.length>0?"&":"?") + "userLoginStatus=true&loginStatus=true";
						}else{
							newParameters += (newParameters.length>0?"&":"?") + "userLoginStatus=true&loginStatus=false";
						}
					}
				}

				if(autoPassParameterP['scrollToTop']){
					newParameters += (newParameters.length>0?"&":"?") + "scrollToTop=true";
				}

				if(newParameters.length > 0){
				
					var iframePath = jQuery("iframe")[i].src.substr(0,jQuery("iframe")[i].src.indexOf(".html") + 5);
					iframePath = iframePath + newParameters;
					jQuery("iframe")[i].src = iframePath ;
				}

				break;
			}
		}
	},200);
}