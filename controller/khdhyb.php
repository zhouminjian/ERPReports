<?php
	//读取客户大货样布
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$field = "Qtys";
		$order = "desc";
		if(isset($_GET['field'])&&isset($_GET['order'])){
			$field = $_GET['field'];
			$order = $_GET['order'];
		}
		$orderby = "$field $order";
		$kh = $_SESSION['kh'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		$kh_array=array();
		$where = "";
		if($kh){
			$kh_array = explode(',',$kh);
			$where = "and (ct.CustomerID='$kh_array[0]'";
			for($index=1;$index<count($kh_array);$index++){
				$where .=" or ct.CustomerID='$kh_array[$index]'";
			}
			$where .= ')';
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(select p.*,ROW_NUMBER() OVER(Order by p.$orderby) as RowId from(
select t1.*,ISNULL(t2.OrderNums,0)OrderNums,ISNULL(t2.Qtys,0)Qtys,ISNULL(t3.Cells,0)Cells,ISNULL(t3.CRolls,0)CRolls,
ISNULL(t4.ProSheets,0)ProSheets,ISNULL(t4.PRolls,0)PRolls,ISNULL(t6.DouDistributes,0)DouDistributes,ISNULL(t6.DRolls,0)DRolls,ISNULL(t5.AOrder,0)AOrder,ISNULL(t5.ARolls,0)ARolls
from 
(select ct.NameC from Dye.OrderMain om,Dye.p_Customer ct
 where om.CustomerID=ct.CustomerID and om.SheetDate between '$startYear' and '$endYear' $where
 group by ct.NameC
)t1
left join 
(select x.NameC,count(x.SheetSeries)OrderNums,sum(x.oqty)Qtys from(
select ct.NameC,om.SheetSeries,sum(oc.OrderQty) oqty
from (select * from Dye.OrderMain where SheetKind=30 and SheetDate between '$startYear' and '$endYear')om
join Dye.OrderColor oc
on oc.SheetSeries=om.SheetSeries
join Dye.p_Customer ct
on ct.CustomerID=om.CustomerID
group by ct.NameC,om.SheetSeries)x
group by x.NameC)t2
on t1.NameC=t2.NameC
left join
(select x.NameC,count(x.SheetSeries)Cells,sum(x.oqty)CRolls from
	(select ct.NameC,om.SheetSeries,sum(oc.OrderQty) oqty
	from Dye.p_Customer ct join (select * from Dye.OrderMain where BusinessKind=10 and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on ct.CustomerID=om.CustomerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY ct.NameC,om.SheetSeries)x
group by x.NameC)t3
on t3.NameC=t1.NameC
left JOIN
(select x.NameC,count(x.SheetSeries)ProSheets,sum(x.oqty)PRolls from
	(select ct.NameC,om.SheetSeries,sum(oc.OrderQty) oqty
	from Dye.p_Customer ct join (select * from Dye.OrderMain where BusinessKind=0 and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on ct.CustomerID=om.CustomerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY ct.NameC,om.SheetSeries)x
group by x.NameC)t4
on t4.NameC=t1.NameC
left join
(select x.NameC,count(x.SheetSeries)DouDistributes,sum(x.oqty)DRolls from
	(select ct.NameC,om.SheetSeries,sum(oc.OrderQty) oqty
	from Dye.p_Customer ct join (select * from Dye.OrderMain where BusinessKind=30 and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on ct.CustomerID=om.CustomerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY ct.NameC,om.SheetSeries)x
group by x.NameC)t6
on t6.NameC=t1.NameC
left join
(select x.NameC,count(x.SheetSeries)AOrder,sum(x.oqty)ARolls from
	(select ct.NameC,om.SheetSeries,sum(oc.OrderQty) oqty
	from Dye.p_Customer ct join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on ct.CustomerID=om.CustomerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY ct.NameC,om.SheetSeries)x
group by x.NameC)t5
on t5.NameC=t1.NameC
)p)s ";
			$_SESSION['SQL'] = $selectSQL;
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
				array_push($array,array("NameC"=>iconv("GBK", "UTF-8//IGNORE",$row['NameC']),"OrderNums"=>$row['OrderNums'],"Qtys"=>$row['Qtys'],"Cells"=>$row['Cells'],"CRolls"=>$row['CRolls'],"ProSheets"=>$row['ProSheets'],"PRolls"=>$row['PRolls'],"DouDistributes"=>$row['DouDistributes'],"DRolls"=>$row['DRolls'],"AOrder"=>$row['AOrder'],"ARolls"=>$row['ARolls']));
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