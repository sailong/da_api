<?php

class indexAction extends publicAction
{
	public function _intialize()	
	{
		parent::_intialize();
	}

	public function index()
	{
		//新闻中心
		$type1=D("arc")->arc_select_pro(" and ( arctype_id=11 or arctype_id2=11 or arctype_id3=11) and arc_state=1",8);
		$this->assign("type1",$type1['item']);
		$type2=D("arc")->arc_select_pro(" and ( arctype_id=12 or arctype_id2=12 or arctype_id3=12) and arc_state=1",8);
		$this->assign("type2",$type2['item']);

		$type3=D("arc")->arc_select_pro(" and ( arctype_id=13 or arctype_id2=13 or arctype_id3=13) and arc_state=1",2);
		$this->assign("type3",$type3['item']);
		$type3_top=D("arc")->arc_select_pro(" and ( arctype_id=13 or arctype_id2=13 or arctype_id3=13) and arc_pic is not null and arc_state=1",1);
		$this->assign("type3_top",$type3_top['item']);
		$type4=D("arc")->arc_select_pro(" and ( arctype_id=14 or arctype_id2=14 or arctype_id3=14) and arc_state=1",2);
		$this->assign("type4",$type4['item']);
		$type4_top=D("arc")->arc_select_pro(" and ( arctype_id=14 or arctype_id2=14 or arctype_id3=14) and arc_pic is not null and arc_state=1",1);
		$this->assign("type4_top",$type4_top['item']);


		$type5=D("arc")->arc_select_pro(" and ( arctype_id=15 or arctype_id2=15 or arctype_id3=15) and arc_state=1",5);
		$this->assign("type5",$type5['item']);
		$type6=D("arc")->arc_select_pro(" and ( arctype_id=16 or arctype_id2=16 or arctype_id3=16) and arc_state=1",5);
		$this->assign("type6",$type6['item']);

		//技术中心
		$type11=D("arc")->arc_select_pro(" and ( arctype_id=25 or arctype_id2=25 or arctype_id3=25) and arc_state=1",5);
		$this->assign("type11",$type11['item']);
		$type22=D("arc")->arc_select_pro(" and ( arctype_id=26 or arctype_id2=26 or arctype_id3=26) and arc_state=1",5);
		$this->assign("type22",$type22['item']);
		$type33=D("arc")->arc_select_pro(" and ( arctype_id=27 or arctype_id2=27 or arctype_id3=27) and arc_state=1",5);
		$this->assign("type33",$type33['item']);
		$type44=D("arc")->arc_select_pro(" and ( arctype_id=28 or arctype_id2=28 or arctype_id3=28) and arc_state=1",5);
		$this->assign("type44",$type44['item']);
		$type55=D("arc")->arc_select_pro(" and ( arctype_id=29 or arctype_id2=29 or arctype_id3=29) and arc_state=1",5);
		$this->assign("type55",$type55['item']);
		$type66=D("arc")->arc_select_pro(" and ( arctype_id=30 or arctype_id2=30 or arctype_id3=30) and arc_state=1",5);
		$this->assign("type66",$type66['item']);
		$type77=D("arc")->arc_select_pro(" and ( arctype_id=31 or arctype_id2=31 or arctype_id3=31) and arc_state=1",5);
		$this->assign("type77",$type77['item']);
		$type88=D("arc")->arc_select_pro(" and ( arctype_id=32 or arctype_id2=32 or arctype_id3=32) and arc_state=1",5);
		$this->assign("type88",$type88['item']);

		
		$type_1=D("arc")->arc_select_pro(" and ( arctype_id=40 or arctype_id2=40 or arctype_id3=40) and arc_state=1",5);
		$this->assign("type_1",$type_1['item']);
		$type_2=D("arc")->arc_select_pro(" and ( arctype_id=41 or arctype_id2=41 or arctype_id3=41) and arc_state=1",5);
		$this->assign("type_2",$type_2['item']);
		$type_3=D("arc")->arc_select_pro(" and ( arctype_id=42 or arctype_id2=42 or arctype_id3=42) and arc_state=1",5);
		$this->assign("type_3",$type_3['item']);
		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=43 or arctype_id2=43 or arctype_id3=43) and arc_state=1",9);
		$this->assign("type_4",$type_4['item']);

		//flink
		$flink=D("flink")->flink_select_pro("",50,' flink_sort asc ');
		$this->assign("flink",$flink['item']);




		//side
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		//echo "dslkfjsld";
		$this->assign("seo_title","");
		$this->display();
	}

	public function new_center()
	{
		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id=2 and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);

		$type1=D("arc")->arc_select_pro(" and ( arctype_id=11 or arctype_id2=11 or arctype_id3=11) and arc_state=1",20);
		$this->assign("type1",$type1['item']);
		$type2=D("arc")->arc_select_pro(" and ( arctype_id=12 or arctype_id2=12 or arctype_id3=12) and arc_state=1",20);
		$this->assign("type2",$type2['item']);

		$type3=D("arc")->arc_select_pro(" and ( arctype_id=13 or arctype_id2=13 or arctype_id3=13) and arc_state=1",5);
		$this->assign("type3",$type3['item']);
		$type4=D("arc")->arc_select_pro(" and ( arctype_id=14 or arctype_id2=14 or arctype_id3=14) and arc_state=1",5);
		$this->assign("type4",$type4['item']);

		$type5=D("arc")->arc_select_pro(" and ( arctype_id=15 or arctype_id2=15 or arctype_id3=15) and arc_state=1",10);
		$this->assign("type5",$type5['item']);

		$type6=D("arc")->arc_select_pro(" and ( arctype_id=16 or arctype_id2=16 or arctype_id3=16) and arc_state=1",10);
		$this->assign("type6",$type6['item']);

		$type3_top=D("arc")->arc_select_pro(" and ( arctype_id=13 or arctype_id2=13 or arctype_id3=13) and arc_pic is not null and arc_state=1",1);
		$this->assign("type3_top",$type3_top['item']);
		$type5_top=D("arc")->arc_select_pro(" and ( arctype_id=14 or arctype_id2=14 or arctype_id3=14 ) and arc_pic is not null and arc_state=1",1);
		$this->assign("type4_top",$type5_top['item']);


		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1",19);
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("seo_title","新闻中心");
		$this->display();
	}

	public function jishu_center()
	{
		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id=4 and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);

		$type11=D("arc")->arc_select_pro(" and ( arctype_id=25 or arctype_id2=25 or arctype_id3=25) and arc_state=1",8);
		$this->assign("type1",$type11['item']);
		$type22=D("arc")->arc_select_pro(" and ( arctype_id=26 or arctype_id2=26 or arctype_id3=26) and arc_state=1",8);
		$this->assign("type2",$type22['item']);
		$type33=D("arc")->arc_select_pro(" and ( arctype_id=27 or arctype_id2=27 or arctype_id3=27) and arc_state=1",8);
		$this->assign("type3",$type33['item']);
		$type44=D("arc")->arc_select_pro(" and ( arctype_id=28 or arctype_id2=28 or arctype_id3=28) and arc_state=1",8);
		$this->assign("type4",$type44['item']);
		$type55=D("arc")->arc_select_pro(" and ( arctype_id=29 or arctype_id2=29 or arctype_id3=29) and arc_state=1",8);
		$this->assign("type5",$type55['item']);
		$type66=D("arc")->arc_select_pro(" and ( arctype_id=30 or arctype_id2=30 or arctype_id3=30) and arc_state=1",8);
		$this->assign("type6",$type66['item']);
		$type77=D("arc")->arc_select_pro(" and ( arctype_id=31 or arctype_id2=31 or arctype_id3=31) and arc_state=1",8);
		$this->assign("type7",$type77['item']);
		$type88=D("arc")->arc_select_pro(" and ( arctype_id=32 or arctype_id2=32 or arctype_id3=32) and arc_state=1",8);
		$this->assign("type8",$type88['item']);

		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1",16);
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("seo_title","技术中心");
		$this->display();
	}

	public function shangwu_center()
	{
		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id=6 and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);

		$type_1=D("arc")->arc_select_pro(" and ( arctype_id=39 or arctype_id2=39 or arctype_id3=39) and arc_state=1",12);
		$this->assign("type1",$type_1['item']);
		$type_2=D("arc")->arc_select_pro(" and ( arctype_id=40 or arctype_id2=40 or arctype_id3=40) and arc_state=1",12);
		$this->assign("type2",$type_2['item']);
		$type_3=D("arc")->arc_select_pro(" and ( arctype_id=41 or arctype_id2=41 or arctype_id3=41) and arc_state=1",12);
		$this->assign("type3",$type_3['item']);
		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=42 or arctype_id2=42 or arctype_id3=42) and arc_state=1",12);
		$this->assign("type4",$type_4['item']);

		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=43 or arctype_id2=43 or arctype_id3=43) and arc_state=1",9);
		$this->assign("type_4",$type_4['item']);


		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1",9);
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("seo_title","商务中心");
		$this->display();
	}



	public function spage()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("seo_title",$data["arctype_name"]."");
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



	public function spage2()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$data['arctype_parent_id']."' ");
				$this->assign("sub_arctype",$sub_arctype['item']);

				$this->assign("seo_title",$data["arctype_name"]."");
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


	public function about()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$data['arctype_parent_id']."' ");
				$this->assign("sub_arctype",$sub_arctype['item']);

				$this->assign("seo_title",$data["arctype_name"]."");
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


	public function unit()
	{
		$unit_left=D("unit")->unit_select_pro();
		$this->assign("unit_left",$unit_left["item"]);
		//print_r($unit_left['item']);

		$this->assign("seo_title","理事单位"."");
		$this->display();
	}

	public function unit_detail()
	{
		if(intval(get("unit_id"))>0)
		{
			$data=M("unit")->where("unit_id=".intval(get("unit_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$unit_left=D("unit")->unit_select_pro();
				$this->assign("unit_left",$unit_left["item"]);

				$this->assign("page_title",$data["unit_name"]."理事单位");
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



	public function unit_page()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$unit_left=D("unit")->unit_select_pro();
				$this->assign("unit_left",$unit_left["item"]);

				$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$data['arctype_parent_id']."' ");
				$this->assign("sub_arctype",$sub_arctype['item']);

				$this->assign("seo_title",$data["arctype_name"]."");
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


	public function peixun()
	{
		$list=D("peixun")->peixun_list_pro("",29);

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$zhidao=D("arc")->arc_select_pro(" and ( arctype_id=38 ) and arc_state=1",12);
		$this->assign("zhidao",$zhidao['item']);

		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=43 or arctype_id2=43 or arctype_id3=43) and arc_state=1",9);
		$this->assign("type_4",$type_4['item']);



		$this->assign("seo_title","企业培训");
    	$this->display();
	}


	public function job()
	{
		$list=D("job")->job_list_pro("",29);

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$zhidao=D("arc")->arc_select_pro(" and ( arctype_id=38 ) and arc_state=1",12);
		$this->assign("zhidao",$zhidao['item']);

		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=43 or arctype_id2=43 or arctype_id3=43) and arc_state=1",9);
		$this->assign("type_4",$type_4['item']);

		$this->assign("page_title","企业招聘");
    	$this->display();
	}

	public function resume()
	{
		$list=D("resume")->resume_list_pro("",29);

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$zhidao=D("arc")->arc_select_pro(" and ( arctype_id=38 ) and arc_state=1",12);
		$this->assign("zhidao",$zhidao['item']);

		$type_4=D("arc")->arc_select_pro(" and ( arctype_id=43 or arctype_id2=43 or arctype_id3=43) and arc_state=1",9);
		$this->assign("type_4",$type_4['item']);

		$this->assign("page_title","个人求职");
    	$this->display();
	}


	public function rencai()
	{
		$list2=D("resume")->resume_select_pro(" ",5);
		$this->assign("list2",$list2['item']);

		$list3=D("job")->job_select_pro(" ",5);
		$this->assign("list3",$list3['item']);

		$list4=D("peixun")->peixun_select_pro(" ",7);
		$this->assign("list4",$list4['item']);

		$zhidao=D("arc")->arc_select_pro(" and ( arctype_id=38 ) and arc_state=1",12);
		$this->assign("zhidao",$zhidao['item']);

		$this->assign("seo_title","人才中心");
    	$this->display();
	}


	
	

}
?>