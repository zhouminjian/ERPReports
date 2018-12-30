<?php
include_once "../common/consql.php";
function pbck($data){
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
			$endMon = date("Y-m", strtotime("+1 month", strtotime($data['endmon']))).'-01';//结束月份
			$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
			while(strcmp($tmpMon,$endMon)<=0){
				$sql = "select sum(x.LengthQty) OutQty
from(select * from (Select a.SheetDate,b.LengthQty from Dye.GrayOut a join
Dye.GrayOutDetail b on a.SheetSeries=b.SheetSeries 
left join Dye.ordercolor c on b.CheckNo=c.CheckNo
left join Dye.OrderMain o on c.SheetSeries=o.SheetSeries
union all
 select 
a.SheetDate,b.LengthQty
 from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b on a.SheetSeries=b.SheetSeries 
 left join Dye.h_Employee c on a.FromSalerID=c.EmpID
)a)x where x.SheetDate between '$startMon' and '$tmpMon'";
				$result = sqlsrv_query($conn,$sql);
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					//将结果集放到数组中
					array_push($xAxis,substr(date("Ymd",strtotime($startMon)),0,6));
					array_push($yAxis,$row['OutQty']);
				}
				$startMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//向后一个月
				$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
			}
		}
		else if($datetype=='3'){
				$startYear = $data['startyear'].'-01-01';//起始年份
				$endYear = date("Y-m-d", strtotime("+1 year", strtotime($data['endyear'].'-01-01')));//结束年份
				$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
				while(strcmp($tmpYear,$endYear)<=0){
					$sql = "select sum(x.LengthQty) OutQty
from(select * from (Select a.SheetDate,b.LengthQty from Dye.GrayOut a join
Dye.GrayOutDetail b on a.SheetSeries=b.SheetSeries 
left join Dye.ordercolor c on b.CheckNo=c.CheckNo
left join Dye.OrderMain o on c.SheetSeries=o.SheetSeries
union all
 select 
a.SheetDate,b.LengthQty
 from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b on a.SheetSeries=b.SheetSeries 
 left join Dye.h_Employee c on a.FromSalerID=c.EmpID
)a)x where x.SheetDate between '$startYear' and '$tmpYear'";
					$result = sqlsrv_query($conn,$sql);
					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						//将结果集放到数组中
						array_push($xAxis,substr(date("Ymd",strtotime($startYear)),0,4));
						array_push($yAxis,$row['OutQty']);
					}
					$startYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//向后一年
					$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
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