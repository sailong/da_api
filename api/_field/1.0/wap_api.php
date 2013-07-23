<?php
/*
*
* field_api.php
* by zhanglong 2013-05-21
* field app WAP页
*
*/

if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
$language=$_G['gp_language'];
$uid=$_G['gp_uid'];
$field_uid=$_G['gp_field_uid'];

if(empty($ac) || empty($uid) || empty($field_uid)) 
{
    api_json_result(1,1,'缺少参数',NULL);
}

//年费明细
if($ac=='user_nianfei_mingxi')
{
    $type = 1;
    $data = user_service_list($uid, $field_uid, $type, $language);
    //api_json_result(1,0,$app_error['event']['10502'],$data);
    $data = format_font($data);
    if(empty($data)) {
        $default_arr = array(
            array('title'=>'总年费','content'=>'暂无信息'),
            array('title'=>'已缴年费','content'=>'暂无信息'),
            array('title'=>'欠年费','content'=>'暂无信息')
        );
        $str = format_html($default_arr);
        echo format_font($str);exit;
    }
    echo $data;
}

//年费回馈
if($ac=='user_nianfei_huikui')
{
    $type = 2;
    $data = user_service_list($uid, $field_uid, $type, $language);
    //api_json_result(1,0,$app_error['event']['10502'],$data);
    $data = format_font($data);
    if(empty($data)) {
        $default_arr = array(
            array('title'=>'总年费反馈','content'=>'暂无信息'),
            array('title'=>'已用','content'=>'暂无信息'),
            array('title'=>'剩余','content'=>'暂无信息')
        );
        $str = format_html($default_arr);
        echo format_font($str);exit;
    }
    echo $data;
}

//优惠券
if($ac=='user_quan')
{
    $type = 3;
    $data = user_service_list($uid, $field_uid, $type, $language);
    //api_json_result(1,0,$app_error['event']['10502'],$data);
    $data = format_font($data);
    if(empty($data)) {
        $default_arr = array(
            array('title'=>'共有优惠券','content'=>'暂无信息'),
            array('title'=>'已用','content'=>'暂无信息'),
            array('title'=>'剩余','content'=>'暂无信息')
        );
        $str = format_html($default_arr);
        echo format_font($str);exit;
    }
    echo $data;
}

//预存款
if($ac=='user_yucunkuan')
{
    $type = 4;
    $data = user_service_list($uid, $field_uid, $type, $language);
    //api_json_result(1,0,$app_error['event']['10502'],$data);
    $data = format_font($data);
    if(empty($data)) {
        $default_arr = array(
            array('title'=>'预存款总额','content'=>'暂无信息'),
            array('title'=>'已用','content'=>'暂无信息'),
            array('title'=>'剩余','content'=>'暂无信息')
        );
        $str = format_html($default_arr);
        echo format_font($str);exit;
    }
    echo $data;
}

function user_service_list($uid, $field_uid, $type, $language) 
{
    $sql = "select * from tbl_field_user_service where uid='{$uid}' and field_uid='{$field_uid}' and user_service_type='{$type}' limit 1";
	$return_data = DB::fetch_first($sql);
	$data['title'] = 'return_data';
	if(empty($return_data)) {
	    $data['data'] = null;
	}else{
	    if($language == 'en') {
	        if(!empty($return_data['user_service_detail_en'])) {
	            $return_data['user_service_detail'] = $return_data['user_service_detail_en'];
	        }
	    }
	    
	    $return_data['user_service_adddate'] = date('Y年m月d日');
	    unset($return_data['user_service_detail_en'],$return_data['user_service_addtime']);
	    $data['data'] = $return_data;
	}
    return $return_data['user_service_detail'];
}
function format_font($str) {
    if(empty($str)) {
        return false;
    }
    //$str = $str;//"<div style='background-color:#F4F4F4;font-size:18px;'>{$str}</div>";
    if($G['gp_test']==1) {
        echo $str;die;
    }
    return $str;
}

function format_html($default_arr=array()) {
    if(empty($default_arr)) 
    {
        return false;
    }
            		
    
    $str = "<p>
        	<span style='font-size:24px;'> 
        	<table style='width:100%;' class='ke-zeroborder' align='right' border='0' cellpadding='2' cellspacing='0'>
        		<tbody>";
    foreach($default_arr as $key=>$val) {
        $str .= "<tr>
    				<td align='right'>
    					<span style='font-size:24px;'>{$val['title']}</span><br />
    				</td>
    				<td align='center'>
    					：<br />
    				</td>
    				<td>
    					<span style='font-size:24px;'>{$val['content']}</span><br />
    				</td>
    			</tr>
    			";
    }
    $str .= "</tbody>
            	</table>
            <br />
            <br />
            <br />
            </span> 
            </p>";
    
    return $str;
}

?>