<?php
	//新增投屏记录
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$data = json_decode(stripslashes($_POST['data']),true);
		$datetype = $data['datetype'];
		$lx = $data['lx'];
		$cycle = $data['cycle'];
		$dtype = $data['dtype'];
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$insertSQL = "insert into screenparam(datetype,lx,cycle,dtype) values($datetype,'$lx',$cycle,'$dtype')";
		mysqli_query($con,$insertSQL);
		$conClass->sqlClose($con);
		$resultJson=Array("code"=>131,"msg"=>"新增成功");
	}
	echo json_encode($resultJson);
?>