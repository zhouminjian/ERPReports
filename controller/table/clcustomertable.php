<?php
	//读取所有客户规格
	include_once "../../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$datetype = $_SESSION['datetype'];
		$sheetkind = $_SESSION['sheetkind'];
		$businesskind = $_SESSION['businesskind'];
		$startres = "";
		if($sheetkind==1){
			$sheetkind = " and SheetKind=0 ";
		}
		else if($sheetkind==2){
			$sheetkind = " and SheetKind=30 ";
		}
		else{
			$sheetkind = "";
		}
		if($businesskind==1){
			$businesskind = " and BusinessKind=0 ";
		}
		else if($businesskind==2){
			$businesskind = " and BusinessKind not in(0,30) ";
		}
		else if($businesskind==4){
			$businesskind = " and BusinessKind =30 ";
		}
		else{
			$businesskind = "";
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			if($datetype == '2'){
				$startMon = $_SESSION['startmon'].'-01';//起始月份
				$endMon = date("Y-m-d", strtotime("+1 month", strtotime($startMon)));//结束月份
				$selectSQL = "select s.* from
(select x.NameC,x.MRolls,ROW_NUMBER() OVER(Order by x.MRolls desc) RowId from(select s.NameC,sum(s.DRolls) MRolls from(select CheckNo,NameC,ContractNo,SalerGroupID,a.SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,
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
where 1=1  and isnull(oc.CheckNo,'')<>''  $sheetkind $businesskind
)a,Dye.p_Customer cu
where 1=1 and isnull(NQty,0)>0 and a.CustomerID=cu.CustomerID) s
group BY s.NameC
)x
)s ";
			}
			else{
				$startYear = $_SESSION['startyear'].'-01-01';//起始年份
				$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
				$selectSQL = "select s.* from
(select x.NameC,x.MRolls,ROW_NUMBER() OVER(Order by x.MRolls desc) RowId from(select s.NameC,sum(s.DRolls) MRolls from(select CheckNo,NameC,ContractNo,SalerGroupID,a.SalerID,ProdID,Spec,PlanQty,SheetKind,ColorE,BusinessKind,OrderQty,
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
where 1=1  and isnull(oc.CheckNo,'')<>''  $sheetkind $businesskind
)a,Dye.p_Customer cu
where 1=1 and isnull(NQty,0)>0 and a.CustomerID=cu.CustomerID) s
group BY s.NameC
)x
)s ";
			}
			$result = sqlsrv_query($conn,$selectSQL,array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));//查询所有提取count
			$count = sqlsrv_num_rows($result);
			//计算起始、结束位置
			$startres = ($page-1)*$limit+1;
			$limit = $limit*$page;
			$startres = "where s.RowId between $startres and $limit";
			$selectSQL .= $startres;//拼接页码
			$result = sqlsrv_query($conn,$selectSQL);//根据页码查询返回的结果
			$array = array();
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				array_push($array,array("NameC"=>iconv("GBK", "UTF-8//IGNORE",$row['NameC']),"MRolls"=>$row['MRolls']));
			}
			$resultJson=Array("code"=>0,"msg"=>"查询成功","count"=>$count,"data"=>$array);
			$conClass->sqlClose($conn);
		}
		catch(Exception $e){
			$resultJson=Array("code"=>135,"msg"=>$e->getMessage());
		}
	}
	echo json_encode($resultJson);
?>