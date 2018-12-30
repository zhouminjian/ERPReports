<?php
	//查询用户
	include_once "../common/consql.php";
	session_start();
	if(!isset($_SESSION['uid'])){
		$resultJson=Array("code"=>100,"msg"=>"failed");
	}
	else{
		$page = $_GET['page'];
		$limit = $_GET['limit'];
		$where = $_SESSION['condition'];
		$searchSQL = "select u.*,ur.RID,r.Description from (select * from user";
		if($where){
			$searchSQL = $searchSQL." where uAccount like '%$where%' or uNickName like '%$where%'";
		}
		$searchSQL = $searchSQL.")u join userroles ur on ur.UID=u.uID join roles r on ur.RID=r.RID";
		//
		$conClass = new ConnectMy();
		$con = $conClass->sqlConnect();
		mysqli_query($con,"set names 'utf8'");
		$result = mysqli_query($con,$searchSQL);
		//存放总数
		$count = $result->num_rows;
		//计算起始位置
		$startres = ($page-1)*$limit;
		$searchSQL = $searchSQL." limit $startres,$limit";
		$result = mysqli_query($con,$searchSQL);
		$conClass->sqlClose($con);
		$array = array();
		while($group = $result->fetch_assoc()){
			$tmp = array('id'=>$group['uID'],'account'=>$group['uAccount'],'nickname'=>$group['uNickName'],'password'=>$group['uPassWord'],'rid'=>$group['RID'],'description'=>$group['Description']);
			array_push($array,$tmp);
		}
		$resultJson=Array("code"=>0,"msg"=>"查询成功","count"=>$count,"data"=>$array);
	}
	echo json_encode($resultJson);
?>