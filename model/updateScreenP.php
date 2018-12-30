<?php
	//修改投屏记录
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$data = json_decode(stripslashes($_POST['data']),true);
		$id = $data['id'];
		$datetype = $data['datetype'];
		$lx = $data['lx'];
		$cycle = $data['cycle'];
		$dtype = $data['ymd'];
		$updateSQL = "update screenparam set datetype='$datetype',lx='$lx',cycle='$cycle',dtype='$dtype' where id='$id';";
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		mysqli_query($con,$updateSQL);
		$conClass->sqlClose($con);
		$resultJson=Array("code"=>130,"msg"=>"更新成功");
	}
	echo json_encode($resultJson);
?>