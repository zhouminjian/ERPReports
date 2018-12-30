<?php
include_once "../common/consql.php";
function dddept($data){
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
		if($sheetkind=='1'){//正常单
			$sheetkind = 'and sheetkind=0';
		}else if($sheetkind=='2'){//试样单
			$sheetkind = 'and sheetkind=30';
		}else{//所有订单
			$sheetkind = '';
		}
		//判断业务类别
		if($businesskind == '1'){ //1为加工单
			$businesskind = 'and BusinessKind=0';
		}
		else if($businesskind == '2'){  //2为经销单
			$businesskind = 'and BusinessKind=10';
		}
		else if($businesskind == '4'){  //4为双经销单
			$businesskind = 'and BusinessKind=30';
		}
		else if($businesskind == '3'){
			$businesskind = '';
		}
		if($datetype=='2'){
			$startMon = $data['startmon'].'-01';//起始月份
			$endMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//结束月份
			$sql = "select sum(x.QTY) QTYS,de.Name from(select SUM(c.OrderQty) QTY,m.SalerGroupID
	from Dye.OrderColor c ,Dye.OrderMain m 
	where (SheetDate between '$startMon' and '$endMon') $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and SheetStatus='0' and Unit='0'
	group by m.SalerGroupID
UNION 
select SUM(c.OrderQty)*0.9144 QTY,m.SalerGroupID
	from Dye.OrderColor c ,Dye.OrderMain m 
	where (SheetDate between '$startMon' and '$endMon') $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and SheetStatus='0' and Unit='2'
	group by m.SalerGroupID
)x,Dye.p_Dept de
where x.SalerGroupID=de.DeptID
group by de.Name;";
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
			$sql = "select sum(x.QTY) QTYS,de.Name from(select SUM(c.OrderQty) QTY,m.SalerGroupID
	from Dye.OrderColor c ,Dye.OrderMain m 
	where (SheetDate between '$startYear' and '$endYear') $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and SheetStatus='0' and Unit='0'
	group by m.SalerGroupID
UNION 
select SUM(c.OrderQty)*0.9144 QTY,m.SalerGroupID
	from Dye.OrderColor c ,Dye.OrderMain m 
	where (SheetDate between '$startYear' and '$endYear') $sheetkind $businesskind and c.SheetSeries=m.SheetSeries and SheetStatus='0' and Unit='2'
	group by m.SalerGroupID
)x,Dye.p_Dept de
where x.SalerGroupID=de.DeptID
group by de.Name";
		}
		$result = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			//把结果添加到数组,sqlserver中文为GBK编码
			array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['Name']));
			array_push($yAxis,$row['QTYS']);
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