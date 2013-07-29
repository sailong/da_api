<?php
class js_alertAction extends field_publicAction
{
    public function _basic()	
	{
		parent::_basic();
	}
	
	public function user_info()
	{
	    
	    $this->display('user');
	}
	
    public function user_list()
	{
	    
	    $list = M()->table('pre_common_member')->field('uid,email,username')->limit('1,20')->select();
	    $this->ajaxReturn($list,'用户信息',1,'json');
	    //echo json_encode($list);
	    //dump(json_encode($list));
	    //$this->display('user');
	}
}