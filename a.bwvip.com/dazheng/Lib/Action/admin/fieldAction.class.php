<?php
/**
 *    #Case		bwvip
 *    #Page		FieldAction.class.php (球场)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class fieldAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function field()
	{
		$list=D("field")->field_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场");
    	$this->display();
	}

	public function field_add()
	{

		$this->assign("page_title","添加球场");
    	$this->display();
	}

	public function field_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fieldid"]=post("fieldid");
			$data["uid"]=post("uid");
			$data["nickname"]=post("nickname");
			$data["fieldname"]=post("fieldname");
			$data["supplierid"]=post("supplierid");
			$data["countryid"]=post("countryid");
			$data["provinceid"]=post("provinceid");
			$data["cityid"]=post("cityid");
			$data["countyid"]=post("countyid");
			$data["province"]=post("province");
			$data["city"]=post("city");
			$data["county"]=post("county");
			$data["logoimg"]=post("logoimg");
			$data["fieldimg"]=post("fieldimg");
			$data["mapimg"]=post("mapimg");
			$data["seesum"]=post("seesum");
			$data["cup"]=post("cup");
			$data["fieldphone"]=post("fieldphone");
			$data["coourtphone"]=post("coourtphone");
			$data["fax"]=post("fax");
			$data["postcode"]=post("postcode");
			$data["email"]=post("email");
			$data["address"]=post("address");
			$data["partnering"]=post("partnering");
			$data["website"]=post("website");
			$data["buildtime"]=post("buildtime");
			$data["teetime"]=post("teetime");
			$data["freetime"]=post("freetime");
			$data["properties"]=post("properties");
			$data["fieldclass"]=post("fieldclass");
			$data["weekdayrackrates"]=post("weekdayrackrates");
			$data["holidayrackrates"]=post("holidayrackrates");
			$data["ordinarymember"]=post("ordinarymember");
			$data["holidaymember"]=post("holidaymember");
			$data["priceremark"]=post("priceremark");
			$data["greenordinarymember"]=post("greenordinarymember");
			$data["greenordinaryhonored"]=post("greenordinaryhonored");
			$data["greenordinaryvisitor"]=post("greenordinaryvisitor");
			$data["greenholidaymember"]=post("greenholidaymember");
			$data["greenholidayhonored"]=post("greenholidayhonored");
			$data["greenholidayvisitor"]=post("greenholidayvisitor");
			$data["caddiefeeone"]=post("caddiefeeone");
			$data["cartfeeone"]=post("cartfeeone");
			$data["caddiefeetwo"]=post("caddiefeetwo");
			$data["cartfeetwo"]=post("cartfeetwo");
			$data["facilityfee"]=post("facilityfee");
			$data["lockerfee"]=post("lockerfee");
			$data["insurance"]=post("insurance");
			$data["clubrentalfee"]=post("clubrentalfee");
			$data["bootrentalfee"]=post("bootrentalfee");
			$data["umbrellafee"]=post("umbrellafee");
			$data["bagrentalfee"]=post("bagrentalfee");
			$data["accompanyfee"]=post("accompanyfee");
			$data["trainerfee"]=post("trainerfee");
			$data["visitantlounge"]=post("visitantlounge");
			$data["cabaretfacility"]=post("cabaretfacility");
			$data["diningfacility"]=post("diningfacility");
			$data["leisurefacility"]=post("leisurefacility");
			$data["fieldcontent"]=post("fieldcontent");
			$data["designer"]=post("designer");
			$data["fieldherb"]=post("fieldherb");
			$data["herb"]=post("herb");
			$data["standardpar"]=post("standardpar");
			$data["length"]=post("length");
			$data["area"]=post("area");
			$data["facility"]=post("facility");
			$data["airport"]=post("airport");
			$data["citycenter"]=post("citycenter");
			$data["travelway"]=post("travelway");
			$data["aroundviews"]=post("aroundviews");
			$data["star"]=post("star");
			$data["pricein"]=post("pricein");
			$data["white"]=post("white");
			$data["gold"]=post("gold");
			$data["blue"]=post("blue");
			$data["red"]=post("red");
			$data["black"]=post("black");
			$data["handicpa"]=post("handicpa");
			$data["par"]=post("par");
			$data["map_x"]=post("map_x");
			$data["map_y"]=post("map_y");
			$data["adduser"]=post("adduser");
			$data["addtime"]=time();
			
			$list=M("field","pre_common_")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/field/field'));
			}
			else
			{				
				$this->error("添加失败",U('admin/field/field'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function field_edit()
	{
		if(intval(get("id"))>0)
		{
			$data= M("field","pre_common_")->where("id=".intval(get("id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改球场");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["id"]=post("id");
			$data["fieldid"]=post("fieldid");
			$data["uid"]=post("uid");
			$data["nickname"]=post("nickname");
			$data["fieldname"]=post("fieldname");
			$data["supplierid"]=post("supplierid");
			$data["countryid"]=post("countryid");
			$data["provinceid"]=post("provinceid");
			$data["cityid"]=post("cityid");
			$data["countyid"]=post("countyid");
			$data["province"]=post("province");
			$data["city"]=post("city");
			$data["county"]=post("county");
			$data["logoimg"]=post("logoimg");
			$data["fieldimg"]=post("fieldimg");
			$data["mapimg"]=post("mapimg");
			$data["seesum"]=post("seesum");
			$data["cup"]=post("cup");
			$data["fieldphone"]=post("fieldphone");
			$data["coourtphone"]=post("coourtphone");
			$data["fax"]=post("fax");
			$data["postcode"]=post("postcode");
			$data["email"]=post("email");
			$data["address"]=post("address");
			$data["partnering"]=post("partnering");
			$data["website"]=post("website");
			$data["buildtime"]=post("buildtime");
			$data["teetime"]=post("teetime");
			$data["freetime"]=post("freetime");
			$data["properties"]=post("properties");
			$data["fieldclass"]=post("fieldclass");
			$data["weekdayrackrates"]=post("weekdayrackrates");
			$data["holidayrackrates"]=post("holidayrackrates");
			$data["ordinarymember"]=post("ordinarymember");
			$data["holidaymember"]=post("holidaymember");
			$data["priceremark"]=post("priceremark");
			$data["greenordinarymember"]=post("greenordinarymember");
			$data["greenordinaryhonored"]=post("greenordinaryhonored");
			$data["greenordinaryvisitor"]=post("greenordinaryvisitor");
			$data["greenholidaymember"]=post("greenholidaymember");
			$data["greenholidayhonored"]=post("greenholidayhonored");
			$data["greenholidayvisitor"]=post("greenholidayvisitor");
			$data["caddiefeeone"]=post("caddiefeeone");
			$data["cartfeeone"]=post("cartfeeone");
			$data["caddiefeetwo"]=post("caddiefeetwo");
			$data["cartfeetwo"]=post("cartfeetwo");
			$data["facilityfee"]=post("facilityfee");
			$data["lockerfee"]=post("lockerfee");
			$data["insurance"]=post("insurance");
			$data["clubrentalfee"]=post("clubrentalfee");
			$data["bootrentalfee"]=post("bootrentalfee");
			$data["umbrellafee"]=post("umbrellafee");
			$data["bagrentalfee"]=post("bagrentalfee");
			$data["accompanyfee"]=post("accompanyfee");
			$data["trainerfee"]=post("trainerfee");
			$data["visitantlounge"]=post("visitantlounge");
			$data["cabaretfacility"]=post("cabaretfacility");
			$data["diningfacility"]=post("diningfacility");
			$data["leisurefacility"]=post("leisurefacility");
			$data["fieldcontent"]=post("fieldcontent");
			$data["designer"]=post("designer");
			$data["fieldherb"]=post("fieldherb");
			$data["herb"]=post("herb");
			$data["standardpar"]=post("standardpar");
			$data["length"]=post("length");
			$data["area"]=post("area");
			$data["facility"]=post("facility");
			$data["airport"]=post("airport");
			$data["citycenter"]=post("citycenter");
			$data["travelway"]=post("travelway");
			$data["aroundviews"]=post("aroundviews");
			$data["star"]=post("star");
			$data["pricein"]=post("pricein");
			$data["white"]=post("white");
			$data["gold"]=post("gold");
			$data["blue"]=post("blue");
			$data["red"]=post("red");
			$data["black"]=post("black");
			$data["handicpa"]=post("handicpa");
			$data["par"]=post("par");
			$data["map_x"]=post("map_x");
			$data["map_y"]=post("map_y");
			$data["adduser"]=post("adduser");
			
			$list=M("field","pre_common_")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/field/field'));
			}
			else
			{
				$this->error("修改失败",U('admin/field/field'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function field_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field","pre_common_")->where("id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_field set field_state=1 where id=".$ids_arr[$i]." ");
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

	public function field_detail()
	{
		if(intval(get("id"))>0)
		{
			$data=M("field","pre_common_")->where("id=".intval(get("id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["field_name"]."球场");
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


	

}
?>