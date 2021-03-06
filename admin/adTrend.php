<?php
	session_start();
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	
	if($_SESSION['username'] == null)
	{
		echo "<script>alert('请先登录！');location.href='index.php';</script>";
	}

	include("../global.inc.php");
	r_require_once("/lib/MultiActions.php");	

	//默认情况时
	function _default()
	{
		r_require_once("/smarty/MySmarty.php");
		r_include_once("/DAL/public/paggingbar.php");
		r_require_once("/DAL/AdTrend.php");
		
		$tpl = new MySmarty("admin");		
		$adTrend = new AdTrend();
		
		$tpl->assign("siteTitle","后台管理系统");
		
		$pageNum = getRequestIntParam('page_num', 1);
		$pageSize = 15;
		$totalRecords = $adTrend->getTotalbyAdTrend();
		$totalPages = intval($totalRecords / $pageSize);
		$totalPages += ($totalRecords % $pageSize == 0 ? 0 : 1);
		if($pageNum > $totalPages) $pageNum = $totalPages;
		if($totalRecords>0)
			$tpl->assign('adTrend', $adTrend->getbyAdTrend($pageNum,$pageSize,0));
		
		$tpl->assign('paggingbar', genPaggingbar('adTrend.php',$pageNum,$totalPages));
		$tpl->display("showAdTrend.html");
	}
	
	//添加、修改广告页面
	function _new() {
		r_include_once("/smarty/MySmarty.php");
		r_require_once("/DAL/AdTrend.php");

		$id = getRequestIntParam('id', 0);
		
		$tpl = new MySmarty('admin');
		$adTrend = new AdTrend();
		
		$tpl->assign("siteTitle","后台管理系统");		
		$tpl->assign("adTrend",$adTrend->getAdTrendbyId($id));
		$tpl->display("editAdTrend.html");
	}
	
	//添加、修改广告操作
	function _save() {
		r_require_once("/DAL/AdTrend.php");
		
		$id = getRequestIntParam('id', 0);
		$title = getRequestStringParam('title', '');
		$image = getRequestStringParam('image', '');
		$hasimg = getRequestStringParam('hasimg', '');
		$contenturl = getRequestStringParam('contenturl', '');
		$checked = getRequestIntParam('checked',0);
				
		$adTrend = new AdTrend();
		$newimg = substr(uploadImages('image'),1);		
		
		if ($id == 0) {
			$cc = $adTrend->insertAdTrend($title,$newimg,$contenturl,$checked);
		} else {
			if($newimg=="")
				$imgurl=$hasimg;
			else
				$imgurl=$newimg;

			$cc = $adTrend->updateAdTrend($id,$title,$imgurl,$contenturl,$checked);
		} 
		
		if ($cc)
        	echo "<script>alert('操作成功！');window.location.href='adTrend.php';</script>";
    	else
        	echo "<script>alert('操作失败！');window.location.href='adTrend.php';</script>";
	}
	
	//删除广告操作
	function _delete() {
		r_require_once("/DAL/AdTrend.php");
		$adTrend = new AdTrend();
		$chk1=$_POST['chk1'];
		if ($chk1!="" or count($chk1)!=0) {
			for ($i=0;$i<count($chk1);$i++){
				$cc = $adTrend->deleteAdTrend($chk1[$i]);
			}
			echo "<script>alert('操作成功！');window.location.href='adTrend.php';</script>";
		}
		else
			echo "<script>alert('操作失败！');window.location.href='adTrend.php';</script>";
	}
?>