//default
(function (config) {
    //config['lock'] = true;
    config['fixed'] = true;
    config['okVal'] = 'Ok';
    config['cancelVal'] = 'Cancel';
    // [more..]
})(art.dialog.defaults);

function reload()
{
	var win = art.dialog.open.origin;
	win.location.reload();
}

function msg_success(msg)
{
	art.dialog({
				icon: 'succeed',
				time: 2,
				content: ''+msg+''
			});
}

function msg_error(msg)
{
	art.dialog({
				icon: 'error',
				time: 2,
				content: ''+msg+''
			});
}


function msg_dialog_tip(tips_msg)
{
	art.dialog({
			icon: tips_msg.split('^')[0],
			time: 2,
			content: tips_msg.split('^')[1]
		});

		setTimeout(function() {
			art.dialog.close();
			reload();
			//location= tips_url ;
		}, 1100);

}


function msg_dialog_tipgo(tips_msg,tips_url)
{
	art.dialog({
			icon: tips_msg.split('^')[0],
			time: 2,
			content: tips_msg.split('^')[1]
		});

		setTimeout(function() {
			art.dialog.close();
			reload();
			//location= tips_url ;
		}, 1100);

}



//jack add mod dialog  ---------- START
//弹窗
function mod_dialog(dialog_title,dialog_url)
{
	//alert(12121);
	art.dialog.open(dialog_url, 
	{
		title:dialog_title,
		id: 'add'
	}
	, false);
}





//删除一个 并移除一行 
function mod_delete_one(action_url,ids)
{
	var length = 1;
	if(ids == '' || !confirm('删除成功后将无法恢复，确认继续？')) return false;
	
	$.post(action_url, {ids:ids}, function(res){
		if(res.split('^')[0]=='succeed')
		{
			ids = ids.toString().split(',');
			for(i = 0; i < ids.length; i++)
			{
				$('#ids_'+ids[i]).remove();
			}
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}



//批量删除
function mod_delete_batch(action_url,obj)
{
	var length = 0;
	ids    = getInputChecked(obj);
	length = ids.length;
	ids    = ids.toString();

	if(ids=='') 
	{
		art.dialog({
				icon: 'error',
				time: 2,
				content: '请至少选择一个'
			});
		return ;
	}

	if(ids == '' || !confirm('删除成功后将无法恢复，确认继续？')) return false;
	
	$.post(action_url, {ids:ids}, function(res){
		if(res.split('^')[0]=='succeed')
		{
			ids = ids.toString().split(',');
			for(i = 0; i < ids.length; i++)
			{
				$('#ids_'+ids[i]).remove();
			}
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}


//批量修改
function mod_modify_batch(action_url,obj)
{
	var length = 0;
	ids    = getInputChecked(obj);
	length = ids.length;
	ids    = ids.toString();

	if(ids=='') 
	{
		art.dialog({
				icon: 'error',
				time: 2,
				content: '请至少选择一个'
			});
		return ;
	}

	if(ids == '' || !confirm('修改成功后将无法恢复，确认继续？')) return false;
	
	$.post(action_url, {ids:ids}, function(res){
		if(res.split('^')[0]=='succeed')
		{
			ids = ids.toString().split(',');
			for(i = 0; i < ids.length; i++)
			{
				//$('#ids_'+ids[i]).remove();
			}
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}



//批量提交
function mod_post_batch(action_url,obj)
{
	var length = 0;
	ids    = getInputChecked(obj);
	length = ids.length;
	ids    = ids.toString();

	if(ids=='') 
	{
		art.dialog({
				icon: 'error',
				time: 2,
				content: '请至少选择一个'
			});
		return ;
	}

	$.post(action_url, {ids:ids}, function(res){
		//alert(ids);
		//alert(res);
		if(res.split('^')[0]=='succeed')
		{
			ids = ids.toString().split(',');
			for(i = 0; i < ids.length; i++)
			{
				$('#ids_'+ids[i]).remove();
			}
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}





//不移除行
function mod_post_batch2(action_url,obj,canshu)
{
	var length = 0;
	ids    = getInputChecked(obj);
	length = ids.length;
	ids    = ids.toString();

	if(ids=='') 
	{
		art.dialog({
				icon: 'error',
				time: 2,
				content: '请至少选择一个'
			});
		return ;
	}

	$.post(action_url, {ids:ids,canshu:canshu}, function(res){

		if(res.split('^')[0]=='succeed')
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}



//批量GET 并弹出页面
function mod_batch_dialog(action_url,obj,dialog_title)
{
	var length = 0;
	ids    = getInputChecked(obj);
	length = ids.length;
	ids    = ids.toString();
	
	

	if(ids=='') 
	{
		art.dialog({
				icon: 'error',
				time: 2,
				content: '请至少选择一个'
			});
		return ;
	}
	else
	{
		action_url=action_url+"&ids="+ids;
		art.dialog.open(action_url, 
		{
			title:dialog_title,
			id: 'add'
		}
		, false);
		
	}
	
	
	//alert(ids);
}


//只提交一个
function mod_post(action_url,ids)
{
	
	$.post(action_url, {ids:ids}, function(res){
		if(res.split('^')[0]=='succeed')
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}


//只提交一个 带提示
function mod_post_alert(action_url,ids,msg)
{
	if(!confirm(msg)) return false;
	
	$.post(action_url, {ids:ids}, function(res){
		if(res.split('^')[0]=='succeed')
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
		else
		{
			art.dialog({
				icon: ''+res.split('^')[0]+'',
				time: 2,
				content: ''+res.split('^')[1]+''
			});
		}
	});
}





function close_dialog(_this)
{
	art.dialog.close();
}

function select_one(o)
{
	if(o.checked == true)
	{
		$(o).parents('tr').addClass('bg_on') ;
	}
	else
	{
		$(o).parents('tr').removeClass('bg_on') ;
	}
}


function select_all(o,obj,checkbox)
{
	if(o.checked == true )
	{
		$('#'+obj+' input[name="'+checkbox+'"]').attr('checked','true');
		$('#'+obj+' tr[overstyle="on"]').addClass("bg_on");
	}
	else
	{
		$('#'+obj+' input[name="'+checkbox+'"]').removeAttr('checked');
		$('#'+obj+' tr[overstyle="on"]').removeClass("bg_on");
	}
}


function getInputChecked(obj)
{
	var uids = new Array();
	$.each($('#'+obj+' input:checked'), function(i, n){
		uids.push( $(n).val() );
	});
	return uids;
}



//jack add mod dialog  ---------- END

function setTab(name,cursel,n)
{
	for(i=1;i<=n;i++)
	{
		var menu=document.getElementById(name+"_"+i);
		var con=document.getElementById(name+"_sub_"+i);
		if(i==cursel)
		{
			menu.className="on";	
			con.style.display="block";
		}
		else
		{
			menu.className="";
			con.style.display="none";
		}
		//menu.className=i==cursel?"on":"";
		//con.style.display=i==cursel?"block":"none";
	}
}



//form function
function get_radio_value(radio_name)
{ 
    var obj; 
    obj=document.getElementsByName(radio_name); 
    if(obj!=null)
	{ 
        var i; 
        for(i=0;i<obj.length;i++)
		{ 
            if(obj[i].checked)
			{ 
                return obj[i].value; 
            } 
        } 
    } 
    return null; 
}


function get_check_value(obj,to_obj,fengefu)
{ 
	if(obj!=null && to_obj!=null)
	{
		to_obj.value="";
		var i;
		var j;
		var strs= new Array(); 
		 for(i=0;i<obj.length; i++)
		{
			if(obj[i].checked)
			{
				to_obj.value +=obj[i].value+fengefu;
			}
		}
	}
}


function set_checkbox_value(obj,setvalue,fengefu)
{ 

    if(obj!=null)
	{
		//alert(obj.length);
        var i;
		var j;
		var strs= new Array(); 
        for(i=0;i<obj.length; i++)
		{ 
			strs=setvalue.split(fengefu); 
			for(j=0; j<strs.length; j++)
			{
				if(obj[i].value==strs[j])
				{
					obj[i].checked=true;
				}
			}

        } 
    } 
    return null; 
}


function set_select_value(obj,val)
{
	//alert(obj.options.length);
	for (i=0; i < obj.options.length ;i++)
	{
		svalue = obj.options[i].value;
		if (val == svalue) {obj.options[i].selected = true;break;}
	}
}



//星星评分
function rate(obj,oEvent)
{ 
	//================== 
	// 图片地址设置  
	//================== 
	var imgSrc = '/public/images/star_out.gif'; //没有填色的星星
	var imgSrc_2 = '/public/images/star_on.gif'; //打分后有颜色的星星
	//--------------------------------------------------------------------------- 
	var e = oEvent || window.event; 
	var target = e.target || e.srcElement;  
	var imgArray = obj.getElementsByTagName("img"); 
	for(var i=0;i<imgArray.length;i++){ 
	   imgArray[i]._num = i; 
	   imgArray[i].onclick=function(){ 
		obj.rateFlag=true; 
		document.getElementById("hidrating").value=this._num+1; //this._num+1这个数字写入到数据库中,作为评分的依据
	   }; 
	} 
	if(target.tagName=="IMG"){ 
	document.getElementById("spNote").innerHTML=target.title;
	   for(var j=0;j<imgArray.length;j++){ 
		
		if(j<=target._num){ 
		 imgArray[j].src=imgSrc_2; 
		} else { 
		 imgArray[j].src=imgSrc; 
		} 
	   } 
	} else { 
	   for(var k=0;k<imgArray.length;k++){ 
		var num=document.getElementById("hidrating").value;
		if(num!="0"){
		   document.getElementById("spNote").innerHTML=imgArray[num-1].title;
		}else{
		 document.getElementById("spNote").innerHTML="";
		}
		if(k<num){
		imgArray[k].src=imgSrc_2; 
		}else{
		imgArray[k].src=imgSrc; 
		}
	   } 
	} 
}





