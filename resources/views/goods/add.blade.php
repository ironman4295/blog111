<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加页面哟</title>
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
	<meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>
	@if ($errors->any())
	 <div class="alert alert-danger">
		 <ul>
		 @foreach ($errors->all() as $error)
		 	<li>{{ $error }}</li>
		 @endforeach
		 </ul>
	 </div>
	@endif
	<form action="{{route('addgoods')}}" method="post" enctype="multipart/form-data">
		@csrf
		<h3 align="center"><a href="{{'first'}}">返回</a></h3>
		<table border="1" align="center">
			<tr>
				<th>网站名称：</th>
				<td>
					<input type="text" name="uname" id="">
				</td>
			</tr>
			<tr>
				<th>网站网址：</th>
				<td>
					<input type="text" name="url" id="">
				</td>
			</tr>
			<tr>
				<th>连接类型：</th>
				<td>
					<input type="radio" name="class" id="" value="1">LOGO链接
					<input type="radio" name="class" id="" value="2">文字链接
				</td>
			</tr>
			<tr>
				<th>LOGO：</th>
				<td>
					<input type="file" name="logo" id="">
				</td>
			</tr>
			<tr>
				<th>网站联系人：</th>
				<td>
					<input type="text" name="man" id="">
				</td>
			</tr>
			<tr>
				<th>网站介绍：</th>
				<td>
					<textarea name="content" id="" cols="30" rows="10"></textarea>
				</td>
			</tr>
			<tr>
				<th>是否显示：</th>
				<td>
					<input type="radio" name="show" id="" value="1">是
					<input type="radio" name="show" id="" value="2">否
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="button" value="添加"></td>
			</tr>
		</table>
	</form>
	<script>
	$(function(){
		//网站名称失去焦点事件
		$('input[name=uname]').blur(function(){
			var uname=$(this).val();
			$(this).next().remove();
			if (uname=='') {
				$(this).after('<b style="color:red">请输入网站名称</b>');
				return false;
			}

			var reg=/^[a-zA-Z_\u4e00-\u9fa5][a-zA-Z0-9_\u4e00-\u9fa5]*$/;
			if (!reg.test(uname)) {
				$(this).after('<b style="color:red">网站名称非法</b>');
				return false;
			}

			//判断是否存在
			$.ajaxSetup({
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				}	
			});
			$.ajax({
				method:"POST",
				url:"/goods/checkname",
				data:{uname:uname},
			}).done(function(msg){
				if (msg.code==0) {
					// alert(msg.msg);
					$("input[name=uname]").after('<b style="color:red">'+msg.msg+'</b>');
					return false;
				}
			});
			
		});

		//网址失去焦点事件
		$('input[name=url]').blur(function(){
			var url=$(this).val();
			$(this).next().remove();
			if (url=='') {
				$(this).after('<b style="color:red">请输入网站网址</b>');
				return false;
			}

			var reg=/(http|https):\/\/([\w.]+\/?)\S*/;
			if (!reg.test(url)) {
				$(this).after('<b style="color:red">网站名称非法</b>');
				return false;
			}
		});

		$("input[type=button]").click(function(){
			var obj_name=$('input[name=uname]');
			var uname=obj_name.val();
			obj_name.next().remove();
			if (uname=='') {
				obj_name.after('<b style="color:red">请输入网站名称</b>');
				return false;
			}

			var reg=/^[a-zA-Z_\u4e00-\u9fa5][a-zA-Z0-9_\u4e00-\u9fa5]*$/;
			if (!reg.test(uname)) {
				obj_name.after('<b style="color:red">网站名称非法</b>');
				return false;
			}

			//判断是否存在
			$.ajaxSetup({
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				}	
			});
			$.ajax({
				method:"POST",
				url:"/goods/checkname",
				data:{uname:uname},
			}).done(function(msg){
				if (msg.code==0) {
					obj_name.next().remove();
					// alert(msg.msg);
					obj_name.after('<b style="color:red">'+msg.msg+'</b>');
					return false;
				}
			});


			var obj_url=$("input[name=url]");
			var url=obj_url.val();
			obj_url.next().remove();
			if (url=='') {
				obj_url.after('<b style="color:red">请输入网站网址</b>');
				return false;
			}

			var reg=/(http|https):\/\/([\w.]+\/?)\S*/;
			if (!reg.test(url)) {
				obj_url.after('<b style="color:red">网站名称非法</b>');
				return false;
			}

			$("form").submit();

		});

		
	});

</script>

</body>
</html>
