<?php
	//修改指定用户的部分信息
	include_once "../common/sec.php";
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$data = json_decode(stripslashes($_POST['data']),true);
		$id = $data['uid'];
		$nickname = $data['nickname'];
		if($data['password']=='******'){
			$password = "";
		}
		else{
			$password = encryptDecrypt($data['password'],0);
			$password = ",u.uPassWord='$password'";
		}
		$rid = $data['rolenames'];
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$updateSQL = "update user u,userroles ur set u.uNickName='$nickname' $password ,ur.RID='$rid' where u.uID='$id' and u.uID=ur.UID";
		mysqli_query($con,$updateSQL);
		$conClass->sqlClose($con);
		$resultJson=Array("code"=>121,"msg"=>"修改成功");
	}
	echo json_encode($resultJson);
?>