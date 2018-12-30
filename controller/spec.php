<?php
	//订单规格分析
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$sheetkind = $_SESSION['sheetkind'];
		$businesskind = $_SESSION['businesskind'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		if($sheetkind==1){
			$sheetkind = " and SheetKind=0 ";
		}
		else if($sheetkind==2){
			$sheetkind = " and SheetKind=30 ";
		}
		else{
			$sheetkind = "";
		}
		if($businesskind==1){
			$businesskind = " and BusinessKind=0 ";
		}
		else if($businesskind==2){
			$businesskind = " and BusinessKind not in(0,30) ";
		}
		else if($businesskind==4){
			$businesskind = " and BusinessKind =30 ";
		}
		else{
			$businesskind = "";
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.SpecNum desc) RowId FROM
(select Spec,count(SheetSeries)SpecNum from Dye.OrderMain where SheetDate BETWEEN '$startYear'and'$endYear' $sheetkind $businesskind group by Spec)x
)s ";
			$result = sqlsrv_query($conn,$selectSQL,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));//查询所有提取count
			$count = sqlsrv_num_rows($result);
			//计算起始、结束位置
			$startres = ($page-1)*$limit+1;
			$limit = $limit*$page;
			$startres = "where s.RowId between $startres and $limit";
			$selectSQL .= $startres;//拼接页码
			$result = sqlsrv_query($conn,$selectSQL);//根据页码查询返回的结果
			$array = array();
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				array_push($array,array("Spec"=>iconv("GBK", "UTF-8//IGNORE",$row['Spec']),"SpecNum"=>$row['SpecNum']));
			}
			$resultJson=Array("code"=>0,"msg"=>"查询成功","count"=>$count,"data"=>$array);
			$conClass->sqlClose($conn);
		}
		catch(Exception $e){
			$resultJson=Array("code"=>135,"msg"=>$e->getMessage());
		}
	}
	echo json_encode($resultJson);
?>