<?php
//新增用户
include_once "../common/sec.php";
include_once "../common/consql.php";
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"failed");
}
else{
	$data = json_decode(stripslashes($_POST['data']),true);
	$account = $data['account'];
	$password = encryptDecrypt('123456',0);//新增用户默认密码为123456
	$nickname = $account;
	$usergroup = $data['group'];
	//判断用户名是否存在
	$conClass = new ConnectMy();
	$con = $conClass->sqlConnect();
	mysqli_query($con,"set names 'utf8'");
	$selectSQL = "select * from user where uAccount='$account'";
	$result = mysqli_query($con,$selectSQL);
	if($result->num_rows>0){
		$resultJson=Array("code"=>105,"msg"=>"用户名已存在");
	}
	else{
		$insertSQL = "insert into user(uAccount,uPassWord,uNickName) values('$account','$password','$nickname')";
		mysqli_query($con,$insertSQL);
		$selectSQL = "select uID from user where uAccount='$account'";
		$result = mysqli_query($con,$selectSQL);
		while($group = $result->fetch_assoc()){
			$uid = $group['uID'];
		}
		$insertSQL = "insert into UserRoles(UID,RID) values('$uid','$usergroup')";
		mysqli_query($con,$insertSQL);
		$resultJson=Array("code"=>104,"msg"=>"新增成功");
	}
	$conClass->sqlClose($con);
}
echo json_encode($resultJson);
?>