<?php
	//删除投屏记录
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$data = json_decode(stripslashes($_POST['data']),true);
		$id = $data['id'];
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$delSQL = "delete from screenparam where id='$id'";
		mysqli_query($con,$delSQL);
		$conClass->sqlClose($con);
		$resultJson=Array("code"=>129,"msg"=>"删除数据成功");
	}
	echo json_encode($resultJson);
?>