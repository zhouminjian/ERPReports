<?php
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"注销失败");
}
else{
	unset($_SESSION['uid']);
	unset($_SESSION['account']);
	unset($_SESSION['password']);
	unset($_SESSION['nickname']);
	session_destroy();
	$resultJson=Array("code"=>110,"msg"=>"注销 成功");
}
echo json_encode($resultJson);
?>