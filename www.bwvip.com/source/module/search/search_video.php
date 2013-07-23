<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: search_video.php 22166 2012/3/17 00:03:44Z Angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('NOROBOT', TRUE);
require_once libfile('function/home');



$_G['setting']['search']['video']['searchctrl'] = 100;


$srchmod = 100;

$cachelife_time = 300;		// Life span for cache of searching in specified range of time
$cachelife_text = 3600;		// Life span for cache of text searching

$srchtype = empty($_G['gp_srchtype']) ? '' : trim($_G['gp_srchtype']);
$searchid = isset($_G['gp_searchid']) ? intval($_G['gp_searchid']) : 0;


$srchtxt = $_G['gp_srchtxt'];
$keyword = isset($srchtxt) ? htmlspecialchars(trim($srchtxt)) : '';

if(!submitcheck('searchsubmit', 1)) {

	include template('search/video');

} else {
	$orderby = in_array($_G['gp_orderby'], array('dateline', 'replies', 'views')) ? $_G['gp_orderby'] : 'lastpost';
	$ascdesc = isset($_G['gp_ascdesc']) && $_G['gp_ascdesc'] == 'asc' ? 'asc' : 'desc';


	if(!empty($searchid)) {

		$page = max(1, intval($_G['gp_page']));
		$start_limit = ($page - 1) * $_G['tpp'];

		$index = DB::fetch_first("SELECT searchstring, keywords, num, ids FROM ".DB::table('common_searchindex')." WHERE searchid='$searchid' AND srchmod='$srchmod'");

		if(!$index) {
			showmessage('search_id_invalid');
		}

		$keyword = htmlspecialchars($index['keywords']);
		$keyword = $keyword != '' ? str_replace('+', ' ', $keyword) : '';

		$index['keywords'] = rawurlencode($index['keywords']);

		$albumlist = array();
		$maxalbum = $nowalbum = 0;
		$query = DB::query("SELECT v.`uid`,v.`vpid`,v.`title`,vp.* FROM ".DB::table('home_video')." as v LEFT JOIN ".DB::table('home_videopath')." as vp ON vp.vpid=v.vpid  WHERE v.vpid IN($index[ids]) ORDER BY v.dateline DESC LIMIT $start_limit, $_G[tpp]");
		while ($value = DB::fetch($query)) {
			$value['images'] = str_replace('../','',$value['images']);
			$videolist[$value['vpid']] = $value;
		}

		$multipage = multi($index['num'], $_G['tpp'], $page, "search.php?mod=video&searchid=$searchid&orderby=$orderby&ascdesc=$ascdesc&searchsubmit=yes");
		$url_forward = 'search.php?mod=video&'.$_SERVER['QUERY_STRING'];
		include template('search/video');

	}else{

		$searchstring = 'video|title|'.addslashes($srchtxt);
		$searchindex = array('id' => 0, 'dateline' => '0');

		$query = DB::query("SELECT searchid, dateline,
			('".$_G['setting']['search']['video']['searchctrl']."'<>'0' AND ".(empty($_G['uid']) ? "useip='$_G[clientip]'" : "uid='$_G[uid]'")." AND $_G[timestamp]-dateline<'".$_G['setting']['search']['blog']['searchctrl']."') AS flood,
			(searchstring='$searchstring' AND expiration>'$_G[timestamp]') AS indexvalid
			FROM ".DB::table('common_searchindex')."
			WHERE srchmod='$srchmod' AND ('".$_G['setting']['search']['video']['searchctrl']."'<>'0' AND ".(empty($_G['uid']) ? "useip='$_G[clientip]'" : "uid='$_G[uid]'")." AND $_G[timestamp]-dateline<".$_G['setting']['search']['video']['searchctrl'].") OR (searchstring='$searchstring' AND expiration>'$_G[timestamp]')
			ORDER BY flood");


		while($index = DB::fetch($query)) {
			if($index['indexvalid'] && $index['dateline'] > $searchindex['dateline']) {
				$searchindex = array('id' => $index['searchid'], 'dateline' => $index['dateline']);
				break;
			} elseif($_G['adminid'] != '1' && $index['flood']) {
				showmessage('search_ctrl', 'search.php?mod=video', array('searchctrl' => $_G['setting']['search']['video']['searchctrl']));
			}
		}

		if($searchindex['id']) {
			$searchid = $searchindex['id'];

		} else {

			!($_G['group']['exempt'] & 2) && checklowerlimit('search');

			if(!$srchtxt && !$srchuid && !$srchuname) {
				dheader('Location: search.php?mod=video');
			}

			if($_G['adminid'] != '1' && $_G['setting']['search']['blog']['maxspm']) {
				if((DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_searchindex')." WHERE srchmod='$srchmod' AND dateline>'$_G[timestamp]'-60")) >= $_G['setting']['search']['blog']['maxspm']) {
					showmessage('search_toomany', 'search.php?mod=blog', array('maxspm' => $_G['setting']['search']['blog']['maxspm']));
				}
			}

			$num = $ids = 0;
			$_G['setting']['search']['video']['maxsearchresults'] = 500;
			list($srchtxt, $srchtxtsql) = searchkey($keyword, " v.title LIKE '%{text}%'", true);


			$query = DB::query("SELECT v.`vpid` FROM ".DB::table('home_video')." as v LEFT JOIN ".DB::table('home_videopath')." as vp ON vp.vpid=v.vpid  WHERE 1 $srchtxtsql ORDER BY v.vid DESC LIMIT ".$_G['setting']['search']['video']['maxsearchresults']);
			while($video = DB::fetch($query)) {
				$ids .= ','.$video['vpid'];
				$num++;
			}
			DB::free_result($query);

			$keywords = str_replace('%', '+', $srchtxt);
			$expiration = TIMESTAMP + $cachelife_text;

			DB::query("INSERT INTO ".DB::table('common_searchindex')." (srchmod, keywords, searchstring, useip, uid, dateline, expiration, num, ids)
					VALUES ('$srchmod', '$keywords', '$searchstring', '$_G[clientip]', '$_G[uid]', '$_G[timestamp]', '$expiration', '$num', '$ids')");
			$searchid = DB::insert_id();

			!($_G['group']['exempt'] & 2) && updatecreditbyaction('search');
		}

		dheader("location: search.php?mod=video&searchid=$searchid&searchsubmit=yes&kw=".urlencode($keyword));
	}
}
