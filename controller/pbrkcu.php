<?php
include_once "../common/consql.php";
function pbrkcu($data){
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
			$sql = "select top 15 Cu.NameC, sum(Gid.LengthQty) SumGrayIn
from Dye.GrayInDetail Gid,Dye.GrayIn Gi,Dye.p_Customer Cu
where Gid.SheetSeries=Gi.SheetSeries and Gi.SheetDate between '$startMon' and '$tmpMon' and Cu.CustomerID=Gi.CustomerID
group by Cu.NameC
order by SumGrayIn desc";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['SumGrayIn']);
			}
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
			$sql = "select top 15 Cu.NameC, sum(Gid.LengthQty) SumGrayIn
from Dye.GrayInDetail Gid,Dye.GrayIn Gi,Dye.p_Customer Cu
where Gid.SheetSeries=Gi.SheetSeries and Gi.SheetDate between '$startYear' and '$tmpYear' and Cu.CustomerID=Gi.CustomerID
group by Cu.NameC
order by SumGrayIn desc";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['SumGrayIn']);
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