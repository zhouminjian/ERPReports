<?php
include_once "../common/consql.php";
function pbrk($data){
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
				$sql = "select sum(Gid.LengthQty)+(select sum(b.LengthQty) from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b 
on a.SheetSeries=b.SheetSeries and SheetDate between '$startMon' and '$tmpMon') SumGrayIn
from Dye.GrayInDetail Gid,Dye.GrayIn Gi
where Gid.SheetSeries=Gi.SheetSeries and Gi.SheetDate between '$startMon' and '$tmpMon'";
				$result = sqlsrv_query($conn,$sql);
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					//将结果集放到数组中
					array_push($xAxis,substr(date("Ymd",strtotime($startMon)),0,6));
					array_push($yAxis,$row['SumGrayIn']);
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
					$sql = "select sum(Gid.LengthQty)+(select sum(b.LengthQty) from Dye.GrayStockAllot a join Dye.GrayStockAllotDetail b 
on a.SheetSeries=b.SheetSeries and SheetDate between '$startYear' and '$tmpYear') SumGrayIn
from Dye.GrayInDetail Gid,Dye.GrayIn Gi
where Gid.SheetSeries=Gi.SheetSeries and Gi.SheetDate between '$startYear' and '$tmpYear'";
					$result = sqlsrv_query($conn,$sql);
					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						//将结果集放到数组中
						array_push($xAxis,substr(date("Ymd",strtotime($startYear)),0,4));
						array_push($yAxis,$row['SumGrayIn']);
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