<?php
include_once "../common/consql.php";
function pbckcu($data){
	//建立数据库连接
	$conClass = new Connect();
	$conn = $conClass->sqlConnect();
	//数据操作
	try{
		$xAxis = array();//x轴
		$yAxis = array();//y轴
		$datetype = $data['datetype'];
		if($datetype=='2'){
			$startMon = $data['startmon'].'-01';//起始月份
			$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
			$sql = "select top 15 Cu.NameC, sum(x.LengthQty) OutQty
from(select * from (Select a.SheetDate,b.LengthQty,a.CustomerID from Dye.GrayOut a join
Dye.GrayOutDetail b on a.SheetSeries=b.SheetSeries 
left join Dye.ordercolor c on b.CheckNo=c.CheckNo
left join Dye.OrderMain o on c.SheetSeries=o.SheetSeries
union all
 select a.SheetDate,b.LengthQty,a.ToCustomerID
 from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b on a.SheetSeries=b.SheetSeries 
 left join Dye.h_Employee c on a.FromSalerID=c.EmpID
)a)x,Dye.p_Customer Cu where x.SheetDate between '$startMon' and '$tmpMon' and Cu.CustomerID=x.CustomerID
group by Cu.NameC
order by OutQty desc";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['OutQty']);
			}
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
			$sql = "select top 15 Cu.NameC, sum(x.LengthQty) OutQty
from(select * from (Select a.SheetDate,b.LengthQty,a.CustomerID from Dye.GrayOut a join
Dye.GrayOutDetail b on a.SheetSeries=b.SheetSeries 
left join Dye.ordercolor c on b.CheckNo=c.CheckNo
left join Dye.OrderMain o on c.SheetSeries=o.SheetSeries
union all
 select a.SheetDate,b.LengthQty,a.ToCustomerID
 from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b on a.SheetSeries=b.SheetSeries 
 left join Dye.h_Employee c on a.FromSalerID=c.EmpID
)a)x,Dye.p_Customer Cu where x.SheetDate between '$startYear' and '$tmpYear' and Cu.CustomerID=x.CustomerID
group by Cu.NameC
order by OutQty desc";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['OutQty']);
			}
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	//关闭数据库连接
	$conClass->sqlClose($conn);
	return json_encode(Array('xAxis'=>$xAxis,'yAxis'=>$yAxis));
//	return $yAxis;
}
?>