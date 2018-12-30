<?php 
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		//读取index对应的用户信息
		$data = $_SESSION['info'];
		$resultJson=Array("code"=>123,"msg"=>"读取成功","data"=>$data);
	}
	echo json_encode($resultJson);
 ?>