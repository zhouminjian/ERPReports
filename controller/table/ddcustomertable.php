<?php
	//读取所有客户规格
	include_once "../../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$datetype = $_SESSION['datetype'];
		$sheetkind = $_SESSION['sheetkind'];
		$businesskind = $_SESSION['businesskind'];
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
			if($datetype == '2'){
				$startMon = $_SESSION['startmon'].'-01';//起始月份
				$endMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//结束月份
				$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.MRolls desc) RowId from
(select p.NameC,Sum(c.OrderQty) MRolls
from Dye.OrderMain m ,Dye.OrderColor c, Dye.p_Customer p 
where SheetDate between '$startMon'and '$endMon' $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and m.CustomerID=p.CustomerID 
and SheetType='10'  and SheetStatus='0' group by p.NameC)x
)s ";
			}
			else{
				$startYear = $_SESSION['startyear'].'-01-01';//起始年份
				$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
				$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.MRolls desc) RowId from
(select p.NameC,Sum(c.OrderQty) MRolls
from Dye.OrderMain m ,Dye.OrderColor c, Dye.p_Customer p 
where SheetDate between '$startYear'and '$endYear' $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and m.CustomerID=p.CustomerID 
and SheetType='10'  and SheetStatus='0' group by p.NameC)x
)s ";
			}
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
				array_push($array,array("NameC"=>iconv("GBK", "UTF-8//IGNORE",$row['NameC']),"MRolls"=>$row['MRolls']));
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