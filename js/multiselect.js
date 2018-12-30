function mulselect(){
	var formSelects = layui.formSelects;
	formSelects.config('select1', {
		type: 'get',				//请求方式: post, get, put, delete...
		header: {},					//自定义请求头
		data: {},					//自定义除搜索内容外的其他数据
		searchUrl: '',				//搜索地址, 默认使用xm-select-search的值, 此参数优先级高
		searchName: 'keyword',		//自定义搜索内容的key值
		searchVal: '',				//自定义搜索内容, 搜素一次后失效, 优先级高于搜索框中的值
		keyName: 'name',			//自定义返回数据中name的key, 默认 name
		keyVal: 'value',			//自定义返回数据中value的key, 默认 value
		keySel: 'selected',			//自定义返回数据中selected的key, 默认 selected
		keyDis: 'disabled',			//自定义返回数据中disabled的key, 默认 disabled
		keyChildren: 'children',	//联动多选自定义children
		delay: 500,					//搜索延迟时间, 默认停止输入500ms后开始搜索
		direction: 'auto',			//多选下拉方向, auto|up|down
		response: {
			statusCode: 0,          //成功状态码
			statusName: 'code',     //code key
			msgName: 'msg',         //msg key
			dataName: 'data'        //data key
		},
		success: function(id, url, searchVal, result){		//使用远程方式的success回调
			console.log(id);		//组件ID xm-select
			console.log(url);		//URL
			console.log(searchVal);	//搜索的value
			console.log(result);	//返回的结果
		},
		error: function(id, url, searchVal, err){			//使用远程方式的error回调
			//同上
			console.log(err);	//err对象
		},
		beforeSuccess: function(id, url, searchVal, result){		//success之前的回调, 干嘛呢? 处理数据的, 如果后台不想修改数据, 你也不想修改源码, 那就用这种方式处理下数据结构吧
			console.log(id);		//组件ID xm-select
			console.log(url);		//URL
			console.log(searchVal);	//搜索的value
			console.log(result);	//返回的结果
			
			return result;	//必须return一个结果, 这个结果要符合对应的数据结构
		},
		beforeSearch: function(id, url, searchVal){			//搜索前调用此方法, return true将触发搜索, 否则不触发
			if(!searchVal){//如果搜索内容为空,就不触发搜索
				return false;
			}
			return true;
		},
		clearInput: false, 			//当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
	}, true);
	formSelects.filter('select1', function(id, inputVal, val, isDisabled){
	    if(
	    	PY.fullPY(val.name).toLowerCase().indexOf(inputVal) != -1 ||	//拼音全拼是否包含
	    	PY.fullPY(val.name, true).indexOf(inputVal) != -1 ||			//拼音简拼是否包含
	    	val.name.indexOf(inputVal) != -1 								//文本是否包含
	   	){
	    	return false;
	    }
	    return true;
	});
}