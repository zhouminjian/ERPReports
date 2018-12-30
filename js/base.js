function showtime(laydate) {
	//日选择器
	laydate.render({
		elem: '#startdate',
	});
	laydate.render({
		elem: '#enddate',
	});
	//月选择器
	laydate.render({
		elem: '#startmon',
		type: 'month'
	});
	laydate.render({
		elem: '#endmon',
		type: 'month'
	});
	//年选择器
	laydate.render({
		elem: '#startyear',
		type: 'year'
	});
	laydate.render({
		elem: '#endyear',
		type: 'year'
	});
}
var vChart;
function showform(form, rp) {
	form.render();
	//监听提交
	form.on('submit(formsubmit)', function(data) {
		var dt = JSON.stringify(data.field);
		var date=new Date;
		var nowYear = date.getFullYear();
		var nowMonth = nowYear+'-'+(date.getMonth()+1);
		var nowDay = nowMonth+'-'+date.getDate();
		var chartTitle;
		if(rp == 'cl'||rp == 'clywlb'||rp == 'pbrk'||rp == 'pbck'||rp == 'cprk'||rp == 'cpck'||rp == 'clordernum'){//动态修改标题
			if(data.field.datetype == '1'){
				chartTitle = "日";
				var d1 = data.field.startdate;
				var d2 = data.field.enddate;
				if(d1>d2){
					layer.msg("起始日期大于结束日期");
					return false;
				}
				else if(d2>nowDay){
					layer.msg("结束日期大于当前日期");
					return false;
				}
			}
			else if(data.field.datetype == '2'){
				chartTitle = "月";
				var d1 = data.field.startmon;
				var d2 = data.field.endmon;
				if(d1>d2){
					layer.msg("起始月份大于结束月份");
					return false;
				}
				else if(d2>nowMonth){
					layer.msg("结束月份大于当前月份");
					return false;
				}
			}
			else if(data.field.datetype == '3'){
				chartTitle = "年";
				var d1 = data.field.startyear;
				var d2 = data.field.endyear;
				if(d1>d2){
					layer.msg("起始年份大于结束年份");
					return false;
				}
				else if(d2>nowYear){
					layer.msg("结束年份大于当前年份");
					return false;
				}
			}
		}
		else if(rp == 'cldept'||rp == 'clcustomer'||rp == 'ddnum'||rp == 'ddcustomer'||rp == 'dddept'||rp == 'pbrkcu'||rp == 'pbckcu'||rp == 'cpckcu'||rp == 'cprkcu'){
			if(data.field.datetype == '2'){
				chartTitle = "月";
				var d1 = data.field.startmon;
				if(d1>nowMonth){
					layer.msg("选择的月份大于当前月份");
					return false;
				}
			}
			else if(data.field.datetype == '3'){
				chartTitle = "年";
				var d1 = data.field.startyear;
				if(d1>nowYear){
					layer.msg("选择的年份大于当前年份");
					return false;
				}
			}
		}
		var lx = data.field.shape;
		if(lx != '999'){
			$.ajax({
				type: "POST",
				url: "controller/json.php?type=" + rp,
				data: {
					'data': dt
				},
				success: function(result) {
					console.log(result);
					if(lx == "1"){
						lx = "bar";
					}else if(lx == "2"){
						lx = "line";
					}else if(lx == "3"){
						lx = "pie";
					}
					result = JSON.parse(result);
					if (vChart != null && vChart != "" && vChart != undefined) {
				        vChart.dispose();
				    }
					vChart = echarts.init(document.getElementById('showecharts'));
					var date = [],num = [];
					date.push(result.xAxis);//横坐标赋值
					num.push(result.yAxis);//纵坐标赋值
					//相关项进行设置，搭图表骨架
					funName = rp+'Option';
					var option = eval(funName+'(date,num,rp,chartTitle,lx)');
					vChart.setOption(option);
				}
			});
		}
		else{//999显示为表格
			$.ajax({
				type:'post',
				url:'model/notecondition.php',
				data:{'data':dt},
				success:function(){
					layui.use('table', function(){
						var table = layui.table;
						table.render({
						  	elem:'#result',
							url:"controller/table/"+rp+"table.php",
							title:'查询结果',
							cols:[[
								{field:'NameC',title:'客户',width:300,align:'center',templet:'<div><span title="{{d.NameC}}">{{d.NameC}}</span></div>'},
								{field:'MRolls',title:'数量',width:200,align:'center',templet:'<div><span title="{{d.MRolls}}">{{d.MRolls}}</span></div>'},
							]],
						  	even:true,
						  	page:true,
						  	limits: [15,30,60,100],
						  	limit: 15,
						  	done:function(res, curr, count){
						  		
						  	}
						});
					});
				}
			});
		}
	});
}