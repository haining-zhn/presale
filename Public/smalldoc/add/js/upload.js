$(function() {
	layui.use([ 'layer', 'element', 'form', 'upload' ], function() {
		var layer = layui.layer,
			element = layui.element,
			form = layui.form,
			upload = layui.upload;

		//创建监听函数
		var xhrOnProgress = function(fun) {
			xhrOnProgress.onprogress = fun; //绑定监听
			//使用闭包实现监听绑
			return function() {
				//通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
				var xhr = $.ajaxSettings.xhr();
				//判断监听函数是否为函数
				if (typeof xhrOnProgress.onprogress !== 'function')
					return xhr;
				//如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
				if (xhrOnProgress.onprogress && xhr.upload) {
					xhr.upload.onprogress = xhrOnProgress.onprogress;
				}
				return xhr;
			}
		}

		var uploadFile = upload.render({
			elem : '#upload', //绑定元素
			url : 'api/upload', //上传接口
			exts : 'jpg|png|jpeg',//限定上传类型
			//accept: images,//指定允许上传时校验的文件类型 images（图片）、file（所有文件）、video（视频）、audio（音频）
			acceptMime : 'image/jpg, image/png, image/jpeg',//只筛选上述类型图片
			//number： '0',//0为不限制上传数量
			xhr : xhrOnProgress,
			//data: {}, //可选项 额外的参数，如：{id: 123, abc: 'xxx'}
			//multiple: true,// 开启多文件上传
			//drag:true, //是否允许拖拽上传
			size: 1024*3,//为0为不限制大小
			//监听xhr进度，并返回给进度条
			progress : function(value) { //上传进度回调 value进度值   
				element.progress('upload_progress', value + '%') //设置页面进度条
			},
			before : function(obj) {
				//开始上传时候让进度条去除隐藏状态
				$("#upload_progress").removeClass("layui-hide");
				//或者设置loading
				//layer.load(1); //去除方法为 layer.close('loading'); 或者 layer.closeAll('loading');
			},
			//auto: false, //选择文件后不自动上传 默认值为true
			//bindAction: '#testListAction', //指向一个按钮触发上传
			//选择文件的回调 在文件被选择后触发，该回调会在 before 回调之前。一般用于非自动上传（即 auto: false ）的场景 预览图片等其他操作
			/*choose: function(obj){
   				//将每次选择的文件追加到文件队列
    			var files = obj.pushFile();
				//预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
    			obj.preview(function(index, file, result){
			  	console.log(index); //得到文件索引
			 	console.log(file); //得到文件对象
			  	console.log(result); //得到文件base64编码，比如图片
      			//obj.resetFile(index, file, '123.jpg'); //重命名文件名
      			//这里还可以做一些 append 文件列表 DOM 的操作
      			//obj.upload(index, file); //对上传失败的单个文件重新上传，一般在某个事件中使用
      			//delete files[index]; //删除列表中对应的文件，一般在某个事件中使用
				 obj.preview(function(index, file, result){
					 //对上传失败的单个文件重新上传，一般在某个事件中使用
					 //obj.upload(index, file);
				});
    		});
			*/
			/*allDone: function(obj){ //当文件全部被提交后，才触发  开启多文件上传时该回调才可触发
				console.log(obj.total); //得到总文件数
				console.log(obj.successful); //请求成功的文件数
				console.log(obj.aborted); //请求失败的文件数
  			},*/
			done : function(res, index, upload) {//在多文件上传中 每次成功将被调用一次
				/*  你不一定非得按照上述格式返回，只要是合法的 JSON 字符即可。其响应信息会转化成JS对象传递给 done 回调。
				{	如果上传后，出现文件下载框（一般为ie下），那么你需要在服务端对response的header设置 Content-Type: text/html
  					"code": 0,
  					"msg": "",
 					"data": {
    					"src": "http://google.com"
 				 	}
				}       
				*/
				//上传完毕回调
				//假设code=0代表上传成功
				layer.close(layer.index);
				if (res.code == 0) {//此处只用于单文件图片的上传，若为多图片多文件请做其他处理
					layer.msg("上传成功！");
					console.log(res.src);//回调内容src
					$("#upload_preview").html("<img alt='预览图' src='"+ res.src +"' width='230px' height='146px' />");
				}
				//获取当前触发上传的元素
				//var item = this.item;
			},
			error : function(index, upload) {
				//请求异常回调
				layer.close(layer.index);
				layer.confirm("上传失败，您是否要重新上传？", {
					btn : [ '确定', '取消' ],
					icon : 3,
					title : "提示"
				}, function() {
					//关闭询问框
					layer.closeAll();
					//重新调用上传方法
					uploadFile.upload();
				})
			}
		});
		
		
	}) //layui

})