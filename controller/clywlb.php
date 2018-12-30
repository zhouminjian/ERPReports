<?php
include_once "../common/consql.php";
function clywlb($data){
	
	try{
		//建立数据库连接
		$conClass = new Connect();
		$conn = $conClass->sqlConnect();
		//数据操作
		$xAxis = array();//x轴
		$yAxis = array();//y轴
		$datetype = $data['datetype'];
		$businesskind = $data['businesskind'];
		$sheetkind = $data['sheetkind'];
		//判断业务类别
		if($businesskind == '1'){ //1为加工单
			$businesskind = 'and a.BusinessKind=0';
		}
		else if($businesskind == '2'){  //2为经销单
			$businesskind = 'and a.BusinessKind=10';
		}
		else if($businesskind == '4'){  //4为双经销单
			$businesskind = 'and a.BusinessKind=30';
		}
		else if($businesskind == '3'){
			$businesskind = '';
		}
		//判断订单类型
		if($sheetkind == '1'){ //1为正常单
			$sheetkind = "and SheetKind = '0'";
		}
		else if($sheetkind == '2'){   //2为试样单
			$sheetkind = "and SheetKind = '30'";
		}
		else if($sheetkind == '3'){
			$sheetkind = '';
		}
		if($datetype=='2'){
			$startMon = $data['startmon'].'-01';//起始月份
			$endMon = date("Y-m", strtotime("+1 month", strtotime($data['endmon']))).'-01';//结束月份
			$tmpMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));
			while(strcmp($tmpMon,$endMon)<=0){
				$sql = "select Convert(varchar(6),s.BookDate,112) Mon,sum(s.DRolls) MRolls from(select CheckNo,CustomerID,ContractNo,SalerGroupID,SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,AssignUnit,Kind,SheetDate BookDate, NQty DRolls,NRolls,CQty,JQty,AQty
from (
Select js.CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,js.ProdID,js.Spec,js.PlanQty,om.SheetKind,c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,0 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,js.ColorE,om.BusinessKind,js.OrderQty,om.AssignUnit
From Dye.OrderColor js
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind in (0,2)and isnull(CheckNo,'')<>'' and SheetDate BETWEEN '$startMon' and '$tmpMon' 
group by CheckNo ) c on js.CheckNo=c.CheckNo 
left join Dye.OrderMain om on js.SheetSeries=om.SheetSeries
where 1=1  and isnull(js.CheckNo,'')<>'' $sheetkind
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
where 1=1 and kind =1 and isnull(CheckNo,'')<>''and
SheetDate BETWEEN '$startMon' and '$tmpMon' 
group by CheckNo ) c on oc.CheckNo=c.CheckNo
left join Dye.OrderMain om on oc.SheetSeries=om.SheetSeries
where 1=1  and isnull(oc.CheckNo,'')<>'' $sheetkind
)a
where 1=1 and  isnull(NQty,0)>0 $businesskind) s
group BY Convert(varchar(6),s.BookDate,112)
order by Convert(varchar(6),s.BookDate,112)";
				$result = sqlsrv_query($conn,$sql);
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					//将结果集放到数组中
					array_push($xAxis,$row['Mon']);
					array_push($yAxis,$row['MRolls']);
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
				$sql = "select Convert(varchar(4),s.BookDate,112) Year,sum(s.DRolls) MRolls from(select CheckNo,CustomerID,ContractNo,SalerGroupID,SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,AssignUnit,Kind,SheetDate BookDate, NQty DRolls,NRolls,CQty,JQty,AQty
from (
Select js.CheckNo,om.CustomerID,om.ContractNo,om.SalerGroupID,om.SalerID,js.ProdID,js.Spec,js.PlanQty,om.SheetKind,
c.SheetDate,isnull(c.LengthQty,0) NQty,c.Rolls NRolls,0 Kind,
isnull(c.CQty,0) CQty,isnull(c.JQty,0) JQty,
isnull(c.LengthQty,0)+isnull(c.CQty,0)+isnull(c.JQty,0) AQty,
js.ColorE,om.BusinessKind,js.OrderQty,om.AssignUnit
From Dye.OrderColor js
  left join ( select a.CheckNo,sum(isnull(a.Rolls,0)) Rolls,max(SheetDate) SheetDate,
  sum(isnull(a.LengthQty,0)) LengthQty,sum(isnull(a.CQty,0)) CQty,sum(isnull(a.JQty,0)) JQty
From Dye.ProdIn a
where 1=1 and kind in (0,2)and isnull(CheckNo,'')<>'' and
 SheetDate BETWEEN '$startYear' and '$tmpYear' 
group by CheckNo ) c on js.CheckNo=c.CheckNo 
left join Dye.OrderMain om on js.SheetSeries=om.SheetSeries
where 1=1  and isnull(js.CheckNo,'')<>'' $sheetkind
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
where 1=1 and kind =1 and isnull(CheckNo,'')<>''and
SheetDate BETWEEN '$startYear' and '$tmpYear' 
group by CheckNo ) c on oc.CheckNo=c.CheckNo
left join Dye.OrderMain om on oc.SheetSeries=om.SheetSeries
where 1=1  and isnull(oc.CheckNo,'')<>'' $sheetkind
)a
where 1=1 and  isnull(NQty,0)>0 $businesskind) s
group BY Convert(varchar(4),s.BookDate,112)
order by Convert(varchar(4),s.BookDate,112);";
				$result = sqlsrv_query($conn,$sql);
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					//将结果集放到数组中
					array_push($xAxis,$row['Year']);
					array_push($yAxis,$row['MRolls']);
				}
				$startYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
				$tmpYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));
			}
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	//关闭数据库连接
	$conClass->sqlClose($conn);
	return json_encode(Array('xAxis'=>$xAxis,'yAxis'=>$yAxis));
//	return $sql;
}
?>