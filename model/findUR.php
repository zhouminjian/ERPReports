<?php
	//delete role
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$rid = json_decode(stripslashes($_POST['data']),true);
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$selectSQL = "select UID from userroles where RID='$rid'";
		$result = mysqli_query($con,$selectSQL);
		//判断是否有用户占用角色
		if($result->num_rows>0){
			$resultJson=Array("code"=>112,"msg"=>"角色ID被占用，请先删除用户！");
		}
		else{
			//没有则进行删除操作
			$delRSQL = "delete from roles where RID='$rid'";
			mysqli_query($con,$delRSQL);
			$resultJson=Array("code"=>113,"msg"=>"角色删除成功");
		}
		$conClass->sqlClose($con);
	}
	echo json_encode($resultJson);
?>