function userAction(action){
	//获取前端点击事件，按需执行模块
	switch(action){
		case 'VfLog' : VfLog();break;//是否登录
		case 'Load' : Load();break;//页面加载模块
		case 'Logout' : Logout();break;//注销模块
	}
}

function VfLog(){
	//进入login页面判断是否已登录，是-跳转index页面，否-等待登录
	$.ajax({
		url: 'model/getInfo.php',
		data: '',
		type: 'post',
		success: function(result){
			result = JSON.parse(result);
			if(result.code == 101) {
				location.href = "index.html";
			}
		}
	});
}

function Load(){
	//发起ajax请求，获取用户昵称并显示，
	//根据用户权限显示对应菜单
	$.ajax({
		url: 'model/getInfo.php',
		data: '',
		type: 'post',
		success: function(result){
			result = JSON.parse(result);
//			console.log(result.code);
			if(result.code == 101) {
				$('#nickname span').text(result.data.nickname);
				var basestr=null;
				for(var k=0;k<result.data.mid.length;k++){
					//向页面添加元素标签
					if(result.data.parentid[k]<0){
						//父节点负数则为用户菜单
						str = "<dd><a href='javascript:;' onclick='"+result.data.mfun[k]+";'>"+result.data.menuname[k]+"</a></dd>"
						$("#add").append(str);
						var script = document.createElement("script");
						script.type = "text/javascript";
						if(result.data.parentid[k] == -1){
							code = "function "+result.data.mfun[k]+"{$('#showRp').load('"+result.data.webpage[k]+"');}";
						}
						else if(result.data.parentid[k] == -2){
							code = "function "+result.data.mfun[k]+"{window.location.href='"+result.data.webpage[k]+"';}";
						}
						try {
					        script.appendChild(document.createTextNode(code));
					    } catch (ex) {
					        script.text = code;
					    }
					    document.body.appendChild(script);
					}
					else{
						if(result.data.parentid[k] == 0){
							//如果为父节点，遍历找到他所有的子节点
							basestr += "<li class='layui-nav-item'>"+
										"<a href='javascript:;'>"+result.data.menuname[k]+"</a>"+
											"<dl class='layui-nav-child'>";
							for(var j=0;j<result.data.mid.length;j++){
								if(result.data.parentid[j] == result.data.mid[k]){
									//与某个节点的父节点匹配，则并入元素标签
									basestr +="<dd><a href='javascript:;' onclick='"+result.data.mfun[j]+";'>"+result.data.menuname[j]+"</a></dd>";
									var script = document.createElement("script");
									script.type = "text/javascript";
									var code = "function "+result.data.mfun[j]+"{$('#showRp').load('"+result.data.webpage[j]+"');}";
									try {
								        script.appendChild(document.createTextNode(code));
								    } catch (ex) {
								        script.text = code;
								    }
								    document.body.appendChild(script);
								}
							}
							basestr += "</dl></li>";
						}
					}
				}
				$("#dnmenu").append(basestr);
				layui.use('element', function() {
					var element = layui.element;
					element.init();
				});
			} else if(result.code == 100){
				location.href = "login.html";
			}
		}
	});
	
}

function AddUser(form){
	//存放权限
	var oldpid = new Array();
	//查询用户功能
	form.on('submit(searchuser)',function(data){
		data = JSON.stringify(data.field);
		$.ajax({
			type:"post",
			url:"model/notecondition.php",//记录查询条件
			data:{'data':data},	
			success:function(){
				layui.use('table', function(){
					var table = layui.table;
					table.render({
					  	elem:'#tableresult',
						url:"model/searchUser.php",
						title:'查询结果',
						cols:[[
							{field:'id',title:'ID',width:60,sort:true,align:'center'},
							{field:'account',title:'用户名',width:150,align:'center',templet:'<div><span title="{{d.account}}">{{d.account}}</span></div>'},
							{field:'nickname',title:'昵称',width:150,align:'center',templet:'<div><span title="{{d.nickname}}">{{d.nickname}}</span></div>'},
							{field:'description',title:'角色',width:150,align:'center',templet:'<div><span title="{{d.description}}">{{d.description}}</span></div>'},	
							{fixed:'right',title:'操作',width:115,align:'center', toolbar: '#optionbar'}
						]],
					  	even:true,
					  	page:true,
					  	limits: [15,30,60,100],
					  	limit: 15,
					});
					table.on('tool(result)', function(obj){
						data = JSON.stringify(obj.data);
						if(obj.event === 'del'){
							layer.confirm('确定删除用户？',{btn:['确定','取消']},function(index){
								layer.close(index);
								$.ajax({
						        	type:"post",
						        	url:"model/deleteuser.php",
						        	data:{'data':data},
						        	success:function(result){
						        		result = JSON.parse(result);
						        		if(result.code==120){
						        			obj.del();
						        		}
						        		layer.msg(result.msg);
						        	}
						        });
							},function(){
								layer.msg("已取消");
							});
						}
						else if(obj.event === 'edit'){
							//编辑用户
							$.ajax({
					    		type:"post",
					        	url:"model/noteindexinfo.php",
					        	data:{'data':data},
					        	success:function(result){
					        		result = JSON.parse(result);
					        		if(result.code == 122){
					        			layer.open({
								     		type:2,
								     		title:false,
								     		closeBtn:1,
								     		shade: [0],
								     		anim: 2,
								     		area: ['450px', '450px'],
								     		content: ['view/User/alertuser.html', 'yes'],
								     		end: function () {
								     			$(".layui-laypage-btn")[0].click();
								     		}
								     	});
					        		}
					        		else{
					        			layer.msg(result.msg);
					        		}
					        	}
					    	});
						}
					});
				});
			}
		});
		return false;
	});
	//ajax请求，判断用户是否有新增权限，然后再执行新增操作
	form.on('submit(adduser)', function(data){
		if(data.field.account.match(/^[0-9a-zA-Z]\w{5,17}$/)!=data.field.account){
			layer.msg('非法的用户名');
			return false;
		}
		data = JSON.stringify(data.field);
		$.ajax({
			type:"post",
			url:"model/insertInfo.php",
			data: {'data':data},
			success: function(result){
				result = JSON.parse(result);
				layer.msg(result.msg);
			}
		});
		return false;
	});
	//权限全选功能
	form.on('checkbox(allcheck)', function(data){
		var a = data.elem.checked;
		if(a == true){
			$(".group").prop("checked", true);
		}else{
			$(".group").prop("checked", false);
		}
		form.render('checkbox');
	});
	//选择角色显示权限功能
	form.on('select(rolenames)',function(data){
		//清空存储的角色权限
		oldpid = [];
		//清空选项
		$("input[class='group']").prop("checked",false);
		$("input[name='allcheck']").prop("checked",false);
		form.render();
		//选择项非第一选项
		if(data.value){
			data = JSON.stringify(data);
			$.ajax({
				type:"post",
				url:"model/getRoleAuth.php",
				data:{'data':data},
				success: function(result){
					result = JSON.parse(result);
					for(let j in result.data){
						$("[name='group"+result.data[j]['pid']+"']").prop("checked",true);
						oldpid[j] = result.data[j]['pid'];
					}
					form.render();
				}
			});	
		}
	});
	//确认修改角色权限
	form.on('submit(alertauth)',function(data){
		layer.confirm('确定修改权限？',{btn:['确定','取消']},function(){
			var newpid = new Array();//存放新权限
			var notexistpid = new Array();//存放已取消的权限
			var rid = 0;
			for(let j in data.field){
				//筛选新权限
				if(j == 'rolenames'){
					//获取角色id
					rid = data.field[j];
				}
				else{
					//提取角色新权限
					var pid = data.field[j];
					var flag = true;//flag:true--新权限，false--旧权限
					for(let k in oldpid){
						if(oldpid[k] == pid){
							flag = false;
							break;
						}
					}
					if(flag){
						newpid.push(pid);
					}
				}
			}
			for(let j in oldpid){
				//筛选取消的权限
				var pid = oldpid[j];
				var flag = true;//true--取消的旧权限，false--未取消的权限
				for(let k in data.field){
					if(k != 'rolenames' && flag){
						if(pid == data.field[k]){
							flag = false;
							break;
						}
					}
					else if(k == 'rolenames'){
						rid = data.field[k];
					}
				}
				if(flag){
					notexistpid.push(pid);
				}
			}
			if((newpid.length || notexistpid.length) && rid){
				//修改一个选项就更新
				$.ajax({
					type:"post",
					url:"model/updateAuth.php?rid="+rid,
					data:{'newpid':JSON.stringify(newpid),'notexistpid':JSON.stringify(notexistpid)},
					success:function(result){
						result = JSON.parse(result);
						layer.msg(result.msg);
					}
				});
			}
			else{
				layer.msg("未修改任何权限");
			}
			oldpid = data.field;
		},function(){
			layer.msg("已取消");
		});
		return false;
	});
	//删除角色
	form.on('submit(delroles)',function(data){
		layer.confirm('确认删除角色？',{btn:['确认','取消']},function(){
			var rid = JSON.stringify(data.field['rolenames']);
			$.ajax({
				type:"post",
				url:"model/findUR.php",
				data:{'data':rid},
				success:function(result){
					result = JSON.parse(result);
					layer.msg(result.msg);
				}
			});
		},function(){
			layer.msg("已取消");
		});
		return false;
	});
	//新增角色及权限
	form.on('submit(addrole)',function(data){
		if(data.field.rolename.match(/^[A-Za-z0-9\u4e00-\u9fa5]+$/)!=data.field.rolename){
			layer.msg('非法的角色名');
			return false;
		}
		data = JSON.stringify(data.field);
		$.ajax({
			type:"post",
			url:"model/insertRole.php",
			data: {'data':data},
			success: function(result){
				result = JSON.parse(result);
				layer.msg(result.msg);
			}
		});
		return false;
	});
	//响应打开投屏参数层
	form.on('submit(screenparam)',function(data){
		layer.open({
			type:1,
	        title:"新增投屏参数",
	        area:["30%","35%"],
	        content:$("#adddiv"),
	        success:function(){
	        	document.getElementById("addScreenParam").reset();
	        	$(".layui-layer-shade").removeClass();
	        }
		});
	});
	//添加屏参
	form.on('submit(addparam)',function(data){
		data = JSON.stringify(data.field);
		$.ajax({
			type:"post",
			url:"model/addScreenP.php",
			data:{'data':data},
			success:function(result){
				result = JSON.parse(result);
				if(result.code == 131){
					layer.close(1); 
					layer.msg(result.msg);
					//更新table
					$(".layui-laypage-btn")[0].click();
				}
				else{
					layer.close(1); 
					layer.msg("新增失败");
				}
			}
		});
	});
}

function UpdateInfo(form){
	//ajax，判断用户更新的内容是否合规，若修改密码，成功则退出重新登录
	form.on('submit(update)', function(data){
		var oldpwd = data.field.opwd;
		if(data.field.npwd.match(/^[0-9a-zA-Z]\w{4,12}$/) != data.field.npwd){
			layer.msg('新密码不合法');
			return false;
		}
		$.ajax({
			url: 'model/getInfo.php',
			data: '',
			type: 'post',
			success: function(result){
				result = JSON.parse(result);
				if(result.code == 101) {
					//判断旧密码成功
					if(result.data.password == oldpwd){
						if(oldpwd == data.field.npwd){
							layer.msg('旧密码与新密码一样');
							return false;
						}
						if(data.field.npwd == data.field.cfmpwd){//判断新密码两次是否一致
							data = JSON.stringify(data.field);
//							console.log(data);
							//提交修改请求
							$.ajax({
								url: 'model/update.php',
								data: {'data':data},
								type: 'post',
								success: function(result1){
									result1 = JSON.parse(result1);
									if(result1.code == 103){
										$.ajax({
											url: 'model/logout.php',
											data: '',
											type: 'post',
											success: function(result2){
												result2 = JSON.parse(result2);
												if(result2.code == 110){
													layer.msg(result1.msg,{time:1000},function(){
														location.href = "login.html";
													});
												}
											}
										});
									}
									else{
										layer.msg(result1.msg);
									}
								}
							});
						}
						else{
							layer.msg('两次密码不一致');
						}
					}
					else{
						layer.msg('原密码错误');
					}
				}
			}
		});
		return false;
	});
}

function Logout(){
	//直接执行注销模块
	layui.use("layer",function(){
		$.ajax({
			url: 'model/logout.php',
			data: '',
			type: 'post',
			success: function(result){
				result = JSON.parse(result);
				if(result.code == 110) {
					layer.msg(result.msg,{time:1000},function(){
						location.href = "login.html";
					});
				}
			}
		});
		return false;
	});
}

function Login(form){
//验证登录模块
	form.render();
	form.on('submit(login)', function(data) {
		if(data.field.account.match(/^[0-9a-zA-Z]\w{5,17}$/)!=data.field.account){
			layer.msg('非法的用户名');
			return false;
		}
		if(data.field.password!=data.field.password.match(/^[0-9a-zA-Z]\w{4,12}$/)){
			layer.msg('密码中有非法字符');
			return false;
		}
		data = JSON.stringify(data.field);
		$.ajax({
			url: 'model/login.php',
			data: {'data':data},
			type: 'post',
			success: function(result){
				if(result) {
					location.href = result;
				} else {
					layer.msg('用户名或密码错误');
				}
			}
		});
		return false;
	})
}