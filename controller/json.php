<?php
//session检测
$type = $_GET["type"];//获取报表类型
$data = json_decode(stripslashes($_POST['data']),true);
$array = array();
//提取前端提交的查询条件
foreach($data as $key=>$value){
	if(!empty($value)){
		$array[$key] = $value;
	}
}
//引入操作对应文件
include_once "$type.php";
print_r($type($array));
//print_r($array);
?>