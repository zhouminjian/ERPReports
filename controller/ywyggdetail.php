<?php
	//读取对应规格及个数
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$ywy = $_SESSION['info']['EmpID'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(
select p.*,ROW_NUMBER() OVER(Order by SpecNum desc) as RowId from
(select x.Spec,count(x.Spec)SpecNum from
	(select em.Name,oc.SheetSeries,oc.Spec
	from Dye.h_Employee em
	join Dye.OrderMain om
	on em.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	where om.SheetDate between '$startYear' and '$endYear' and em.EmpID='$ywy'
	group by em.Name,oc.SheetSeries,oc.Spec)x
group by x.Spec)p)s ";
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