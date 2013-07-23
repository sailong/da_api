<?php

/*编辑器调用的初始化类

 *

 */

class editor {

var $Width;

var $Height;

var $Value;

/* 此方法是编辑器的构造方法

 *第一个参数，$Height是高度，不填默认是500px

 *第二个参数，$Width是宽度，不填默认是700px

 *第三个参数，$Value是编辑器默认内容，不填默认是“<h2>欢迎使用编辑器</h2><br>”

 *

 */

function editor($Height="500px",$Width="700px",$Value="<h2>欢迎使用编辑器</h2><br>",$Name="content1") {

$this->Value = $Value;

$this->Height = $Height;

$this->Width = $Width;
$this->Name = $Name;

}







/*此方法是在线编辑器的调用

 * 在需要编辑器的地方调用此函数

 */

function createEditor(){

return "<textarea name='$this->Name' style='width:$this->Width;height:$this->Height;visibility:hidden;'>$this->Value</textarea>";

}




/*使用在线编辑器必须在html<head></head>之间调用此方法，才能正确调用，

 * 内容主要都是script

 */

function usejs(){

$str=<<<eot

<link rel="stylesheet" href="__PUBLIC__/editor/themes/default/default.css" />
<link rel="stylesheet" href="__PUBLIC__/editor/plugins/code/prettify.css" />
<script charset="utf-8" src="__PUBLIC__/editor/kindeditor.js"></script>
<script charset="utf-8" src="__PUBLIC__/editor/lang/zh_CN.js"></script>
<script charset="utf-8" src="__PUBLIC__/editor/plugins/code/prettify.js"></script>
<script>
KindEditor.ready(function(K) {
var editor1 = K.create('textarea[name="$this->Name"]', {
cssPath : '__PUBLIC__/editor/plugins/code/prettify.css',
uploadJson : '__PUBLIC__/editor/php/upload_json.php',
fileManagerJson : '__PUBLIC__/editor/php/file_manager_json.php',
allowFileManager : true,
afterCreate : function() {

var self = this;

K.ctrl(document, 13, function() {

self.sync();

K('form[name=example]')[0].submit();

});

K.ctrl(self.edit.doc, 13, function() {

self.sync();

K('form[name=example]')[0].submit();

});

}

});

prettyPrint();

});

</script>

eot;

return $str;

}




/*取得在线编辑器的值并返回

 */

 function getEditorContent(){

    $htmlData = '';

   if (!empty($_POST['$this->Name'])) {

	if (get_magic_quotes_gpc()) {

	$htmlData = stripslashes($_POST['$this->Name']);

	} else {

	$htmlData = $_POST['$this->Name'];

	}

	return $htmlData;

   }

 }




}
