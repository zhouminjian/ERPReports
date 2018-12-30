<?php
//新增角色
include_once "../common/consql.php";
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"failed");
}
else{
	$data = json_decode(stripslashes($_POST['data']),true);
	$rolename = $data['rolename'];//角色名称
	//判断用户名是否存在
	$conClass = new ConnectMy();
	$con = $conClass->sqlConnect();
	mysqli_query($con,"set names 'utf8'");
	$selectSQL = "select * from roles where Description='$rolename'";
	$result = mysqli_query($con,$selectSQL);
	if($result->num_rows>0){
		$resultJson=Array("code"=>107,"msg"=>"角色名已存在");
	}
	else{
		$insertSQL = "insert into roles(Description) values('$rolename')";
		mysqli_query($con,$insertSQL);
		//获取角色的id
		$selectSQL = "select RID from roles where Description='$rolename'";
		$result = mysqli_query($con,$selectSQL);
		while($group = $result->fetch_assoc()){
			$rid = $group['RID'];
		}
		mysqli_autocommit($con,FALSE);
		foreach($data as $key=>$value){
			//循环读取提交的权限
			if($key!="rolename"){
				$pid = $value;
				$insertSQL = "insert into rolepermissions(RID,PID) values('$rid','$pid')";
				mysqli_query($con,$insertSQL);
			}
		}
		mysqli_commit($con);
		$resultJson = Array("code"=>108,"msg"=>"新增成功");
	}
	$conClass->sqlClose($con);
}
echo json_encode($resultJson);
?>