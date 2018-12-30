<?php
include_once "../common/consql.php";
function ddcustomer($data){
	//建立数据库连接
	$conClass = new Connect();
	$conn = $conClass->sqlConnect();
	//数据操作
	try{
		$xAxis = array();//x轴
		$yAxis = array();//y轴
		$datetype = $data['datetype'];
		$sheetkind = $data['sheetkind'];
		$businesskind = $data['businesskind'];
		if($sheetkind=='1'){
			$sheetkind = 'and sheetkind=0';
		}else if($sheetkind=='2'){
			$sheetkind = 'and sheetkind=30';
		}else{
			$sheetkind = '';
		}
		//判断业务类别
		if($businesskind == '1'){ //1为加工单
			$businesskind = 'and BusinessKind=0';
		}
		else if($businesskind == '2'){  //2为经销单
			$businesskind = 'and BusinessKind not in(0,30)';
		}
		else if($businesskind == '4'){	//4双经销
			$businesskind = 'and BusinessKind=30';
		}
		else{
			$businesskind = '';
		}
		if($datetype=='2'){
			$startMon = $data['startmon'].'-01';//起始月份
			$endMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//结束月份
			$sql = "select top 20 p.NameC,Sum(c.OrderQty) QTY
from Dye.OrderMain m ,Dye.OrderColor c, Dye.p_Customer p 
where SheetDate between '$startMon'and '$endMon' $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and m.CustomerID=p.CustomerID and SheetType='10'  and SheetStatus='0' group by p.NameC order by Sum(c.OrderQty) desc";
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
			$sql = "select top 20 p.NameC,Sum(c.OrderQty) QTY
from Dye.OrderMain m ,Dye.OrderColor c, Dye.p_Customer p 
where SheetDate between '$startYear'and '$endYear' $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and m.CustomerID=p.CustomerID and SheetType='10'  and SheetStatus='0' group by p.NameC order by Sum(c.OrderQty) desc";
		}
		$result = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			//把结果添加到数组,sqlserver中文为GBK编码
			array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['NameC']));
			array_push($yAxis,$row['QTY']);
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	//关闭数据库连接
	$conClass->sqlClose($conn);
	return json_encode(Array('xAxis'=>$xAxis,'yAxis'=>$yAxis));
//	return $xAxis;
}
?>