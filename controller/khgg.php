<?php
	//读取所有客户规格
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$kh = $_SESSION['kh'];
		$startYear = $_SESSION['startyear'].'-01-01';//起始年份
		$endYear = date("Y-m-d", strtotime("+1 year", strtotime($startYear)));//结束年份
		$startres = "";
		$kh_array=array();
		$where = "";
		if($kh){
			$kh_array = explode(',',$kh);
			$where = "and (ct.CustomerID='$kh_array[0]'";
			for($index=1;$index<count($kh_array);$index++){
				$where .=" or ct.CustomerID='$kh_array[$index]'";
			}
			$where .= ')';
		}
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select s.* from(
select p.*,ROW_NUMBER() OVER(Order by SpecNum desc) as RowId from
(select sx.CustomerID,sx.NameC,count(sx.Spec)SpecNum from
	(select x.CustomerID,x.NameC,x.Spec from
		(select ct.CustomerID,ct.NameC,oc.SheetSeries,om.Spec
		from Dye.p_Customer ct
		join Dye.OrderMain om
		on ct.CustomerID=om.CustomerID
		join Dye.OrderColor oc
		on oc.SheetSeries=om.SheetSeries
		where om.SheetDate between '$startYear' and '$endYear' $where
		group by ct.CustomerID,ct.NameC,oc.SheetSeries,om.Spec)x
	group by x.CustomerID,x.NameC,x.Spec)sx
group by sx.CustomerID,sx.NameC)p)s ";
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
				array_push($array,array("CustomerID"=>$row['CustomerID'],"NameC"=>iconv("GBK", "UTF-8//IGNORE",$row['NameC']),"SpecNum"=>$row['SpecNum']));
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