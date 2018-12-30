function initInput(){
	$("#showecharts").empty();//清空div图表
	$("#startdate").removeAttr("lay-verify"); 
 	$("#enddate").removeAttr("lay-verify");
 	$("#startmon").removeAttr("lay-verify"); 
 	$("#endmon").removeAttr("lay-verify"); 
 	$("#startyear").removeAttr("lay-verify"); 
 	$("#endyear").removeAttr("lay-verify"); 
 	$("#startdate").val(""); 
 	$("#enddate").val(""); 
 	$("#startmon").val(""); 
 	$("#endmon").val(""); 
 	$("#startyear").val(""); 
 	$("#endyear").val(""); 
	$("#choosedate1").hide();
	$("#choosedate2").hide();
	$("#choosedate3").hide();
}
