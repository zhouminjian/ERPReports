<?php
//更新角色对应的权限
include_once "../common/consql.php";
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"访问失败");
}
else{
	$newpid = json_decode(stripslashes($_POST['newpid']),true);
	$notexistpid = json_decode(stripslashes($_POST['notexistpid']),true);
	$rid = $_GET["rid"];
	$conClass = new ConnectMy();
	$con = $conClass->sqlConnect();
	mysqli_query($con,"set names 'utf8'");
	mysqli_autocommit($con,FALSE);
	//插入新增的权限
	for($i=0;$i<count($newpid);$i++){
		$updateSQL = "insert into rolepermissions(RID,PID) values('$rid','$newpid[$i]')";
		mysqli_query($con,$updateSQL);
	}
	//删剔除的权限
	for($i=0;$i<count($notexistpid);$i++){
		$deleteSQL = "delete from rolepermissions where RID='$rid' and PID='$notexistpid[$i]'";
		mysqli_query($con,$deleteSQL);
	}
	mysqli_commit($con);
	$conClass->sqlClose($con);
	$resultJson=Array("code"=>111,"msg"=>"修改权限成功");
}
echo json_encode($resultJson);
?>