<?php
include_once "../common/consql.php";
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"访问失败");
}
else{
	$data = json_decode(stripslashes($_POST['data']),true);
	$account = $data['account'];
	$newpwd = $data['npwd'];
	
	$conClass = new ConnectMy();
	$con = $conClass->sqlConnect();
	mysqli_query($con,"set names 'utf8'");
	$updateSQL = "update user set uPassWord='$newpwd' where uAccount='$account'";
	mysqli_query($con,$updateSQL);
	$conClass->sqlClose($con);
	$resultJson=Array("code"=>103,"msg"=>"密码修改成功");
}
echo json_encode($resultJson);
?>