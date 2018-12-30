//计算产量
function sum(num){
	var s = eval(num.join('+'));
	//判断结果是否为空
	if(typeof(s) == "undefined"){
		s=0;
	}
	return s;
}
//产量报表渲染
function clOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'产量报表',
			subtext:'总产量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:['产量'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'产量',  
		}],
		series:[
		{
			name:'产量',
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//业务类别报表渲染
function clywlbOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'产量报表',
			subtext:'总产量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:['产量'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'产量',
		}],
		series:[
		{
			name:'产量',
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//部门产量报表渲染(废弃)
function cldeptOption1(date,num,rp,chartTitle,lx){
	if(lx == "bar"){
		var option = {
			title:{
				text: '各部门'+chartTitle+'产量报表',
				subtext:'总产量：'+sum(num[0]).toFixed(2)+'m',
				x:'center',
			},
			tootip:{
				show:true
			},
			legend:{
				data:['产量'],
				x:'left',
			},
			xAxis:[{
				type:'category',
				data:date[0],
				axisLabel:{  
	                 interval:0,  
	                 rotate:-45,//0度角倾斜显示  
	           },
			}],
			yAxis:[{
				type:'value',
				axisLabel:{  
	                 interval:0,//横轴信息全部显示  
	                 rotate:0,//0度角倾斜显示  
	                 formatter:'{value}m',//纵坐标添加单位
	            },
            	name:chartTitle+'产量',
			}],
			series:[
			{
				name:'产量',
				type:lx,
				data:num[0],
				barMaxWidth:30,//柱宽最大30
				label:{
					normal: {
						show: true,
						position: 'top',
						textStyle:{
							color:'black'
						}
					}
				},
				itemStyle:{
	            	normal: {
	                    color: new echarts.graphic.LinearGradient(
	                        0, 0, 0, 1,
	                        [
	                            {offset: 0, color: '#83bff6'},
	                            {offset: 0.5, color: '#188df0'},
	                            {offset: 1, color: '#188df0'}
	                        ]
	                    )//柱形图颜色渐变
	                },
	                emphasis: {
	                    color: new echarts.graphic.LinearGradient(
	                        0, 0, 0, 1,
	                        [
	                            {offset: 0, color: '#2378f7'},
	                            {offset: 0.7, color: '#2378f7'},
	                            {offset: 1, color: '#83bff6'}
	                        ]
	                    )
	                }//鼠标悬浮在柱形图上改变颜色
	            }
			}]
		};
	}
	else if(lx == "pie"){ //饼图
		var seriesData = [];
		//参数合并
		for ( var i = 0; i <date[0].length; i++){
		    seriesData.push({
				name:date[0][i],
				value:num[0][i]
			});
		}
		var option = {
		    title : {
		        text: '各部门'+chartTitle+'产量报表',
		        subtext:'总产量：'+sum(num[0]).toFixed(2)+'m',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{b} <br/>{c} {a} : ({d}%)"
		    },
		    legend: {
		        type: 'scroll',
		        orient: 'vertical',
		        right: 10,
		        top: 20,
		        data: date[0],
		        selected: false
		    },
		    series : [
		        {
		            name: '占比',
		            type: 'pie',
		            radius : '55%',
		            center: ['40%', '50%'],
		            data: seriesData,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
	}
	return option;
}
//客户产量报表渲染
function clcustomerOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '客户'+chartTitle+'产量前20汇总表',
			subtext:'总产量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:['产量'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'产量',  
		}],
		series:[
		{
			name:"产量",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//订单量渲染
function clordernumOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'经销、加工订单量汇总',
			subtext:'经销单总产量：'+sum(num[0][0]).toFixed(2)+'，加工单总产量：'+sum(num[0][1]).toFixed(2)+'，双经销总产量：'+sum(num[0][2]).toFixed(2),
			x:'center',
		},
		color: ['#6A5ACD','#63B8FF','#4cabce'],
		tooltip: {
	        trigger: 'axis',
	        axisPointer: {
	            type: 'shadow'
	        }
	    },
		legend:{
			data:[chartTitle+'经销单',chartTitle+'加工单',chartTitle+'双经销'],
			x:'right',
		},
		toolbox: {
	        show: true,
	        orient: 'vertical',
	        left: 'right',
	        top: 'center',
	        feature: {
	            saveAsImage: {show: true}
	        }
	    },
    	calculable: true,
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'订单量', 
		}],
		series:[
		{
			name:chartTitle+"经销单",
			type:lx,
			data:num[0][0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		},
		{
			name:chartTitle+"加工单",
			type:lx,
			data:num[0][1],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		},
		{
			name:chartTitle+"双经销",
			type:lx,
			data:num[0][2],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		}]
	};
	return option;
}
//订单数渲染
function ddnumOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'经销、加工订单量汇总',
			subtext:'经销单单数：'+sum(num[0][0]).toFixed(0)+'，加工单单数：'+sum(num[0][1]).toFixed(0)+'，双经销单数：'+sum(num[0][2]).toFixed(0),
			x:'center',
		},
		color: ['#6A5ACD','#63B8FF','#4cabce'],
		tooltip: {
	        trigger: 'axis',
	        axisPointer: {
	            type: 'shadow'
	        }
	    },
		legend:{
			data:[chartTitle+'经销单',chartTitle+'加工单',chartTitle+'双经销'],
			x:'right',
		},
		toolbox: {
	        show: true,
	        orient: 'vertical',
	        left: 'right',
	        top: 'center',
	        feature: {
	            saveAsImage: {show: true}
	        }
	    },
    	calculable: true,
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}',//纵坐标添加单位
            },
            name:chartTitle+'订单数',   
		}],
		series:[
		{
			name:chartTitle+"经销单",
			type:lx,
			data:num[0][0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		},
		{
			name:chartTitle+"加工单",
			type:lx,
			data:num[0][1],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		},
		{
			name:chartTitle+"双经销",
			type:lx,
			data:num[0][2],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'insideBottom',
					textStyle:{
						color:'black'
					},
					distance: 15,
					align: 'left',
					verticalAlign: 'middle',
					rotate: 90,
					formatter: '{c} {name|{a}}',
					fontSize: 16,
					rich: {
						name:{
							textBorderColor: '#fff'
						}
					}
				}
			},
		}]
	};
	return option;
}
//客户订单量渲染
function ddcustomerOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '客户'+chartTitle+'订单量前20汇总表',
			subtext:'总订单量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'订单量'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'订单量',   
		}],
		series:[
		{
			name:chartTitle+"订单量",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//部门订单报表渲染(废弃)
function dddeptOption1(date,num,rp,chartTitle,lx){
	if(lx == "bar"){
		var option = {
			title:{
				text: '各部门'+chartTitle+'订单报表',
				subtext:'总订单量：'+sum(num[0]).toFixed(2)+'m',
				x:'center',
			},
			tootip:{
				show:true
			},
			legend:{
				data:[chartTitle+'订单量'],
				x:'left',
			},
			xAxis:[{
				type:'category',
				data:date[0],
				axisLabel:{  
	                 interval:0,  
	                 rotate:-45,//0度角倾斜显示  
	           },
			}],
			yAxis:[{
				type:'value',
				axisLabel:{  
	                 interval:0,//横轴信息全部显示  
	                 rotate:0,//0度角倾斜显示  
	                 formatter:'{value}m',//纵坐标添加单位
	            },
            	name:chartTitle+'订单量',   
			}],
			series:[
			{
				name:chartTitle+"订单量",
				type:lx,
				data:num[0],
				barMaxWidth:30,//柱宽最大30
				label:{
					normal: {
						show: true,
						position: 'top',
						textStyle:{
							color:'black'
						}
					}
				},
				itemStyle:{
	            	normal: {
	                    color: new echarts.graphic.LinearGradient(
	                        0, 0, 0, 1,
	                        [
	                            {offset: 0, color: '#83bff6'},
	                            {offset: 0.5, color: '#188df0'},
	                            {offset: 1, color: '#188df0'}
	                        ]
	                    )//柱形图颜色渐变
	                },
	                emphasis: {
	                    color: new echarts.graphic.LinearGradient(
	                        0, 0, 0, 1,
	                        [
	                            {offset: 0, color: '#2378f7'},
	                            {offset: 0.7, color: '#2378f7'},
	                            {offset: 1, color: '#83bff6'}
	                        ]
	                    )
	                }//鼠标悬浮在柱形图上改变颜色
	            }
			}]
		};
	}
	else if(lx == "pie"){ //饼图
		var seriesData = [];
		//参数合并
		for ( var i = 0; i <date[0].length; i++){
		    seriesData.push({
				name:date[0][i],
				value:num[0][i]
			});
		}
		var option = {
		    title : {
		        text: '各部门'+chartTitle+'订单报表',
		        subtext:'总订单量：'+sum(num[0]).toFixed(2),
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{b} <br/>{c} {a} : ({d}%)"
		    },
		    legend: {
		        type: 'scroll',
		        orient: 'vertical',
		        right: 10,
		        top: 20,
		        data: date[0],
		        selected: false
		    },
		    series : [
		        {
		            name: '占比',
		            type: 'pie',
		            radius : '55%',
		            center: ['40%', '50%'],
		            data: seriesData,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
	}
	return option;
}
//坯布入库报表渲染
function pbrkOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'坯布入库报表',
			subtext:'总入库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'入库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'入库量',   
		}],
		series:[
		{
			name:chartTitle+"入库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//坯布入库客户报表渲染
function pbrkcuOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'坯布入库(Top15客户)报表',
			subtext:'总入库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'入库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'入库量',   
		}],
		series:[
		{
			name:chartTitle+"入库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//坯布出库报表渲染
function pbckOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'坯布出库报表',
			subtext:'总出库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'出库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'出库量',  
		}],
		series:[
		{
			name:chartTitle+"出库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//坯布出库客户报表渲染
function pbckcuOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: chartTitle+'坯布出库(Top15客户)报表',
			subtext:'总出库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'出库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'出库量',   
		}],
		series:[
		{
			name:chartTitle+"出库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//成品入库报表渲染
function cprkOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '成品'+chartTitle+'入库报表',
			subtext:'总入库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'入库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'入库量',  
		}],
		series:[
		{
			name:chartTitle+"入库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//成品入库（客户）报表渲染
function cprkcuOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '成品'+chartTitle+'入库（Top15客户）报表',
			subtext:'总入库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'入库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'入库量',  
		}],
		series:[
		{
			name:chartTitle+"入库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//成品出库报表渲染
function cpckOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '成品'+chartTitle+'出库报表',
			subtext:'总出库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'出库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'出库量',  
		}],
		series:[
		{
			name:chartTitle+"出库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//成品出库(客户)报表渲染
function cpckcuOption(date,num,rp,chartTitle,lx){
	var option = {
		title:{
			text: '成品'+chartTitle+'出库（Top15客户）报表',
			subtext:'总出库量：'+sum(num[0]).toFixed(2)+'m',
			x:'center',
		},
		tootip:{
			show:true
		},
		legend:{
			data:[chartTitle+'出库'],
			x:'left',
		},
		xAxis:[{
			type:'category',
			data:date[0],
			axisLabel:{  
                 interval:0,  
                 rotate:-45,//0度角倾斜显示  
           },
		}],
		yAxis:[{
			type:'value',
			axisLabel:{  
                 interval:0,//横轴信息全部显示  
                 rotate:0,//0度角倾斜显示  
                 formatter:'{value}m',//纵坐标添加单位
            },
            name:chartTitle+'出库量',  
		}],
		series:[
		{
			name:chartTitle+"出库",
			type:lx,
			data:num[0],
			barMaxWidth:30,//柱宽最大30
			label:{
				normal: {
					show: true,
					position: 'top',
					textStyle:{
						color:'black'
					}
				}
			},
			itemStyle:{
            	normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )//柱形图颜色渐变
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }//鼠标悬浮在柱形图上改变颜色
            }
		}]
	};
	return option;
}
//部门产量双图表渲染
function cldeptOption(date,num,rp,chartTitle,lx){
	var total = sum(num[0]).toFixed(2);
	var pieArray = {};
	for(var i=0;i<date[0].length;i++){
		pieArray[date[0][i]] = Math.round(num[0][i]/total*10000)/100;
	}
	var option = {
	    tooltip: {},
	    title: [{
	        text: '部门'+chartTitle+'产量表',
	        subtext: '总计：' + total+'m',
	        x: '25%',
	        textAlign: 'center'
	    }, {
	        text: '占比图',
	        subtext: '单位：% ',
	        x: '75%',
	        textAlign: 'center'
	    }],
	    grid: [{
	        top: 100,
	        width: '50%',
	        bottom: '35%',
	        left: 10,
	        containLabel: true
	    }, {
	        top: '55%',
	        width: '50%',
	        bottom: 0,
	        left: 10,
	        containLabel: true
	    }],
	    xAxis: [{
	        type: 'category',
	        data: date[0],
	        axisLabel: {
	            interval: 0,
	            rotate: 45
	        },
	        splitLine: {
	            show: false
	        }
	    }],
	    yAxis: [{
	        type: 'value',
	        splitLine: {
	            show: false
	        }
	    }],
	    series: [{
	        type: 'bar',
	        stack: 'chart',
	        z: 3,
	        barMaxWidth:30,//柱宽最大30
	        label: {
	            normal: {
	                position: 'top',
	                show: true
	            }
	        },
	        data: num[0],
	    }, {
	        type: 'pie',
	        radius: [0, '40%'],
	        center: ['75%', '35%'],
	        data: Object.keys(pieArray).map(function(key){
	        	return {
	        		name:key,
	        		value:pieArray[key]
	        	}
	        }),
	    }]
	};
	return option;
}
//部门订单量双图表渲染
function dddeptOption(date,num,rp,chartTitle,lx){
	var total = sum(num[0]).toFixed(2);
	var pieArray = {};
	for(var i=0;i<date[0].length;i++){
		pieArray[date[0][i]] = Math.round(num[0][i]/total*10000)/100;
	}
	var option = {
	    tooltip: {},
	    title: [{
	        text: '部门'+chartTitle+'产量表',
	        subtext: '总计：' + total+'m',
	        x: '25%',
	        textAlign: 'center'
	    }, {
	        text: '占比图',
	        subtext: '单位：% ',
	        x: '75%',
	        textAlign: 'center'
	    }],
	    grid: [{
	        top: 100,
	        width: '50%',
	        bottom: '35%',
	        left: 10,
	        containLabel: true
	    }, {
	        top: '55%',
	        width: '50%',
	        bottom: 0,
	        left: 10,
	        containLabel: true
	    }],
	    xAxis: [{
	        type: 'category',
	        data: date[0],
	        axisLabel: {
	            interval: 0,
	            rotate: 45
	        },
	        splitLine: {
	            show: false
	        }
	    }],
	    yAxis: [{
	        type: 'value',
	        splitLine: {
	            show: false
	        }
	    }],
	    series: [{
	        type: 'bar',
	        stack: 'chart',
	        z: 3,
	        barMaxWidth:30,//柱宽最大30
	        label: {
	            normal: {
	                position: 'top',
	                show: true
	            }
	        },
	        data: num[0],
	    }, {
	        type: 'pie',
	        radius: [0, '40%'],
	        center: ['75%', '35%'],
	        data: Object.keys(pieArray).map(function(key){
	        	return {
	        		name:key,
	        		value:pieArray[key]
	        	}
	        }),
	    }]
	};
	return option;
}