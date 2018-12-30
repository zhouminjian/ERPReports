<?php
	//get all auth
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$selectSQL = "select PID,MID,Menuname,ParentID from permission join menu on MenuID = MID";
		$result = mysqli_query($con,$selectSQL);
		$conClass->sqlClose($con);
		$array = array();
		if($result->num_rows>0){
			while($group = $result->fetch_assoc()){
				array_push($array,array("pid"=>$group['PID'],"mid"=>$group['MID'],"menuname"=>$group['Menuname'],"parentid"=>$group['ParentID']));
			}
			$resultJson=Array("code"=>106,"msg"=>"successed","data"=>$array);
		}
	}
	echo json_encode($resultJson);
?>