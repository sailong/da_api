<?php
/**
 *    #Case		bwvip
 *    #Page		Field_aboutAction.class.php (球场介绍)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class field_aboutAction extends field_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function field_about()
	{
	    $page_list=select_dict(13);
	    $dict_type = 13;
        $dict_value = get('about_type');
        foreach($page_list as $key=>$val) {
            if($val['dict_value'] == $dict_value) {
                $this->assign('dict_name',$val['dict_name']);
            }
        }
	    
		$list=D("field_about")->field_about_list_pro(" and field_uid='".$_SESSION['field_uid']."' ");
		
		//echo '<pre>';
		//var_dump($list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		$this->assign("about_type",get('about_type'));

		$this->assign("page_title","球场介绍");
    	$this->display();
	}

	public function field_about_add()
	{
//		$page_list=select_dict(13,"select");
//		$this->assign("page_list",$page_list);
	    $dict_value = get('about_type');
		$page_list=select_dict(13);
        foreach($page_list as $key=>$val) {
            if($val['dict_value'] == $dict_value) {
                $this->assign('dict_name',$val['dict_name']);
            }
        }
		$about_type = get('about_type');
		
		$category_list = $this->get_category_list($about_type);
		
		$dict_info = M('dict')->where("dict_type='13' and dict_value='{$about_type}'")->find();
		$this->assign('dict_info',$dict_info);
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["about_content"],"about_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign('category_list',$category_list);
		$this->assign("page_title","添加球场介绍");
    	$this->display();
	}

	public function field_about_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
		    $now_time = time();
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["about_name"]=post("about_name");
			$data["about_type"]=post("about_type");
			$data["about_content"]=stripslashes($_POST["about_content"]);;
			$data["about_tel"]=post("about_tel");
			$data["about_tel2"]=post("about_tel2");
			$data["about_sort"]=post("about_sort");
			$data["language"]=post("language");
			$data["category_id"]=post("category_id");
			$data["about_more"]=post("about_more");
			$data["about_addtime"]=$now_time;
			$pic_arr = array();
			$uploadinfo=upload_file("upload/about","png,jpg,jpeg,gif,bmp,tiff,psd");
			if(!empty($uploadinfo))
			{
		        foreach($uploadinfo as $key=>$val) {
		            $key_name_arr = explode('_',$val['up_name']);
		            $key_id = end($key_name_arr);
		            $pic_arr[$key_id]['pic_url']=$val["savepath"].$val["savename"];
		            $pic_arr[$key_id]['iphone4_pic_url']=$val["savepath"].$val["savename"];
		            $pic_arr[$key_id]['iphone5_pic_url']=$val["savepath"].$val["savename"];
			        $pic_arr[$key_id]['pic_addtime']=$now_time;
		        }
			    unset($uploadinfo);
				
			}
			$first_pic = reset($pic_arr);
			$data["about_pic"] = !empty($first_pic['pic_url']) ? $first_pic['pic_url'] : '';
			$list=M("field_about")->add($data);
			if($list!=false)
			{
			    $about_id = M("field_about")->getLastInsID();
			    if(!empty($pic_arr)) {
    			    foreach($pic_arr as $key=>&$val) {
    			        $val['about_id'] = $about_id;
    			        $res = M('field_about_pic')->add($val);
    			    }
    			    
			    }
			    
				$this->success("添加成功",U('field/field_about/field_about',array('language'=>post("language"),'about_type'=>post("about_type"))));
			}
			else
			{				
				$this->error("添加失败",U('field/field_about/field_about_add',array('language'=>post("language"),'about_type'=>post("about_type"))));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_about/field_about_add',array('language'=>post("language"),'about_type'=>post("about_type"))));
		}

	}


	public function field_about_edit()
	{
		if(intval(get("about_id"))>0)
		{
		    $about_type = get('about_type');
    		$dict_info = M('dict')->where("dict_type='13' and dict_value='{$about_type}'")->find();
    		$this->assign('dict_info',$dict_info);
		    $dict_value = get('about_type');
			
			
			$category_list = $this->get_category_list($dict_value);
			$this->assign('category_list',$category_list);
			
    		$page_list=select_dict(13);
            foreach($page_list as $key=>$val) {
                if($val['dict_value'] == $dict_value) {
                    $this->assign('dict_name',$val['dict_name']);
                }
            }
			/*$page_list=select_dict(13,"select");
			$this->assign("page_list",$page_list);*/
            $about_id = intval(get("about_id"));
			$data=M("field_about")->where("about_id=".$about_id)->find();
			
			$pic_list = M('field_about_pic')->where("about_id='{$about_id}'")->order('pic_id desc')->limit('10')->select();
			$count = count($pic_list);
			for($i=$count;$i<10;$i++) {
			    $pic_list[$i]['pic_id'] = "{$i}_addpic"; 
			}
			$this->assign("pic_list",$pic_list);
			$this->assign("data",$data);
			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data["about_content"],"about_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			
			$this->assign("page_title","修改球场介绍");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_about_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["about_id"]=post("about_id");
			$about_id = $data["about_id"];
			$data["about_name"]=post("about_name");
			$data["about_type"]=post("about_type");
			$data["about_content"]=stripslashes($_POST["about_content"]);;
			$data["about_tel"]=post("about_tel");
			$data["about_tel2"]=post("about_tel2");
			//$data["about_replynum"]=post("about_replynum");
			$data["language"]=post("language");
			$data["about_sort"]=post("about_sort");
			$data["about_more"]=post("about_more");
			$data["category_id"]=post("category_id");
			$uploadinfo=upload_file("upload/about","png,jpg,jpeg,gif,bmp,tiff,psd");
			
			$pic_arr = array();
			$add_pic_arr = array();
			if(!empty($uploadinfo))
			{
		        foreach($uploadinfo as $key=>$val) {
		            $key_name_arr = explode('_',$val['up_name']);
		            $pic_id = end($key_name_arr);
		            if($pic_id == 'addpic'){
		                $add_pic_arr[$key_name_arr[2]]['about_id']=$about_id;
    		            $add_pic_arr[$key_name_arr[2]]['pic_url']=$val["savepath"].$val["savename"];
    		            $add_pic_arr[$key_name_arr[2]]['iphone4_pic_url']=$val["savepath"].$val["savename"];
    		            $add_pic_arr[$key_name_arr[2]]['iphone5_pic_url']=$val["savepath"].$val["savename"];
    			        $add_pic_arr[$key_name_arr[2]]['pic_addtime']=time();
		            }
		            else
		            {
		                $pic_arr[$pic_id]['pic_id']=$pic_id;
    		            $pic_arr[$pic_id]['pic_url']=$val["savepath"].$val["savename"];
    		            $pic_arr[$pic_id]['iphone4_pic_url']=$val["savepath"].$val["savename"];
    		            $pic_arr[$pic_id]['iphone5_pic_url']=$val["savepath"].$val["savename"];
    			        $pic_arr[$pic_id]['pic_addtime']=time();
		            }
		            
		        }
			    unset($uploadinfo);
				
			}
			
			if(!empty($add_pic_arr)) {
			    foreach($add_pic_arr as $key2=>$val2) {
			        $res = M('field_about_pic')->add($val2);
			    }
			}
			if(!empty($pic_arr)) {
			    $first_pic = reset($pic_arr);
			    $data["about_pic"] = $first_pic['pic_url'];
			    foreach($pic_arr as $key1=>$val1) {
			        $res = M('field_about_pic')->where("pic_id='$key1'")->save($val1);
			    }
			}
			
			$list=M("field_about")->save($data);
			$this->success("修改成功",U('field/field_about/field_about',array('language'=>post("language"),'about_type'=>post("about_type"))));
		}
		else
		{
			$this->error("不能重复提交",U('field/field_about/field_about',array('language'=>post("language"),'about_type'=>post("about_type"))));
		}

	}

	public function field_about_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field_about")->where("about_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_about_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_field_about set field_about_state=1 where about_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核成功";
			}			
			
		}
	}

	public function field_about_detail()
	{
		if(intval(get("about_id"))>0)
		{
			$data=M("field_about")->where("about_id=".intval(get("about_id")))->find();
			$pic_list=M("field_about_pic")->where("about_id=".intval(get("about_id")))->select();
    		
			if(!empty($data))
			{
    			$dict_value = get('about_type');
        		$page_list=select_dict(13);
                foreach($page_list as $key=>$val) {
                    if($val['dict_value'] == $dict_value) {
                        $this->assign('dict_name',$val['dict_name']);
                    }
                }
				$this->assign("data",$data);
				$this->assign("pic_list",$pic_list);

				$this->assign("page_title",$data["about_name"]."球场介绍");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}
	//菜单所属分类
	public function get_category_list($about_type)
	{
		$field_uid = $_SESSION["field_uid"];
		$category_about_types = category_father('type_more');
	
		$category_type = '';
		foreach($category_about_types as $key=>$val){
			if(false !== strpos($val,$about_type) || $val==$about_type){
				$category_type = $key;
				break;
			}
		}
		
		$category_list = M('category')->where("field_uid='{$field_uid}' and category_type='{$category_type}'")->select();
		
		if(empty($category_list))
		{
			return false;
		}
		$data_list = array();
		foreach($category_list as $key=>$val)
		{
			$data_list[$val['category_id']] = $val;
		}
		unset($category_list);
		return $data_list;
	}

	

}
?>