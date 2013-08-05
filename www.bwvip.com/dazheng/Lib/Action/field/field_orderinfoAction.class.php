<?php
/**
 *    #Case		mlh
 *    #Page		field_orderinfoAction.class.php(会员订场)
 *
 *    @author		changsailong
 *    @E-mail		653690921@qq.com
 */
class field_orderinfoAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}
    public function field_orderlist()
    {
        $page = get('page');
        $language = get('language');
        if(empty($language)) {
            $language = 'cn';
        }
        $page_size = 10;
        $field_uid = $_SESSION["field_uid"];
        $field_orderinfo_model =new field_orderinfoModel();
        $list = $field_orderinfo_model->get_orderinfo_list($field_uid,$page,$page_size);
        if(!empty($list['item'])) {
            foreach($list['item'] as $key=>&$val) {
                if($language == 'en') {
                    $val['field_orderinfo_title'] = $val['field_orderinfo_title_en'];
                    $val['field_orderinfo_content'] = $val['field_orderinfo_content_en'];
                }
                unset($val['field_orderinfo_title_en'],$val['field_orderinfo_content_en']);
            }
        }
        $this->assign('info_list',$list['item']);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
        $this->assign('language',$language);
		$this->assign("page_title","文章");
        
        $this->display('field_orderinfo_list');
    }
    
    public function orderinfo_add()
    {
        $this->display('field_orderinfo_add');  
    }
    
	public function orderinfo_add_action() 
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $data['uid'] = $_SESSION["uid"];//post('uid');
		    $language = post('language');
		    $data['field_uid'] = $_SESSION["field_uid"];//post('field_uid');
		    if($language == 'en') {
		        $data['field_orderinfo_title_en'] = post('field_orderinfo_title');
		        $data['field_orderinfo_content_en'] = post('content');
		    }else{
		        $data['field_orderinfo_title'] = post('field_orderinfo_title');
		        $data['field_orderinfo_content'] = post('content');
		    }
		    $data['field_orderinfo_is_memberphone'] = post('is_memberphone');
		    $data['field_orderinfo_isnot_memberphone'] = post('isnot_memberphone');
		    $data['field_orderinfo_addtime'] = time();
		    
		    $list=M("field_orderinfo")->add($data);
		    if($list!=false)
			{
				$this->success("添加成功",U('field/field_orderinfo/field_orderlist'));
			}
			else
			{				
				$this->error("添加失败",U('field/field_orderinfo/field_orderlist'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_orderinfo/field_orderlist'));
		}
	}
	public function orderinfo_detail() {
	    $orderinfo_id = get('orderinfo_id');
	    $language = get('language');
	    
	    $field_orderinfo_model =new field_orderinfoModel();
	    $info = $field_orderinfo_model->get_orderinfo_byid($orderinfo_id);
	   
	    if($language == 'en') {
            $info['field_orderinfo_title'] = $info['field_orderinfo_title_en'];
            $info['field_orderinfo_content'] = $info['field_orderinfo_content_en'];
        }
        unset($info['field_orderinfo_title_en'],$info['field_orderinfo_content_en']);
	    $info['language'] = $language;
        $this->assign('info',$info);
     
	    $this->display('field_orderinfo_detail');
	    
	}
	public function orderinfo_edit()
	{
	    $orderinfo_id = get('orderinfo_id');
	    $language = get('language');
	    $field_orderinfo_model =new field_orderinfoModel();
	    $info = $field_orderinfo_model->get_orderinfo_byid($orderinfo_id);
	   
	    if($language == 'en') {
            $info['field_orderinfo_title'] = $info['field_orderinfo_title_en'];
            $info['field_orderinfo_content'] = $info['field_orderinfo_content_en'];
        }
        unset($info['field_orderinfo_title_en'],$info['field_orderinfo_content_en']);
	    $info['language'] = $language;
        $this->assign('info',$info);
     
	    $this->display('field_orderinfo_edit');
	}
    public function orderinfo_edit_action() 
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $language = post('language');
		    $data['field_orderinfo_id'] = post('orderinfo_id');
		    if($language == 'en') {
		        $data['field_orderinfo_title_en'] = post('field_orderinfo_title');
		        $data['field_orderinfo_content_en'] = post('content');
		    }else{
		        $data['field_orderinfo_title'] = post('field_orderinfo_title');
		        $data['field_orderinfo_content'] = post('content');
		    }
		    
		    $data['field_orderinfo_is_memberphone'] = post('is_memberphone');
		    $data['field_orderinfo_isnot_memberphone'] = post('isnot_memberphone');
		    $data['field_orderinfo_addtime'] = time();
		    
		    $list=M("field_orderinfo")->save($data);
		   
		    if($list!=false)
			{
				$this->success("修改成功",U('field/field_orderinfo/field_orderlist'));
			}
			else
			{				
				$this->error("修改失败",U('field/field_orderinfo/field_orderlist'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_orderinfo/field_orderlist'));
		}
	}

	
    public function orderinfo_del_action() 
	{
	    $orderinfo_id = intval(get("orderinfo_id"));
	    if($orderinfo_id>0)
		{
		    $list=M("field_orderinfo")->where("field_orderinfo_id=".$orderinfo_id)->delete();
		    if($list!=false)
			{
				$this->success("删除成功",U('field/field_orderinfo/field_orderlist'));
			}
			else
			{				
				$this->error("删除失败",U('field/field_orderinfo/field_orderlist'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_orderinfo/field_orderlist'));
		}
	}
}
?>