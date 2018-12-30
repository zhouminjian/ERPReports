<?php
	//查询用户
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$data = json_decode(stripslashes($_POST['data']),true);
		$uid = $data['id'];
		if($uid == 1){
			$resultJson=Array("code"=>119,"msg"=>"无法删除系统管理员");
		}
		else{
			$conClass = new ConnectMy();
			$con = $conClass->sqlConnect();
			mysqli_query($con,"set names 'utf8'");
			$delSQL = "delete from user where uID='$uid'";
			mysqli_query($con,$delSQL);
			$conClass->sqlClose($con);
			$resultJson=Array("code"=>120,"msg"=>"删除用户成功");
		}
	}
	echo json_encode($resultJson);
?>