<?php
include_once "../common/consql.php";
function cprk($data){
	//建立数据库连接
	$conClass = new Connect();
	$conn = $conClass->sqlConnect();
	//数据操作
	try{
		$xAxis = array();//x轴
		$yAxis = array();//y轴
		$datetype = $data['datetype'];
		if($datetype=='1'){
			$startDate = $data['startdate'];//起始日期
			$endDate = date('Y-m-d',strtotime($data['enddate'])+86400);//结束日期
			$sql = "select Convert(varchar(8),SheetDate,112) BookDate , sum(isnull(a.LengthQty,0)) MRolls
				From Dye.ProdIn a
				where 1=1 and kind in (0,1,2,4)and isnull(CheckNo,'')<>'' and
				SheetDate between'$startDate' and '$endDate'
				group BY Convert(varchar(8),SheetDate,112)
				order by Convert(varchar(8),SheetDate,112);";//sql语句
		}
		else if($datetype=='2'){
			$startMon = $data['startmon'].'-01';//起始月份
			$endMon = date("Y-m", strtotime("+1 month", strtotime($data['endmon']))).'-01';//结束月份
			$sql = "select Convert(varchar(6),SheetDate,112) BookDate , sum(isnull(a.LengthQty,0)) MRolls
				From Dye.ProdIn a
				where 1=1 and kind in (0,1,2,3,4)and isnull(CheckNo,'')<>'' and
				SheetDate between '$startMon' and '$endMon'
				group BY Convert(varchar(6),SheetDate,112)
				order by Convert(varchar(6),SheetDate,112);";
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$endYear = date("Y-m-d", strtotime("+1 year", strtotime($data['endyear'].'-01-01')));//结束年份
			$sql = "select Convert(varchar(4),SheetDate,112) BookDate , sum(isnull(a.LengthQty,0)) MRolls
				From Dye.ProdIn a
				where 1=1 and kind in (0,1,2,3,4)and isnull(CheckNo,'')<>'' and
				SheetDate between '$startYear' and '$endYear'
				group BY Convert(varchar(4),SheetDate,112)
				order by Convert(varchar(4),SheetDate,112);";
		}
		$result = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			//把结果添加到数组
			array_push($xAxis,$row['BookDate']);
			array_push($yAxis,$row['MRolls']);
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	//关闭数据库连接
	$conClass->sqlClose($conn);
	return json_encode(Array('xAxis'=>$xAxis,'yAxis'=>$yAxis));
}
?>