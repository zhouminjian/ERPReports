<?php
	//业务员大货数据
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$field = "QTY";
		$order = "desc";
		if(isset($_GET['field'])&&isset($_GET['order'])){
			$field = $_GET['field'];
			$order = $_GET['order'];
		}
		$orderby = "$field $order";
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
			$selectSQL = "select s.* from(select x.*,ROW_NUMBER() OVER(Order by x.$orderby) RowId from(
select t3.*,isNULL(t4.CustomerNums,0)CustomerNums,isNULL(t5.ARolls,0)ARolls,isNULL(t6.OutQty,0)OutQty,t7.MaxQty,t8.MaxOrder,isNULL(t9.SellCts,0)SellCts,
isNULL(t10.Cells,0)Cells,isNULL(t11.CRolls,0)CRolls,isNULL(t12.ProcessCts,0)ProcessCts,isNULL(t13.ProSheets,0)ProSheets,isNULL(t14.PRolls,0)PRolls,
ISNULL(t16.DouDisCts,0)DouDisCts,ISNULL(t17.DouDistributes,0)DouDistributes,ISNULL(t18.DRolls,0)DRolls,isNULL(t15.OutGrayQty,0)OutGrayQty
from
(select t1.Name,count(t1.Name) OrderNums,sum(t1.QTY) Qty
from(select emp.Name,om.SheetSeries,SUM(oc.OrderQty) QTY
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	$where
	GROUP BY om.SheetSeries,emp.Name) t1
GROUP BY t1.Name
)t3 left join
(
select t2.Name,count(t2.CustomerID) CustomerNums
from(
	select emp.Name,ct.CustomerID
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear') om
	on emp.EmpID=om.SalerID
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	GROUP BY emp.Name,ct.CustomerID) t2
GROUP BY t2.Name
)t4
on t3.Name=t4.Name
left join
(
select em.Name,sum(isnull(pi.LengthQty,0)) ARolls
From Dye.ProdIn pi,Dye.h_Employee em
where pi.kind in (0,1,2,3,4) and isnull(pi.CheckNo,'')<>'' and em.EmpID=pi.SalerID and pi.SheetDate between'$startYear' and '$endYear'
group by em.Name
)t5
on t5.Name=t3.Name
left join 
(
select em.Name,sum(po.DlvLengthQty) OutQty 
from Dye.ProdOut po,Dye.h_Employee em where em.EmpID=po.SalerID and po.SheetDate between'$startYear' and '$endYear'
group by em.Name) t6
on t6.Name=t3.Name
left join
(select qm.Name,qm.NameC MaxQty from 
	(select emp.Name,ct.NameC,SUM(oc.OrderQty) QTY
	from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear') om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	join Dye.p_Customer ct
	on ct.CustomerID=om.CustomerID
	GROUP BY ct.NameC,emp.Name)qm 
where qm.QTY=(
	select MAX(x.QTY) from 
		(select emp.Name,ct.NameC,SUM(oc.OrderQty) QTY
		from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear') om
		on emp.EmpID=om.SalerID
		join Dye.OrderColor oc
		on oc.SheetSeries=om.SheetSeries
		join Dye.p_Customer ct
		on ct.CustomerID=om.CustomerID
		GROUP BY ct.NameC,emp.Name)x
		where x.Name = qm.Name)
)t7
on t7.Name=t3.Name
left join
(select Name,NameC MaxOrder from(
	select od.Name,od.NameC,od.Orders,Row_Number() Over(Partition By od.Name Order By od.NameC desc) Rn from 
	(select emp.Name,ct.NameC,count(om.SheetSeries) Orders
		from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear') om
		on emp.EmpID=om.SalerID
		left join Dye.p_Customer ct
		on ct.CustomerID=om.CustomerID
		GROUP BY ct.NameC,emp.Name)od
	where od.Orders=(
	select MAX(x.Orders) from
		(select emp.Name,ct.NameC,count(om.SheetSeries) Orders
			from Dye.h_Employee emp join (select * from Dye.OrderMain where SheetKind=0 and SheetDate between'$startYear' and '$endYear') om
			on emp.EmpID=om.SalerID
			left join Dye.p_Customer ct
			on ct.CustomerID=om.CustomerID
			GROUP BY ct.NameC,emp.Name)x
	where x.Name=od.Name)
)y
where y.Rn=1
)t8
on t8.Name=t3.Name
left JOIN
(select em.Name,count(om.CustomerID) SellCts
	from( 
		(select SalerID,CustomerID
		from Dye.OrderMain where BusinessKind not in(0,30) and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,CustomerID)om
		join Dye.h_Employee em on om.SalerID=em.EmpID)
	group by em.Name
)t9
on t9.Name=t3.Name
left join 
(select x.Name,count(x.SheetSeries)Cells from
	(select emp.Name,om.SheetSeries
	from Dye.h_Employee emp join (select * from Dye.OrderMain where BusinessKind not in(0,30) and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY emp.Name,om.SheetSeries)x
group by x.Name
)t10
on t10.Name=t3.Name
left join
(select em.Name,sum(oc.OrderQty) CRolls
	from( 
		(select SalerID,SheetSeries
		from Dye.OrderMain where BusinessKind not in(0,30) and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,SheetSeries)om
		join Dye.h_Employee em on om.SalerID=em.EmpID
		join Dye.OrderColor oc on oc.SheetSeries=om.SheetSeries)
	group by em.Name
)t11
on t11.Name=t3.Name
left JOIN
(select em.Name,count(om.CustomerID) ProcessCts
	from( 
		(select SalerID,CustomerID
		from Dye.OrderMain where BusinessKind=0 and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,CustomerID)om
		join Dye.h_Employee em on om.SalerID=em.EmpID)
	group by em.Name
)t12
on t12.Name=t3.Name
left join 
(select x.Name,count(x.SheetSeries)ProSheets from
	(select emp.Name,om.SheetSeries
	from Dye.h_Employee emp join (select * from Dye.OrderMain where BusinessKind=0 and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY emp.Name,om.SheetSeries)x
group by x.Name
)t13
on t13.Name=t3.Name
left join
(select em.Name,sum(oc.OrderQty) PRolls
	from( 
		(select SalerID,SheetSeries
		from Dye.OrderMain where BusinessKind=0 and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,SheetSeries)om
		join Dye.h_Employee em on om.SalerID=em.EmpID
		join Dye.OrderColor oc on oc.SheetSeries=om.SheetSeries)
	group by em.Name
)t14
on t14.Name=t3.Name
left JOIN
(select em.Name,count(om.CustomerID) DouDisCts
	from( 
		(select SalerID,CustomerID
		from Dye.OrderMain where BusinessKind=30 and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,CustomerID)om
		join Dye.h_Employee em on om.SalerID=em.EmpID)
	group by em.Name
)t16
on t16.Name=t3.Name
left join 
(select x.Name,count(x.SheetSeries)DouDistributes from
	(select emp.Name,om.SheetSeries
	from Dye.h_Employee emp join (select * from Dye.OrderMain where BusinessKind=30 and SheetKind=0 and SheetDate between'$startYear' and '$endYear')om
	on emp.EmpID=om.SalerID
	join Dye.OrderColor oc
	on oc.SheetSeries=om.SheetSeries
	GROUP BY emp.Name,om.SheetSeries)x
group by x.Name
)t17
on t17.Name=t3.Name
left join
(select em.Name,sum(oc.OrderQty) DRolls
	from( 
		(select SalerID,SheetSeries
		from Dye.OrderMain where BusinessKind=30 and SheetKind=0 and SheetDate between'$startYear' and '$endYear'
		group by SalerID,SheetSeries)om
		join Dye.h_Employee em on om.SalerID=em.EmpID
		join Dye.OrderColor oc on oc.SheetSeries=om.SheetSeries)
	group by em.Name
)t18
on t18.Name=t3.Name
left join
(select x.Name,sum(x.LengthQty) OutGrayQty
	from(select * from (Select a.SheetDate,b.LengthQty,e.Name from Dye.GrayOut a join
	Dye.GrayOutDetail b on a.SheetSeries=b.SheetSeries 
	left join Dye.ordercolor c on b.CheckNo=c.CheckNo
	left join Dye.OrderMain o on c.SheetSeries=o.SheetSeries
	LEFT JOIN Dye.h_Employee e on e.EmpID=a.SalerID where a.GrayKindID<>4
	union all
	 select a.SheetDate,b.LengthQty,c.Name
	 from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b on a.SheetSeries=b.SheetSeries
	 left join Dye.h_Employee c on a.FromSalerID=c.EmpID
	)a)x where x.SheetDate between '$startYear' and '$endYear'
group by x.Name
)t15
on t15.Name=t3.Name)x)s ";
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
				array_push($array,array("Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name']),"OrderNums"=>$row['OrderNums'],"Qty"=>$row['Qty'],"CustomerNums"=>$row['CustomerNums'],"ARolls"=>$row['ARolls'],"OutQty"=>$row['OutQty'],"MaxQty"=>iconv("GBK", "UTF-8//IGNORE",$row['MaxQty']),"MaxOrder"=>iconv("GBK", "UTF-8//IGNORE",$row['MaxOrder']),"SellCts"=>$row['SellCts'],"Cells"=>$row['Cells'],"CRolls"=>$row['CRolls'],"ProcessCts"=>$row['ProcessCts'],"ProSheets"=>$row['ProSheets'],"PRolls"=>$row['PRolls'],"DouDisCts"=>$row['DouDisCts'],"DouDistributes"=>$row['DouDistributes'],"DRolls"=>$row['DRolls'],"OutGrayQty"=>$row['OutGrayQty']));
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