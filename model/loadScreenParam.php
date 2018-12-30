<?php
	//读取所有投屏参数
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$selectSQL = "select * from screenparam";
		$result = mysqli_query($con,$selectSQL);
		$conClass->sqlClose($con);
		$array = array();
		if($result->num_rows>0){
			while($group = $result->fetch_assoc()){
				array_push($array,array("id"=>$group['id'],"datetype"=>$group['datetype'],"lx"=>$group['lx'],"cycle"=>$group['cycle'],"dtype"=>$group['dtype']));
			}
			$resultJson=Array("code"=>128,"msg"=>"读取成功","data"=>$array);
		}
		else{
			$resultJson=Array("code"=>127,"msg"=>"读取失败");
		}
	}
	echo json_encode($resultJson);
?>