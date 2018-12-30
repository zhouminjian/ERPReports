<?php
include_once "../common/consql.php";
function cprkcu($data){
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
			$sql = "select top 15 c.NameC, sum(isnull(a.LengthQty,0)) MRolls
From Dye.ProdIn a,Dye.p_Customer c
where 1=1 and kind in (0,1,2,3,4)and isnull(CheckNo,'')<>'' and
SheetDate between'$startMon' and '$tmpMon' and a.CustomerID=c.CustomerID
group BY Convert(varchar(6),SheetDate,112),c.NameC
order by MRolls desc;";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['MRolls']);
			}
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
			$sql = "select top 15 c.NameC, sum(isnull(a.LengthQty,0)) MRolls
From Dye.ProdIn a,Dye.p_Customer c
where 1=1 and kind in (0,1,2,3,4)and isnull(CheckNo,'')<>'' and
SheetDate between'$startYear' and '$tmpYear' and a.CustomerID=c.CustomerID
group BY Convert(varchar(4),SheetDate,112),c.NameC
order by MRolls desc;";
			$result = sqlsrv_query($conn,$sql);
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				//将结果集放到数组中
				array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
				array_push($yAxis,$row['MRolls']);
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