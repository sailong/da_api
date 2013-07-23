<?php
/**
 *    #Case		mlh
 *    #Page		user_serviceAction.class.php(会员服务)
 *
 *    @author		changsailong
 *    @E-mail		653690921@qq.com
 */
class user_serviceAction extends field_publicAction
{

    private $http_url = '';
	public function _basic()	
	{
		parent::_basic();
	}
	public function user_service_list()
	{

	    $page_size = 20;
	    $page = intval(get("p"))?get("p"):1;
        $language = get('language');
        $service_type=get('servicetype_id');
        $uid = get('k');
        if(empty($language)) {
            $language = 'cn';
        }
        $field_uid = 1186;
        $where = "field_uid='{$field_uid}'";
	    if(!empty($service_type)) {
	        $where .= " and user_service_type='{$service_type}'";
	    }
	    if(!empty($uid)) {
	        $where .= " and uid='{$uid}'";
	    }
	    $list = M('field_user_service')->where($where)->page($page.",".$page_size)->select();
	    foreach($list as $key=>&$val) {
	        if($language == 'en') {
	            $val['user_service_detail'] = $val['user_service_detail_en'];
	        }
	        unset($val['user_service_detail_en']);
	        if($val['user_service_type'] == 1){
	            $val['user_service_name'] = '年费明细';
	        }elseif($val['user_service_type'] == 2){
	            $val['user_service_name'] = '年费反馈';
	        }elseif($val['user_service_type'] == 3){
	            $val['user_service_name'] = '优惠券';
	        }elseif($val['user_service_type'] == 4){
	            $val['user_service_name'] = '预存款';
	        }
	    }
	    $total =  M('field_user_service')->where($where)->count();
	    import ("@.ORG.Page");
		$page = new page ($total, $page_size );
		
	    $this->assign('list',$list);
		$this->assign("pages",$page->show());
		$this->assign("total",$total);
        $this->assign('language',$language);
	    
	    $this->display('user_service_list');
	}
	
	//查看详情
	public function show_service_info(){
	    $user_service_id = get('user_service_id');
	    $language = get('language');
	    if(empty($user_service_id)) {
	        echo 'error^信息错误';exit;
	    }
	    
	    $info = M('field_user_service')->where("user_service_id='{$user_service_id}'")->find();
	    if($language == 'en') {
	        $info['user_service_detail'] = $info['user_service_detail_en'];
	    }
	    unset($info['user_service_detail_en']);
	    $this->assign('info',$info);
	    $this->display('service_detail');
	}
	
	//添加会员服务
	public function add_user_service() {
	    
	    import("@.ORG.editor");  //导入类
	    $data['user_service_detail']="<p>
            	<span style='font-size:24px;'> 
            	<table style='width:100%;' class='ke-zeroborder' align='right' border='0' cellpadding='2' cellspacing='0'>
            		<tbody>
            			<tr>
            				<td align='right'>
            					<span style='font-size:24px;'>XXX</span><br />
            				</td>
            				<td align='center'>
            					：<br />
            				</td>
            				<td>
            					<span style='font-size:24px;'>XXX元</span><br />
            				</td>
            			</tr>
            			<tr>
            				<td align='right'>
            					<span style='font-size:24px;'>XXX</span><br />
            				</td>
            				<td align='center'>
            					：<br />
            				</td>
            				<td>
            					<span style='font-size:24px;'>XXX元</span><br />
            				</td>
            			</tr>
            			<tr>
            				<td align='right'>
            					<span style='font-size:24px;'>XXX</span><br />
            				</td>
            				<td align='center'>
            					：<br />
            				</td>
            				<td>
            					<span style='font-size:24px;'>XXX元</span><br />
            				</td>
            			</tr>
            		</tbody>
            	</table>
            <br />
            <br />
            <br />
            </span> 
            </p>";
		$editor=new editor("400px","700px",$data['user_service_detail'],"user_service_detail");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
	    
	    $this->display('add_user_service');
	}
	public function add_user_service_action() {
	    $language = post('language');
	    $data['uid'] = post('uid');
	    $data['field_uid'] = 1186;//post('field_uid');
	    $data['user_service_type'] = post('user_service_type');
	    $user_service_detail = stripslashes($_POST["user_service_detail"]);
	    //检查是否该会员UID已经有该服务
	    $res = M('field_user_service')->where("uid='{$data['uid']}' and field_uid='{$data['field_uid']}' and user_service_type='{$data['user_service_type']}'")->find();
	    
	    if($res != false) {
	        $this->error("该会员此服务已存在！",U('field/user_service/add_user_service'));exit;
	    }
	    if($language == 'en') {
	        $data['user_service_detail_en'] = $user_service_detail;
	    }else{
	        $language = 'cn';
	        $data['user_service_detail'] = $user_service_detail;
	    }
	    $data['user_service_addtime'] = time();
	    $res = M('field_user_service')->add($data);
	    
	    if($res != false) {
	        $this->success("添加成功",U('field/user_service/user_service_list',array('p'=>get('p'),'language'=>$language)));exit;
	    }
	    $this->error("添加失败",U('field/user_service/add_user_service'));exit;
	}
	
	//修改会员服务
	public function upd_user_service() {
	    $user_service_id = get('user_service_id');
	    $language = get('language');
	    if(empty($language)) {
	        $language = 'cn';
	    }
	    if(empty($user_service_id)) {
	        $this->error("缺少参数",U('field/user_service/user_service_list',array('p'=>get('p'),'language'=>$language)));exit;
	    }
	    
	    $info = M('field_user_service')->where("user_service_id='{$user_service_id}'")->find();
	    
	    if($language == 'en') {
	        $info['user_service_detail'] = $info['user_service_detail_en'];
	    }
	    unset($info['user_service_detail_en']);
	    
	    if(empty($info)) {
	        $this->error("暂无信息",U('field/user_service/user_service_list',array('p'=>get('p'),'language'=>$language)));exit;
	    }
        $info['language'] = $language;
        import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$info['user_service_detail'],"user_service_detail");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
        
	    $this->assign('info',$info);
	    
	    $this->display('upd_user_service');
	}
	public function upd_user_service_action() {
	    $user_service_id = post('user_service_id');
	    $language = get('language');
	    $data['uid'] = post('uid');
	    $data['field_uid'] = 1186;//post('field_uid');
	    $user_service_detail = stripslashes($_POST["user_service_detail"]);
	    if($language == 'en') {
	        $data['user_service_detail_en'] = $user_service_detail;
	    }else{
	        $language == 'cn';
	        $data['user_service_detail'] = $user_service_detail;
	    }
	    $data['user_service_addtime'] = time();
	    
	    $res = M('field_user_service')->where("user_service_id='{$user_service_id}'")->save($data);
	    
	    if($res != false) {
	        $this->success("修改成功",U('field/user_service/user_service_list',array('p'=>get('p'),'language'=>$language)));exit;
	    }
	    $this->error("修改失败",U('field/user_service/user_service_list',array('p'=>get('p'),'language'=>$language)));exit;
	}
	
	//删除会员服务
	public function del_user_service_action() {
	    $ids = post('ids');
	    if(empty($ids)) {
	        echo "error^删除失败！";exit;
	    }
	    $res = M('field_user_service')->where("user_service_id='{$ids}'")->delete();
	    
	    if($res != false) {
	        echo "succeed^删除成功!";exit;
	    }
	    echo "error^删除失败！";exit;
	}
	
	
	
	
	
	
	
	
	
	
	
}
?>