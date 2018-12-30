<?php
	//get all roleAuth
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$data = json_decode(stripslashes($_POST['data']),true);
		$rid = $data['value'];
		$selectSQL = "select r.pid from (select * from rolepermissions where RID = $rid) r
					join permission p on r.PID = p.PID
					join menu m on m.MID = p.MenuID";
		$result = mysqli_query($con,$selectSQL);
		$conClass->sqlClose($con);
		$array = array();
		if($result->num_rows>0){
			while($group = $result->fetch_assoc()){
				array_push($array,array("pid"=>$group['PID']));
			}
			$resultJson=Array("code"=>109,"msg"=>"successed","data"=>$array);
		}
	}
	echo json_encode($resultJson);
?>