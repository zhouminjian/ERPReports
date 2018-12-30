<?php
	//读取所有业务员
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		try{
			$conClass = new Connect();
			$conn = $conClass->sqlConnect();
			$selectSQL = "select EmpID,Name from Dye.h_Employee where DeptID like 'YW%'";
			$result = sqlsrv_query($conn,$selectSQL);
			$array = array();
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				array_push($array,array("EmpID"=>$row['EmpID'],"Name"=>iconv("GBK", "UTF-8//IGNORE",$row['Name'])));
			}
			$resultJson=Array("code"=>132,"msg"=>"读取成功","data"=>$array);
			$conClass->sqlClose($conn);
		}
		catch(Exception $e){
			$resultJson=Array("code"=>133,"msg"=>$e->getMessage());
		}
	}
	echo json_encode($resultJson);
?>