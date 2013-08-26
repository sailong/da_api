provinceArray[70] = '海外';
provinceArray[71] = '51035';
cityArray[870] = '海外';
cityArray[871] = '52439';
dependencyValues['51035'] = new Array();
dependencyValues['51035'].push('请选择');
dependencyValues['51035'].push('');
dependencyValues['51035'].push('海外');
dependencyValues['51035'].push('52439');


checkboxValues[2] = new Array();
checkboxValues[2][0] = "ticket_date_e1";
checkboxValues[2][1] = '10月24日';
checkboxValues[2][2] = "true";
checkboxValues[2][3] = "1199";
checkboxValues[2][4] = "314";
checkboxValues[2][5] = false;
checkboxValues[2][6] = false;
checkboxValues[2][7] = "";
checkboxValues[2][8] = false;
checkboxValues[3] = new Array();
checkboxValues[3][0] = "ticket_date_e2";
checkboxValues[3][1] = '10月25日';
checkboxValues[3][2] = "true";
checkboxValues[3][3] = "1199";
checkboxValues[3][4] = "314";
checkboxValues[3][5] = false;
checkboxValues[3][6] = false;
checkboxValues[3][7] = "";
checkboxValues[3][8] = false;
checkboxValues[4] = new Array();
checkboxValues[4][0] = "ticket_date_e3";
checkboxValues[4][1] = '10月26日';
checkboxValues[4][2] = "true";
checkboxValues[4][3] = "1199";
checkboxValues[4][4] = "314";
checkboxValues[4][5] = false;
checkboxValues[4][6] = false;
checkboxValues[4][7] = "";
checkboxValues[4][8] = false;
checkboxValues[5] = new Array();
checkboxValues[5][0] = "ticket_date_e4";
checkboxValues[5][1] = '10月27日';
checkboxValues[5][2] = "true";
checkboxValues[5][3] = "1199";
checkboxValues[5][4] = "314";
checkboxValues[5][5] = false;
checkboxValues[5][6] = false;
checkboxValues[5][7] = "";
checkboxValues[5][8] = false;

var ticketArray = new Array();
ticketArray[0] = "请选择";
ticketArray[1] = "";
ticketArray[2] = "1";
ticketArray[3] = "1";
ticketArray[4] = "2";
ticketArray[5] = "2";
selectionValues[9] = new Array();
selectionValues[9][0] = "ticket";
selectionValues[9][1] =  ticketArray;
selectionValues[9][2] = "2001";
selectionValues[9][3] = "400";
selectionValues[9][4] = "6";
selectionValues[9][5] = "";
selectionValues[9][6] = true;
selectionValues[9][7] = false;
selectionValues[9][8] = false;
selectionValues[9][9] = "";
selectionValues[9][10] = "";
selectionValues[9][11] = dependencyValues;

var bmw_ownerArray = new Array();
bmw_ownerArray[0] = "请选择";
bmw_ownerArray[1] = "";
bmw_ownerArray[2] = "是";
bmw_ownerArray[3] = "1";
bmw_ownerArray[4] = "否";
bmw_ownerArray[5] = "2";
selectionValues[10] = new Array();
selectionValues[10][0] = "bmw_owner";
selectionValues[10][1] =  bmw_ownerArray;
selectionValues[10][2] = "1899";
selectionValues[10][3] = "400";
selectionValues[10][4] = "6";
selectionValues[10][5] = "";
selectionValues[10][6] = true;
selectionValues[10][7] = false;
selectionValues[10][8] = false;
selectionValues[10][9] = "";
selectionValues[10][10] = "";
selectionValues[10][11] = dependencyValues;

var informationArray = new Array();
informationArray[0] = "请选择";
informationArray[1] = "";
informationArray[2] = "高尔夫球场";
informationArray[3] = "高尔夫球场";
informationArray[4] = "高尔夫练习场";
informationArray[5] = "高尔夫练习场";
informationArray[6] = "高尔夫专卖店/高尔夫订场中介";
informationArray[7] = "高尔夫专卖店/高尔夫订场中介";
informationArray[8] = "报纸/杂志媒体";
informationArray[9] = "报纸/杂志媒体";
informationArray[10] = "户外媒体";
informationArray[11] = "户外媒体";
informationArray[12] = "线上媒体";
informationArray[13] = "线上媒体";
informationArray[14] = "其他";
informationArray[15] = "其他";
selectionValues[11] = new Array();
selectionValues[11][0] = "data_information";
selectionValues[11][1] =  informationArray;
selectionValues[11][2] = "1891";
selectionValues[11][3] = "400";
selectionValues[11][4] = "6";
selectionValues[11][5] = "";
selectionValues[11][6] = true;
selectionValues[11][7] = false;
selectionValues[11][8] = false;
selectionValues[11][9] = "";
selectionValues[11][10] = "";
selectionValues[11][11] = dependencyValues;

function checkDropDown(obj,flag){
	if(flag){
		$.each( $("input[name="+obj+"]").parent().find("table td"), function(i, n){
			if($(n).attr("bgColor") == "#dadada"){
				$(n).attr("bgColor","#ff0000");
			}
		});		
		$.each( $("input[name="+obj+"]").parent().find("table td img"), function(i, n){
		  if($(n).attr("src").indexOf("dadada.gif") > -1){
			$(n).attr("src",$(n).attr("src").replace("dadada.gif","ff0000.gif"));
		  }else{
			if($(n).attr("src").indexOf("ecrm2_pulldown.gif") > -1){
				$(n).attr("src",$(n).attr("src").replace("ecrm2_pulldown.gif","ecrm2_pulldown_error.gif"));
			}
		  }
		});
	}else{
		$.each( $("input[name="+obj+"]").parent().find("table td"), function(i, n){
			if($(n).attr("bgColor") == "#ff0000"){
				$(n).attr("bgColor","#dadada");
			}
		});		
		$.each( $("input[name="+obj+"]").parent().find("table td img"), function(i, n){
		  if($(n).attr("src").indexOf("ff0000.gif") > -1){
			$(n).attr("src",$(n).attr("src").replace("ff0000.gif","dadada.gif"));
		  }else{
			if($(n).attr("src").indexOf("ecrm2_pulldown_error.gif") > -1){
				$(n).attr("src",$(n).attr("src").replace("ecrm2_pulldown_error.gif","ecrm2_pulldown.gif"));
			}
		  }
		});	
	}
}

function getParameter(name) {
	var query ="";
	if (document.URL.indexOf('?')!=-1) {
		var startIndex = document.URL.indexOf('?')+1;
		query = document.URL.substring(startIndex);
		var paramString = new Array();
		paramString = query.split('&');
		var parameters = new Array(paramString.length);
		for (var i=0;i<paramString.length;i++) {
			var params = paramString[i].split('=')
			if (params[0]==name) {
				return params[1];
			}
		}
	}
	return "";
}


function submitForm(){
	var flag = false;
	var tempStr,ticket_date,birthday,gender;
	var sFlag1 = false,sFlag2 = false,sFlag3 = false,sFlag4 = false,sFlag5 = false,sFlag6 = false,sFlag7 = false;

	tempStr = "salutation";
	if($("input[name="+tempStr+"]").val() == ""){
		gender = "";
		flag = true;
		sFlag1 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);		
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		gender = $("input[name="+tempStr+"]").val();
		checkDropDown(tempStr, false);
	}

	tempStr = "family_name";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag1 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
	}

	tempStr = "given_name";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag1 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
	}

	tempStr = "mobile";
	if($("input[name="+tempStr+"]").val().length < 6 || $("input[name="+tempStr+"]").val().length > 20){
		flag = true;
		sFlag2 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
	}
	tempStr = "email";
	if($("input[name="+tempStr+"]").val() == "" || !isEmail($("input[name="+tempStr+"]").val())){
		flag = true;
		sFlag2 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		$("#common_phone_section_error").html("请检查您输入的信息。");		
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		$("#common_phone_section_error").html("请检查您输入的信息。");
	}
	tempStr = "province";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag3 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "city";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag3 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "address";
	if($("textarea[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag3 = true;
		$("textarea[name="+tempStr+"]").parent().parent().addClass("error");
	}else{
		$("textarea[name="+tempStr+"]").parent().parent().removeClass("error");
	}
	tempStr = "post_code";
	if($("input[name="+tempStr+"]").val() == "" || $("input[name="+tempStr+"]").val().length > 10){
		flag = true;
		sFlag3 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
	}
	tempStr = "ticket";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag4 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "bmw_owner";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag5 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "intended_series";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag5 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "data_information";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag7 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	tempStr = "data_information";
	if($("input[name="+tempStr+"]").val() == "其他" && $("#information_source").val() == ""){
		flag = true;
		sFlag7 = true;
		$("#information_source").parent().addClass("error");
	}else{
		$("#information_source").parent().removeClass("error");
	}
	/*
	tempStr = "intended_model";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag5 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}
	*/
	tempStr = "intended_date";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		sFlag5 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}

	tempStr = "data_usage";
	if($("input[name="+tempStr+"]").val() == "false"){
		flag = true;
		sFlag6 = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		$("#checkboxImage"+tempStr).attr("src",$("#checkboxImage"+tempStr).attr("src").replace("checkbox.gif","checkbox_error.gif"));
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		$("#checkboxImage"+tempStr).attr("src",$("#checkboxImage"+tempStr).attr("src").replace("_error",""));
	}
	
	if($("input[name=ticket_date_e1]").val() == "false" && $("input[name=ticket_date_e2]").val() == "false" && $("input[name=ticket_date_e3]").val() == "false" && $("input[name=ticket_date_e4]").val() == "false"){
		flag = true;
		sFlag4 = true;
		$("#ticket_date_e_form_item").addClass("error");
		$("#checkboxImageticket_date_e1").attr("src",$("#checkboxImageticket_date_e1").attr("src").replace("checkbox.gif","checkbox_error.gif"));
		$("#checkboxImageticket_date_e2").attr("src",$("#checkboxImageticket_date_e2").attr("src").replace("checkbox.gif","checkbox_error.gif"));
		$("#checkboxImageticket_date_e3").attr("src",$("#checkboxImageticket_date_e3").attr("src").replace("checkbox.gif","checkbox_error.gif"));
		$("#checkboxImageticket_date_e4").attr("src",$("#checkboxImageticket_date_e4").attr("src").replace("checkbox.gif","checkbox_error.gif"));
		ticket_date = "";
	}else{
		$("#ticket_date_e_form_item").removeClass("error");
		$("#checkboxImageticket_date_e1").attr("src",$("#checkboxImageticket_date_e1").attr("src").replace("_error",""));
		$("#checkboxImageticket_date_e2").attr("src",$("#checkboxImageticket_date_e2").attr("src").replace("_error",""));
		$("#checkboxImageticket_date_e3").attr("src",$("#checkboxImageticket_date_e3").attr("src").replace("_error",""));
		$("#checkboxImageticket_date_e4").attr("src",$("#checkboxImageticket_date_e4").attr("src").replace("_error",""));
		ticket_date = "";
		if($("input[name=ticket_date_e1]").val() == "true"){
			ticket_date += "10.24\n"
		}
		if($("input[name=ticket_date_e2]").val() == "true"){
			ticket_date += "10.25\n"
		}
		if($("input[name=ticket_date_e3]").val() == "true"){
			ticket_date += "10.26\n"
		}
		if($("input[name=ticket_date_e4]").val() == "true"){
			ticket_date += "10.27\n"
		}
	}
	/*
	tempStr = "edit_birthday_year";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}	
	tempStr = "edit_birthday_month";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}	
	tempStr = "edit_birthday_day";
	if($("input[name="+tempStr+"]").val() == ""){
		flag = true;
		$("input[name="+tempStr+"]").parent().parent().addClass("error");
		checkDropDown(tempStr, true);
	}else{
		$("input[name="+tempStr+"]").parent().parent().removeClass("error");
		checkDropDown(tempStr, false);
	}		
	*/
	if($("input[name=edit_birthday_year]").val() != "" && $("input[name=edit_birthday_month]").val() != "" && $("input[name=edit_birthday_day]").val() != ""){
		birthday = $("input[name=edit_birthday_year]").val() + "-" + ($("input[name=edit_birthday_month]").val().length==1 ? "0"+$("input[name=edit_birthday_month]").val() : $("input[name=edit_birthday_month]").val()) + "-" + ($("input[name=edit_birthday_day]").val().length==1 ? "0"+$("input[name=edit_birthday_day]").val() : $("input[name=edit_birthday_day]").val());
	}else{
		birthday = ""
	}
	
	if(sFlag1){
		$("#common_personal_section_error").show();
	}else{
		$("#common_personal_section_error").hide();	
	}	
	if(sFlag2){
		$("#common_phone_section_error").show();
	}else{
		$("#common_phone_section_error").hide();	
	}	
	if(sFlag3){
		$("#common_postal_section_error").show();
	}else{
		$("#common_postal_section_error").hide();	
	}	
	if(sFlag4){
		$("#common_ticket_section_error").show();
	}else{
		$("#common_ticket_section_error").hide();	
	}	
	if(sFlag5){
		$("#common_purchase_intention_section_error").show();
	}else{
		$("#common_purchase_intention_section_error").hide();	
	}	
	if(sFlag6){
		$("#common_data_usage_section_error").show();
	}else{
		$("#common_data_usage_section_error").hide();	
	}	
	if(sFlag7){
		$("#common_information_source_section_error").show();
	}else{
		$("#common_information_source_section_error").hide();	
	}
	
	var controllorUrl = "http://"+getParameter('public_domain')+"/cn_gs_rb/zh/tournaments/bmwMasterGame/public_controllor/bmw_ticket_2012.html";
	if(flag){
		$("#washing").hide();
		//$("#controllor").attr("src","about:blank").attr("src",controllorUrl);
		$(".below_dotted_line_left").css("display","inline");
	}else{
		$(".below_dotted_line_left").hide();		
		var information_source_val = $("input[name=data_information]").val();
		if(information_source_val == "其他") information_source_val = information_source_val + "(" + $("#information_source").val() + ")";
		$.ajax({
		   type: "POST",
		   url: "bmwregac.php",
		   //url: "/bw_api.php? &mod=bwm_reg&ac=bwm_reg&no_token=1",
		   data: "ac=bwm_reg&qiancheng="+gender+"&family_name="+$("input[name=family_name]").val()+"&name="+$("input[name=given_name]").val()+"&birthday="+birthday+"&phone="+$("input[name=mobile]").val()+"&email="+$("input[name=email]").val()+"&province="+$("input[name=province]").val()+"&city="+$("input[name=city]").val()+"&address="+$("textarea[name=address]").val()+"&postcode="+$("input[name=post_code]").val()+"&watch_date="+ticket_date+"&ticket="+$("input[name=ticket]").val()+"&is_owners="+$("input[name=bmw_owner]").val()+"&bwm_cars="+$("input[name=intended_series]").val()+"&buy_car_date="+$("input[name=intended_date]").val()+"& is_contact="+$("input[name=dealer_contact_usage]").val()+"&is_readed="+$("input[name=data_usage]").val()+"&learn_channels="+information_source_val,
		   success: function(data){
			 $("#washing").hide();  
			// $("#controllor").attr("src","about:blank").attr("src",controllorUrl);
			  if(data == 1){
				//document.location="successful.html?controllor_url="+controllorUrl;
				  document.location="success.html";
			 }else
			 {			 	
				//document.location="error.html?controllor_url="+controllorUrl ;;
				 document.location="error.html";
			 }
		   }
		});
	}
	
	return false;	
	
}

function isNumber(text) {
	if(text == "") {
		return false;
	}
	var b = /^[0-9]*$/
	if(b.test(text)) {
		return true;
	} else {
		return false;
	}
}

function isEmail(cmail) {
	var re = /^[\w.-]+@([0-9a-z][\w-]+\.)+[a-z]{2,4}$/i;
	if(re.test(cmail))
		return true;
	else {
		return false;
	}
}

$(document).ready(function(){
  if($.browser.msie && $.browser.version < 7){  	
	setTimeout(function() {
		$("#button_save_form").attr("href","###");
		
	}, 100);
  }
  checkSelectBoxStatus();
  

$("#selectBoxContentdata_information .selectboxEntry").click( function() {
	var temp = $("#selectBoxContentdata_information .selectboxEntry").index($(this));
	if(temp == 7){
		$("#information_source").val("").show();
	}else{
		$("#information_source").val("").hide();
	}
})
  
});