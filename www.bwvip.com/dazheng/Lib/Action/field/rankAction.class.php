<?php
/**
 *    #Case		mlh
 *    #Page		rankAction.class.php(冠军榜排行榜)
 *
 *    @author		changsailong
 *    @E-mail		653690921@qq.com
 */
class rankAction extends field_publicAction
{

    private $http_url = '';
	public function _basic()	
	{
		parent::_basic();
	}
	public function rank()
	{

	    $page_size = 20;
	    $page = intval(get("p"))?get("p"):1;
        $language = get('language');
        $event_id=get('event_id');
        $uid = get('k');
        if(empty($language)) {
            $language = 'cn';
        }
        $field_uid = 1186;
        $where = "b.field_uid='{$field_uid}'";
	    if(!empty($event_id)) {
	        $where .= " and b.field_event_id='{$event_id}'";
	    }
	    if(!empty($uid)) {
	        $where .= " and b.field_uid='{$field_uid}'";
	    }
        
	    $where .= " and a.field_event_id=b.field_event_id";
	    $list = M()->table('tbl_field_event a,tbl_field_event_rank b')->where($where)->field('b.field_event_rank_id,b.field_event_rank_name,b.field_event_rank_name_en,uid,b.field_uid,b.field_event_rank_score,b.field_event_rank_sort,b.field_event_rank_addtime,b.field_event_id,a.field_event_name')->page($page.",".$page_size)->order('b.field_event_rank_sort asc' )->select();
//	    echo '<pre>';
//	    var_dump($list);
	    foreach($list as $key=>&$val) {
	        if($language == 'en') {
	            $val['field_event_rank_name'] = $val['field_event_rank_name_en'];
	        }
	        unset($val['field_event_rank_name_en']);
	    }
	    $total =  M()->table('tbl_field_event a,tbl_field_event_rank b')->where($where)->field('b.field_event_rank_id,b.field_event_rank_name,b.field_event_rank_name_en,uid,b.field_uid,b.field_event_rank_score,b.field_event_rank_sort,b.field_event_rank_addtime,b.field_event_id,a.field_event_name')->count();
	    import ("@.ORG.Page");
		$page = new page ($total, $page_size );
		
	    $this->assign('list',$list);
		$this->assign("pages",$page->show());
		$this->assign("total",$total);
        $this->assign('language',$language);
	    
	    $this->display('rank');
	}
	
	//查看详情
	public function rank_detail(){
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
	    $this->display('rank_detail');
	}
	
	//添加会员服务
	public function rank_add() {
	    
//	    import("@.ORG.editor");  //导入类
//		$editor=new editor("400px","700px",$data['user_service_detail'],"user_service_detail");     //创建一个对象
//		$a=$editor->createEditor();   //返回编辑器
//		$b=$editor->usejs();             //js代码
//		$this->assign('usejs',$b);     //输出到html
//		$this->assign('editor',$a);
	    
	    $this->display('rank_add');
	}
	public function rank_add_action() {
	    $language = post('language');
	    $data['uid'] = post('uid');
	    $data['field_event_id'] = post('field_event_id');
	    $data['field_uid'] = post('field_uid');
	    $data['field_event_rank_score'] = post('field_event_rank_score');
	    $data['field_event_rank_sort'] = post('field_event_rank_sort');
	    $field_event_rank_name = post('field_event_rank_name');
	    if($language == 'en') {
	        $data['field_event_rank_name_en'] = $field_event_rank_name;
	    }else{
	        $language = 'cn';
	        $data['field_event_rank_name'] = $field_event_rank_name;
	    }
	    $data['field_event_rank_addtime'] = time();
	    
	    $res = M('field_event_rank')->add($data);
	    
	    if($res != false) {
	        $this->success("添加成功",U('field/rank/rank',array('p'=>get('p'),'language'=>$language,'field_event_rank_id'=>$field_event_rank_id)));exit;
	    }
	    $this->error("添加失败",U('field/rank/rank',array('p'=>get('p'),'language'=>$language,'field_event_rank_id'=>$field_event_rank_id)));exit;
	}
	
	//修改会员服务
	public function rank_edit() {
	    $field_event_rank_id = get('field_event_rank_id');
	    $language = get('language');
	    if(empty($language)) {
	        $language = 'cn';
	    }
	    if(empty($field_event_rank_id)) {
	        $this->error("缺少参数",U('field/rank/rank',array('p'=>get('p'),'language'=>$language)));exit;
	    }
	    
	    $info = M('field_event_rank')->where("field_event_rank_id='{$field_event_rank_id}'")->find();
	    
	    if($language == 'en') {
	        $info['field_event_rank_name'] = $info['field_event_rank_name_en'];
	    }
	    unset($info['field_event_rank_name_en']);
	    
	    if(empty($info)) {
	        $this->error("暂无信息",U('field/rank/rank',array('p'=>get('p'),'language'=>$language)));exit;
	    }
        $info['language'] = $language;
//        import("@.ORG.editor");  //导入类
//		$editor=new editor("400px","700px",$info['user_service_detail'],"user_service_detail");     //创建一个对象
//		$a=$editor->createEditor();   //返回编辑器
//		$b=$editor->usejs();             //js代码
//		$this->assign('usejs',$b);     //输出到html
//		$this->assign('editor',$a);
       
	    $this->assign('info',$info);
	    
	    $this->display('rank_edit');
	}
	public function rank_edit_action() {
	    $field_event_rank_id = post('field_event_rank_id');
	    $language = get('language');
	    $data['uid'] = post('uid');
	    $data['field_event_id'] = post('field_event_id');
	    $data['field_uid'] = post('field_uid');
	    $data['field_event_rank_score'] = post('field_event_rank_score');
	    $data['field_event_rank_sort'] = post('field_event_rank_sort');
	    $field_event_rank_name = post('field_event_rank_name');
	    if($language == 'en') {
	        $data['field_event_rank_name_en'] = $field_event_rank_name;
	    }else{
	        $language == 'cn';
	        $data['field_event_rank_name'] = $field_event_rank_name;
	    }
	    $data['field_event_rank_addtime'] = time();
	    
	    $res = M('field_event_rank')->where("field_event_rank_id='{$field_event_rank_id}'")->save($data);
	    
	    if($res != false) {
	        $this->success("修改成功",U('field/rank/rank',array('p'=>get('p'),'language'=>$language,'field_event_rank_id'=>$field_event_rank_id)));exit;
	    }
	    $this->error("修改失败",U('field/rank/rank',array('p'=>get('p'),'language'=>$language,'field_event_rank_id'=>$field_event_rank_id)));exit;
	}
	
	//删除会员服务
	public function rank_del_action() {
	    $ids = post('ids');
	    if(empty($ids)) {
	        echo "error^删除失败！";exit;
	    }
	    $res = M('field_event_rank')->where("field_event_rank_id in({$ids})")->delete();
	    if($res != false) {
	        echo "succeed^删除成功!";exit;
	    }
	    echo "error^删除失败！";exit;
	}
	
	
	
	
	
	
	
	
	
	
	
}
?>