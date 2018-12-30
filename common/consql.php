<?php
/*
 * sqlserver数据库连接
 */
class Connect{
	private $uid = "填写你的ERP数据库用户名"; //数据库用户名
	private $pwd = "你的ERP数据库密码";//数据库密码
	private $dbName = "你的ERP数据库名称";//数据库名
	//连接sqlserver数据库 
	function sqlConnect(){
	    $serverName = "服务器IP,1433";//数据库服务器地址
	    $connectionInfo = array("Database"=>$this->dbName,"UID"=>$this->uid,"PWD"=>$this->pwd);
	    try{
	    	//数据库连接
			$conn = sqlsrv_connect($serverName,$connectionInfo);
			return $conn;
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	} 
	//关闭sqlserver数据库连接
	function sqlClose($conn){
		try{
			sqlsrv_close($conn);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
}

/*
 * mysql数据库连接
 */
class ConnectMy{
	private $uid = 'root';//数据库用户名
	private $pwd = '';//数据库密码
	private $dbName = 'report_admin';//数据库名称
	private $serverName = '127.0.0.1';
	//连接mysql数据库
	function sqlConnect(){
		try{
			$con = mysqli_connect($this->serverName,$this->uid,$this->pwd,$this->dbName);
			return $con;
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
	//关闭mysql数据库连接
	function sqlClose($con){
		try{
			mysqli_close($con);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
}
?>