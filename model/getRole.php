<?php
	//get all role
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$selectSQL = "select RID,Description from roles where RID<>1";
		$result = mysqli_query($con,$selectSQL);
		$conClass->sqlClose($con);
		$array = array();
		if($result->num_rows>0){
			while($group = $result->fetch_assoc()){
				array_push($array,array("rid"=>$group['RID'],"description"=>$group['Description']));
			}
			$resultJson=Array("code"=>108,"msg"=>"successed","data"=>$array);
		}
	}
	echo json_encode($resultJson);
?>