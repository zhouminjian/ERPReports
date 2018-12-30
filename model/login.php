<?php
//验证登录
include_once "../common/sec.php";
include_once "../common/consql.php";
$data = json_decode(stripslashes($_POST['data']),true);
$account = $data['account'];
$password = encryptDecrypt($data['password'],0);

$conClass = new ConnectMy();
$con = $conClass->sqlConnect();
mysqli_query($con,"set names 'utf8'");
$searchSQL = "select u.*,M.* from (select * from user where uAccount='$account' and uPassWord='$password') u
left join userroles ur on u.uID=ur.UID
left join rolepermissions rp on rp.RID=ur.RID
left join menu m on m.MID=rp.PID;";
$result = mysqli_query($con,$searchSQL);
$conClass->sqlClose($con);
if($result->num_rows>0){
	session_start();
	$MID = array();
	$MenuName = array();
	$ParentID = array();
	$Mfun = array();
	$WebPage = array();
	while($group = $result->fetch_assoc()){
		$_SESSION['uid'] = $group['uID'];
		$_SESSION['account'] = $group['uAccount'];
		$_SESSION['password'] = trim(encryptDecrypt($group['uPassWord'],1));
		$_SESSION['nickname'] = $group['uNickName'];
		array_push($MID,$group['MID']);
		array_push($MenuName,$group['Menuname']);
		array_push($ParentID,$group['ParentID']);
		array_push($Mfun,$group['Mfun']);
		array_push($WebPage,$group['WebPage']);
	}
	$_SESSION['MID'] = $MID;
	$_SESSION['MenuName'] = $MenuName;
	$_SESSION['ParentID'] = $ParentID;
	$_SESSION['Mfun'] = $Mfun;
	$_SESSION['WebPage'] = $WebPage;
	echo "index.html";
}
else{
	echo  false;
}

?>