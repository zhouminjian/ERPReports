<?php
include_once "../common/consql.php";
function cldept($data){
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
			$sql = "select s.Name,sum(s.DRolls) MRolls from(select CheckNo,CustomerID,ContractNo,dp.Name,SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,
AssignUnit,Kind,SheetDate BookDate, NQty DRolls,NRolls,CQty,JQty,AQty
from (
Select js.CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,js.ProdID,js.Spec,js.PlanQty,om.SheetKind,
c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,0 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,
isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,
js.ColorE,om.BusinessKind,js.OrderQty,
om.AssignUnit
From Dye.OrderColor js
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,
  sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind in (0,2)and isnull(CheckNo,'')<>'' and
 SheetDate BETWEEN '$startMon' and '$endMon' 
group by CheckNo ) c on js.CheckNo=c.CheckNo
left join Dye.OrderMain om on js.SheetSeries=om.SheetSeries
where 1=1  and isnull(js.CheckNo,'')<>'' $sheetkind $businesskind
union all
Select oc.CheckNo+'R' CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,oc.ProdID,oc.Spec,oc.PlanQty,om.SheetKind,
c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,1 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,
isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,
oc.ColorE,om.BusinessKind,oc.OrderQty,
om.AssignUnit
From Dye.OrderColor oc
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,
  sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind =1 and isnull(CheckNo,'')<>'' and
SheetDate BETWEEN '$startMon' and '$endMon' 
group by CheckNo ) c on oc.CheckNo=c.CheckNo
left join Dye.OrderMain om on oc.SheetSeries=om.SheetSeries
where 1=1  and isnull(oc.CheckNo,'')<>'' $sheetkind $businesskind
)a ,Dye.p_Dept dp
where 1=1 and  isnull(NQty,0)>0 and a.SalerGroupID=dp.DeptID) s
group BY Convert(varchar(6),s.BookDate,112),s.Name
order by Convert(varchar(6),s.BookDate,112),s.Name;";
		}
		else if($datetype=='3'){
			$startYear = $data['startyear'].'-01-01';//起始年份
			$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
			$sql = "select s.Name,sum(s.DRolls) MRolls from(select CheckNo,CustomerID,ContractNo,dp.Name,SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,
AssignUnit,Kind,SheetDate BookDate, NQty DRolls,NRolls,CQty,JQty,AQty
from (
Select js.CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,js.ProdID,js.Spec,js.PlanQty,om.SheetKind,
c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,0 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,
isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,
js.ColorE,om.BusinessKind,js.OrderQty,
om.AssignUnit
From Dye.OrderColor js
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,
  sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind in (0,2)and isnull(CheckNo,'')<>'' and
 SheetDate BETWEEN '$startYear' and '$endYear' 
group by CheckNo ) c on js.CheckNo=c.CheckNo
left join Dye.OrderMain om on js.SheetSeries=om.SheetSeries
where 1=1  and isnull(js.CheckNo,'')<>'' $sheetkind $businesskind
union all
Select oc.CheckNo+'R' CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,oc.ProdID,oc.Spec,oc.PlanQty,om.SheetKind,
c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,1 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,
isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,
oc.ColorE,om.BusinessKind,oc.OrderQty,
om.AssignUnit
From Dye.OrderColor oc
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,
  sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind =1 and isnull(CheckNo,'')<>'' and
SheetDate BETWEEN '$startYear' and '$endYear' 
group by CheckNo ) c on oc.CheckNo=c.CheckNo
left join Dye.OrderMain om on oc.SheetSeries=om.SheetSeries
where 1=1  and isnull(oc.CheckNo,'')<>'' $sheetkind $businesskind
)a ,Dye.p_Dept dp
where 1=1 and  isnull(NQty,0)>0 and a.SalerGroupID=dp.DeptID) s
group BY Convert(varchar(4),s.BookDate,112),s.Name
order by Convert(varchar(4),s.BookDate,112),s.Name;";
		}
		$result = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			//把结果添加到数组,sqlserver中文为GBK编码
			array_push($xAxis,iconv("GBK", "UTF-8//IGNORE",$row['Name']));
			array_push($yAxis,$row['MRolls']);
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