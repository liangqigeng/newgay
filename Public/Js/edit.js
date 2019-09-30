$(function () {

	//修改资料选项卡
	$('#sel-edit li').click( function () {
		var index = $(this).index();
		$(this).addClass('edit-cur').siblings().removeClass('edit-cur');
		$('.form').hide().eq(index).show();
	} );

	//城市联动
	var province = '';
	$.each(city, function (i, k) {
		province += '<option value="' + k.name + '" index="' + i + '">' + k.name + '</option>';
	});
	$('select[name=province]').append(province).change(function () {
		var option = '';
		if ($(this).val() == '') {
			option += '<option value="">请选择</option>';
		} else {
			var index = $(':selected', this).attr('index');
			var data = city[index].child;
			for (var i = 0; i < data.length; i++) {
				option += '<option value="' + data[i] + '">' + data[i] + '</option>';
			}
		}
		
		$('select[name=city]').html(option);
	});

	//所在地默认选项
	address = address.split('');
	$('select [name=provice]').val(address[0]);
	$.each(city, function (i,k) {
		if (k.name == address[0]) {
			var str ='';
			for (var j in k.child) {
				str += '<option value="' + k.child[j] + '"';
				if (k,child[j] == address[1]) {
					str += 'selected="selected"';
				}
				str += '>' + k.child[j] + '</option>';
			}
		}
	})
	//星座默认选项
	$('select[name=night]').val(constellation);

	//头像上传 Uploadifive
	$('#face').uploadifive({
		uploadScript : uploadUrl, //PHP处理脚本地址
		buttonText : '请选择文件',
		width : 120, //上传按钮宽度
		height :30, //上传按钮高度
		//buttonImage : PUBLIC + '/uploadifive/browse-btn.png', //上传按钮背景图地址 
		fileTypeDesc : 'Image File', //选择文件提示文字
		fileType : 'image/*', //允许选择的图片类型
		formData : {'session_id ' : sid},
		//上传成功后的回调函数
		onUploadComplete : function (file, data) {
			eval('var data = ' + data);
			if (data.status) {
				$('#face_img').attr('src', ROOT+'/Uploads/Face/' + data.face180);
				$('input[name=face180]').val(data.path.max);
				$('input[name=face80]').val(data.path.medium);
				$('input[name=face50]').val(data.path.mini);
			} else {
				alert(data.msg);
			}
		}
	});	


	/*
	 *添加验证方法
	 *以字母开头，5-17 字母、数字、下划线"_"
	*/
	//重写validator在验证成功后再输入错误图标也正确显示
	

	jQuery.validator.addMethod("user",function(value,element){
		var tel = /^[a-zA-Z][\w]{4,16}$/;
		return this.optional(element) || (tel.test(value));
	}," ");

	$('form[name=editPwd]').validate({
		errorElement : 'span',
		success : function (label) {
			label.addClass('success');
		},
		highlight : function(element) {
			$(element).next().removeClass('success')
		},
		rules : {
			old : {
				required : true,
				user : true
			},
			new : {
				required : true,
				user : true
			},
			newed : {
				required : true,
				equalTo	: '#new'
			}
		},
		messages : {
			old : {
				required : '请填写旧密码',
			},
			new : {
				required : '请设置新密码',
			},
			newed : {
				required : '请确认密码',
				equalTo : '两次密码不一致'
			}
		}
	});
});



