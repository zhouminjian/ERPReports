<?php
	//机台产量
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$datetype = $_SESSION['datetype'];
		$jt = $_SESSION['jt'];
		$jt_array = array();
		$where = "";
		if($jt){
			$jt_array = explode(',',$jt);
			$where = "and (sh.ShiftID='$jt_array[0]'";
			for($index=1;$index<count($jt_array);$index++){
				$where .=" or sh.ShiftID='$jt_array[$index]'";
			}
			$where .=")";
		}
		if($datetype == '1'){
			$startDate = $_SESSION['startdate'];
			$endDate = date('Y-m-d',strtotime($_SESSION['enddate'])+86400);
			$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.ShiftID) RowId from(
select sh.Name,sh.ShiftID,sum(WorkQty)Qty from Dye.WProcess wp,Dye.p_Shift sh 
where EndTime BETWEEN '$startDate' and '$endDate' and wp.ShiftID is not null and wp.ShiftID<>'' and sh.ShiftID=wp.ShiftID $where
group by sh.Name,sh.ShiftID)x
)s ";
		}
		else if($datetype == '2'){
			$startMon = $_SESSION['startmon'].'-01';
			$endMon = date("Y-m", strtotime("+1 month", strtotime($_SESSION['endmon']))).'-01';
			$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.ShiftID) RowId from(
select sh.Name,sh.ShiftID,sum(WorkQty)Qty from Dye.WProcess wp,Dye.p_Shift sh 
where EndTime BETWEEN '$startMon' and '$endMon' and wp.ShiftID is not null and wp.ShiftID<>'' and sh.ShiftID=wp.ShiftID $where
group by sh.Name,sh.ShiftID)x
)s ";
		}
		else if($datetype == '3'){
			$startYear = $_SESSION['startyear'].'-01-01';//起始年份
			$endYear = date("Y-m-d", strtotime("+1 year", strtotime($_SESSION['endyear'].'-01-01')));//结束年份
			$selectSQL = "select s.* from
(select x.*,ROW_NUMBER() OVER(Order by x.ShiftID) RowId from(
select sh.Name,sh.ShiftID,sum(WorkQty)Qty from Dye.WProcess wp,Dye.p_Shift sh 
where EndTime BETWEEN '$startYear' and '$endYear' and wp.ShiftID is not null and wp.ShiftID<>'' and sh.ShiftID=wp.ShiftID $where
group by sh.Name,sh.ShiftID)x
)s ";
		}
		$startres = "";
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
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
				array_push($array,array("Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name']),"Qty"=>number_format($row['Qty'],2)));
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