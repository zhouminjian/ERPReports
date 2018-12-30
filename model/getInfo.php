<?php
session_start();
if(!isset($_SESSION['uid'])){
	$resultJson=Array("code"=>100,"msg"=>"failed");
}
else{
	$uid = $_SESSION['uid'];
	$account = $_SESSION['account'];
	$password = $_SESSION['password'];
	$nickname = $_SESSION['nickname'];
	$mid = $_SESSION['MID'];
	$menuname = $_SESSION['MenuName'];
	$parentid = $_SESSION['ParentID'];
	$mfun = $_SESSION['Mfun'];
	$webpage = $_SESSION['WebPage'];
	$array = array('uid'=>$uid,'account'=>$account,'password'=>$password,'nickname'=>$nickname,'mid'=>$mid,'menuname'=>$menuname,'parentid'=>$parentid,'mfun'=>$mfun,'webpage'=>$webpage);
	$resultJson=Array("code"=>101,"msg"=>"successed","data"=>$array);	
}
echo json_encode($resultJson);
?>