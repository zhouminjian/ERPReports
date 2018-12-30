<?php
	//订单个数
include_once "../common/consql.php";
function ddnum($data){
	//建立数据库连接
	$conClass = new Connect();
	$conn = $conClass->sqlConnect();
	//数据操作
	try{
		$xAxis = array();//x轴
		$yAxis = array();//y轴
		$y1Axis = array();//存放经销单
		$y2Axis = array();//存放加工单
		$y3Axis = array();//存放双经销
		$datetype = $data['datetype'];
		$sheetkind = $data['sheetkind'];
		if($sheetkind=='1'){
			$sheetkind = 'and sheetkind=0';
		}else if($sheetkind=='2'){
			$sheetkind = 'and sheetkind=30';
		}else{
			$sheetkind = '';
		}
		if($datetype=='2'){
			for($i=1;$i<=3;$i++){//1：经销  2：加工 3：双经销
				if($i==1){
					$businessKind = " and BusinessKind='10'";
				}
				else if($i==2){
					$businessKind = " and BusinessKind='0'";
				}
				else{
					$businessKind = " and BusinessKind='30'";
				}
				$startMon = $data['startmon'].'-01';//起始月份
				$endMon = date("Y-m", strtotime("+1 month", strtotime($data['endmon']))).'-01';//结束月份
				$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
				while(strcmp($tmpMon,$endMon)<=0){
					$sql = "select COUNT(*)+(select COUNT(*) 
 from Dye.OrderMain 
 where SheetDate BETWEEN '$startMon'and '$tmpMon' $sheetkind  and SheetType='10'  and SheetStatus='0' $businessKind) NUM
from Dye.OrderMain 
where SheetDate BETWEEN '$startMon'and '$tmpMon' $sheetkind  and SheetType='10'  and SheetStatus='0' $businessKind;";
					$result = sqlsrv_query($conn,$sql);
					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						//将结果集放到数组中
						if($i==1){
							array_push($xAxis,substr(date("Ymd",strtotime($startMon)),0,6));
							array_push($y1Axis,isset($row['NUM'])?$row['NUM']:0);
						}
						else if($i==2){
							array_push($y2Axis,isset($row['NUM'])?$row['NUM']:0);
						}
						else{
							array_push($y3Axis,isset($row['NUM'])?$row['NUM']:0);
						}
					}
					$startMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//向后一个月
					$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
				}
			}
			array_push($yAxis,$y1Axis,$y2Axis,$y3Axis);
		}
		else if($datetype=='3'){
			for($i=1;$i<=3;$i++){
				if($i==1){
					$businessKind = " and BusinessKind='10'";
				}
				else if($i==2){
					$businessKind = " and BusinessKind='0'";
				}
				else{
					$businessKind = " and BusinessKind='30'";
				}
				$startYear = $data['startyear'].'-01-01';//起始年份
				$endYear = date("Y-m-d", strtotime("+1 year", strtotime($data['endyear'].'-01-01')));//结束年份
				$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
				while(strcmp($tmpYear,$endYear)<=0){
					$sql = "select COUNT(*)+(select COUNT(*) 
 from Dye.OrderMain 
 where SheetDate BETWEEN '$startYear'and '$tmpYear' $sheetkind  and SheetType='10'  and SheetStatus='0' $businessKind) NUM
from Dye.OrderMain 
where SheetDate BETWEEN '$startYear'and '$tmpYear' $sheetkind  and SheetType='10'  and SheetStatus='0' $businessKind;";
					$result = sqlsrv_query($conn,$sql);
					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						//将结果集放到数组中
						if($i==1){
							array_push($xAxis,substr(date("Ymd",strtotime($startYear)),0,4));
							array_push($y1Axis,isset($row['NUM'])?$row['NUM']:0);
						}
						else if($i==2){
							array_push($y2Axis,isset($row['NUM'])?$row['NUM']:0);
						}
						else{
							array_push($y3Axis,isset($row['NUM'])?$row['NUM']:0);
						}
					}
					$startYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//向后一年
					$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
				}
			}
			array_push($yAxis,$y1Axis,$y2Axis,$y3Axis);
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