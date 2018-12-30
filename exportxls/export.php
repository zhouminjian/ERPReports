<?php
	//记录查询条件
	include_once "../common/consql.php";
	include_once "../phpexcel/PHPExcel.php";
	include_once "../phpexcel/PHPExcel/Writer/Excel2007.php";
	include_once "../phpexcel/PHPExcel/Writer/Excel5.php";
	include_once "../phpexcel/PHPExcel/IOFactory.php";
	session_start();
	if(!isset($_SESSION['uid'])||!isset($_SESSION['SQL'])){
		exit("<b>非法访问！<br>错误代码100！</b><a href='/report'>返回首页</a>");
	}
	else{
		$SQL = $_SESSION['SQL'];
		$conClass = new Connect();
		$conn = $conClass->sqlConnect();
		$result = sqlsrv_query($conn,$SQL);
		//初始化表格
		ob_start();
		$objPHPExcel = new \PHPExcel();
		$setActiveSheet = $objPHPExcel->setActiveSheetIndex(0);
		$line = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$start = ord('A');
			$column = 0;
			if($line == 1){//第一行为表头--数据库中的英文字段
				foreach($row as $key=>$value){
					$setActiveSheet->setCellValue(chr($start+$column).$line, $key);
					$column++;
				}
				$line++;
				//设置表头后重置
				$start = ord('A');
				$column = 0;
			}
			foreach($row as $key=>$value){
				$setActiveSheet->setCellValue(chr($start+$column).$line, mb_convert_encoding($value,"utf-8","gbk"));
				$column++;
			}
			$line++;
		}
		$conClass->sqlClose($conn);
		$fileName = iconv("utf-8", "gb2312", date("Ymd"));//文件名
		$objPHPExcel->getActiveSheet()->removeColumn(chr($start+$column-1));//删除序号列
		$objPHPExcel->getActiveSheet()->setTitle('sheet1');//命名工作薄
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  
		header("Content-Disposition:attachment;filename='".$fileName.".xlsx'");  
		header('Cache-Control:max-age=0'); 
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		ob_end_clean();
		$objWriter->save("php://output");
		exit; 
	}
?>