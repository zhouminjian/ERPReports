<?php
	//记录查询条件
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$condition = json_decode(stripslashes($_POST['data']),true);
		foreach($condition as $key=>$value){
			$_SESSION[$key] = $value;
		}
	}
?>