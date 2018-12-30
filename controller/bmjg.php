<?php
	//业务员价格数据
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$dept = $_SESSION['bm'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		$dept_array = array();
		$where = "";
		if($dept){
			$dept_array = explode(',',$dept);
			$where = "and (DeptID='$dept_array[0]'";
			for($index=1;$index<count($dept_array);$index++){
				$where .=" or DeptID='$dept_array[$index]'";
			}
			$where .=")";
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(
select t0.Name,t0.DeptID,
CONVERT(DECIMAL(18,2),ISNULL(t1.Fees,0))XFees,CONVERT(DECIMAL(18,2),ISNULL(t2.Fees,0))GFees,CONVERT(DECIMAL(18,2),ISNULL(t3.Fees,0))SFees,
CONVERT(DECIMAL(18,2),ISNULL(t1.ProSalesPrice,0))XSumSalePrices,CONVERT(DECIMAL(18,2),ISNULL(t1.AvgProSalesPrice,0))XAvgSalePrice,
CONVERT(DECIMAL(18,2),ISNULL(t2.ProSalesPrice,0))GSumSalePrices,CONVERT(DECIMAL(18,2),ISNULL(t2.AvgProSalesPrice,0))GAvgSalePrice,
CONVERT(DECIMAL(18,2),ISNULL(t3.ProSalesPrice,0))SSumSalePrices,CONVERT(DECIMAL(18,2),ISNULL(t3.AvgProSalesPrice,0))SAvgSalePrice,
CONVERT(DECIMAL(18,2),ISNULL(t1.ProDirPrice,0))XSumDirPrcices,CONVERT(DECIMAL(18,2),ISNULL(t1.AvgProDirPrice,0))XAvgDirPrice,
CONVERT(DECIMAL(18,2),ISNULL(t2.ProDirPrice,0))GSumDirPrcices,CONVERT(DECIMAL(18,2),ISNULL(t2.AvgProDirPrice,0))GAvgDirPrice,
CONVERT(DECIMAL(18,2),ISNULL(t3.ProDirPrice,0))SSumDirPrcices,CONVERT(DECIMAL(18,2),ISNULL(t3.AvgProDirPrice,0))SAvgDirPrice,
CONVERT(DECIMAL(18,2),ISNULL(t1.ProcessPrice,0))XSumProPrices,CONVERT(DECIMAL(18,2),ISNULL(t1.AvgProcessPrice,0))XAvgProPrice,
CONVERT(DECIMAL(18,2),ISNULL(t2.ProcessPrice,0))GSumProPrices,CONVERT(DECIMAL(18,2),ISNULL(t2.AvgProcessPrice,0))GAvgProPrice,
CONVERT(DECIMAL(18,2),ISNULL(t3.ProcessPrice,0))SSumProPrices,CONVERT(DECIMAL(18,2),ISNULL(t3.AvgProcessPrice,0))SAvgProPrice,ROW_NUMBER() OVER(Order by t0.DeptID) AS RowId from
(select DeptID,Name from Dye.p_Dept where ParentCode='YW' $where)t0
left join 
(select x.SalerGroupID,sum(x.OrderQty*x.Processprice) ProcessPrice,CONVERT(DECIMAL(18,2),avg(x.Processprice)) AvgProcessPrice,
sum(Fees) Fees,sum(x.OrderQty*x.salesPrice) ProSalesPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.salesPrice)/sum(x.OrderQty)) AvgProSalesPrice,
sum(x.OrderQty*x.DirectorPrice) ProDirPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.DirectorPrice)/sum(x.OrderQty)) AvgProDirPrice
from(
select om.SalerGroupID,om.SheetSeries,sum(oc.OrderQty)OrderQty,processprice,sum(Fee) Fees,salesPrice,DirectorPrice
from Dye.OrderColor oc,Dye.OrderMain om 
where om.SheetSeries = oc.SheetSeries and om.SheetDate between '$startYear' and '$endYear' and SalesPrice is not null
and om.BusinessKind not in(0,30) group by om.SalerGroupID,processprice,om.SheetSeries,SalesPrice,salesPrice,DirectorPrice)
x GROUP BY x.SalerGroupID
)t1
on t1.SalerGroupID=t0.DeptID
left join 
(select x.SalerGroupID,sum(x.OrderQty*x.Processprice)ProcessPrice,CONVERT(DECIMAL(18,2),avg(x.Processprice))AvgProcessPrice,
sum(Fees) Fees,sum(x.OrderQty*x.salesPrice) ProSalesPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.salesPrice)/sum(x.OrderQty))AvgProSalesPrice,
sum(x.OrderQty*x.DirectorPrice)ProDirPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.DirectorPrice)/sum(x.OrderQty))AvgProDirPrice
from(
select om.SalerGroupID,om.SheetSeries,sum(oc.OrderQty)OrderQty,processprice,sum(Fee) Fees,salesPrice,DirectorPrice
from Dye.OrderColor oc,Dye.OrderMain om
where om.SheetSeries = oc.SheetSeries and om.SheetDate between '$startYear' and '$endYear' and SalesPrice is not null
and om.BusinessKind = 0 group by om.SalerGroupID,processprice,om.SheetSeries,SalesPrice,salesPrice,DirectorPrice)
x GROUP BY x.SalerGroupID
)t2
on t2.SalerGroupID=t0.DeptID
left join
(select x.SalerGroupID,sum(x.OrderQty*x.Processprice)ProcessPrice,CONVERT(DECIMAL(18,2),avg(x.Processprice))AvgProcessPrice,
sum(Fees) Fees,sum(x.OrderQty*x.salesPrice) ProSalesPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.salesPrice)/sum(x.OrderQty))AvgProSalesPrice,
sum(x.OrderQty*x.DirectorPrice)ProDirPrice,CONVERT(DECIMAL(18,2),sum(x.OrderQty*x.DirectorPrice)/sum(x.OrderQty))AvgProDirPrice
from(
select om.SalerGroupID,om.SheetSeries,sum(oc.OrderQty)OrderQty,processprice,sum(Fee) Fees,salesPrice,DirectorPrice
from Dye.OrderColor oc,Dye.OrderMain om
where om.SheetSeries = oc.SheetSeries and om.SheetDate between '$startYear' and '$endYear' and SalesPrice is not null
and om.BusinessKind = 30 group by om.SalerGroupID,om.SheetSeries,processprice,SalesPrice,salesPrice,DirectorPrice)
x GROUP BY x.SalerGroupID
)t3
on t3.SalerGroupID=t0.DeptID
where t1.ProcessPrice<>0 or t1.AvgProcessPrice<>0 or t2.ProcessPrice<>0 or t2.AvgProcessPrice<>0 or t3.ProcessPrice<>0 or t3.AvgProcessPrice<>0 or t1.Fees<>0 or t2.Fees<>0 or t3.Fees<>0 or 
t1.ProSalesPrice<>0 or t1.AvgProSalesPrice<>0 or t2.ProSalesPrice<>0 or t2.AvgProSalesPrice<>0 or t3.ProSalesPrice<>0 or t3.AvgProSalesPrice<>0 or t1.ProDirPrice<>0 or t1.AvgProDirPrice<>0 or 
t2.ProDirPrice<>0 or t2.AvgProDirPrice<>0 or t3.ProDirPrice<>0 or t3.AvgProDirPrice<>0 
)s ";
			$_SESSION['SQL'] = $selectSQL;
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
				array_push($array,array("Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name']),"DeptID"=>$row['DeptID'],"XSumProPrices"=>number_format($row['XSumProPrices'],2),"XAvgProPrice"=>number_format($row['XAvgProPrice'],2),"GSumProPrices"=>number_format($row['GSumProPrices'],2),"GAvgProPrice"=>number_format($row['GAvgProPrice'],2),"SSumProPrices"=>number_format($row['SSumProPrices'],2),"SAvgProPrice"=>number_format($row['SAvgProPrice'],2),"XFees"=>number_format($row['XFees'],2),"GFees"=>number_format($row['GFees'],2),"SFees"=>number_format($row['SFees'],2),"XSumSalePrices"=>number_format($row['XSumSalePrices'],2),"XAvgSalePrice"=>number_format($row['XAvgSalePrice'],2),"GSumSalePrices"=>number_format($row['GSumSalePrices'],2),"GAvgSalePrice"=>number_format($row['GAvgSalePrice'],2),"SSumSalePrices"=>number_format($row['SSumSalePrices'],2),"SAvgSalePrice"=>number_format($row['SAvgSalePrice'],2),"XSumDirPrcices"=>number_format($row['XSumDirPrcices'],2),"XAvgDirPrice"=>number_format($row['XAvgDirPrice'],2),"GSumDirPrcices"=>number_format($row['GSumDirPrcices'],2),"GAvgDirPrice"=>number_format($row['GAvgDirPrice'],2),"SSumDirPrcices"=>number_format($row['SSumDirPrcices'],2),"SAvgDirPrice"=>number_format($row['SAvgDirPrice'],2)));
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