<?php
	//业务员样布数据
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
			$where = "where emp.EmpID='$ywy_array[0]'";
			for($index=1;$index<count($ywy_array);$index++){
				$where .=" or emp.EmpID='$ywy_array[$index]'";
			}
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(
select t3.*,isNULL(t4.CustomerNums,0)CustomerNums,t5.MaxQty,t6.MaxOrder,ROW_NUMBER() OVER(Order by t3.QTY desc) AS RowId from 
(select t1.Name,count(t1.Name) OrderNums,sum(t1.QTY) Qty
from(
	select emp.Name,om.SheetSeries,SUM(oc.OrderQty) QTY
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear')om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	$where
	GROUP BY om.SheetSeries,emp.Name) t1
GROUP BY t1.Name)t3
left join
(select t2.Name,count(t2.CustomerID) CustomerNums
from(
	select emp.Name,ct.CustomerID
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear') om
	on emp.EmpID=om.SalerID
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	GROUP BY emp.Name,ct.CustomerID) t2
GROUP BY t2.Name
)t4
on t3.Name=t4.Name
left join
(select qm.Name,qm.NameC MaxQty from 
	(select emp.Name,ct.NameC,SUM(oc.OrderQty) QTY
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear') om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	GROUP BY ct.NameC,emp.Name)qm 
where qm.QTY=(
	select MAX(x.QTY) from 
		(select emp.Name,ct.NameC,SUM(oc.OrderQty) QTY
		from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear') om
		on emp.EmpID=om.SalerID
		join Dye.OrderColor oc
		on oc.SheetSeries=om.SheetSeries
		join Dye.p_Customer ct
		on ct.CustomerID=om.CustomerID
		GROUP BY ct.NameC,emp.Name)x
		where x.Name = qm.Name)
)t5
on t5.Name=t3.Name
left join
(select Name,NameC MaxOrder from(
	select od.Name,od.NameC,od.Orders,Row_Number() Over(Partition By od.Name Order By od.NameC) Rn from 
	(select emp.Name,ct.NameC,count(om.SheetSeries) Orders
		from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear') om
		on emp.EmpID=om.SalerID
		left join Dye.p_Customer ct
		on ct.CustomerID=om.CustomerID
		GROUP BY ct.NameC,emp.Name)od
	where od.Orders=(
	select MAX(x.Orders) from
		(select emp.Name,ct.NameC,count(om.SheetSeries) Orders
			from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=30 and SheetDate between'$startYear' and '$endYear') om
			on emp.EmpID=om.SalerID
			left join Dye.p_Customer ct
			on ct.CustomerID=om.CustomerID
			GROUP BY ct.NameC,emp.Name)x
	where x.Name=od.Name)
)y
where y.Rn=1
)t6
on t6.Name=t3.Name)s ";
			$_SESSION['SQL'] = $selectSQL;
			$result = sqlsrv_query($conn,$selectSQL,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));//查询所有提取count
			$count = sqlsrv_num_rows($result);
			//计算起始、结束位置
			$startres = ($page-1)*$limit+1;
			$limit = $limit*$page;
			$startres = "where s.RowId between $startres and $limit ORDER BY s.QTY desc";
			$selectSQL .= $startres;//拼接页码
			$result = sqlsrv_query($conn,$selectSQL);//根据页码查询返回的结果
			$array = array();
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				array_push($array,array("Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name']),"OrderNums"=>$row['OrderNums'],"Qty"=>$row['Qty'],"CustomerNums"=>$row['CustomerNums'],"MaxQty"=>iconv("GBK", "UTF-8//IGNORE",$row['MaxQty']),"MaxOrder"=>iconv("GBK", "UTF-8//IGNORE",$row['MaxOrder'])));
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