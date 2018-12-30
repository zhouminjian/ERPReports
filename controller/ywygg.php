<?php
	//读取所有业务员规格
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$ywy = $_SESSION['ywy'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		$ywy_array = array();
		$where = "";
		if($ywy){
			$ywy_array = explode(',',$ywy);
			$where = "and (em.EmpID='$ywy_array[0]'";
			for($index=1;$index<count($ywy_array);$index++){
				$where .=" or em.EmpID='$ywy_array[$index]'";
			}
			$where .=")";
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(
select p.*,ROW_NUMBER() OVER(Order by SpecNum desc) as RowId from
(select s.EmpID,s.Name,count(s.Spec)SpecNum from
(select x.EmpID,x.Name,x.Spec from
	(select em.EmpID,em.Name,oc.SheetSeries,oc.Spec
		from Dye.h_Employee em
		join Dye.OrderMain om
		on em.EmpID=om.SalerID
		join Dye.OrderColor oc
		on oc.SheetSeries=om.SheetSeries
		where om.SheetDate between '$startYear' and '$endYear' $where
		group by em.EmpID,em.Name,oc.SheetSeries,oc.Spec)x
	group by x.EmpID,x.Name,x.Spec)s
group by s.EmpID,s.Name)p)s ";
			$result = sqlsrv_query($conn,$selectSQL,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));//查询所有提取count
			$count = sqlsrv_num_rows($result);
			//计算起始、结束位置
			$startres = ($page-1)*$limit+1;
			$limit = $limit*$page;
			$startres = "where s.RowId between $startres and $limit ORDER BY s.SpecNum desc";
			$selectSQL .= $startres;//拼接页码
			$result = sqlsrv_query($conn,$selectSQL);//根据页码查询返回的结果
			$array = array();
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				array_push($array,array("EmpID"=>$row['EmpID'],"Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name']),"SpecNum"=>$row['SpecNum']));
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