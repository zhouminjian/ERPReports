<?php 
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		//存储index对应的用户信息
		$data = json_decode(stripslashes($_POST['data']),true);
		$_SESSION['info'] = $data;
		$resultJson=Array("code"=>122,"msg"=>"保存成功");
	}
	echo json_encode($resultJson);
 ?>