<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登陆</title>
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
	<form action="{{route('adduser')}}" method="post" enctype="multipart/form-data">
		@csrf
		<p><input type="text" name="username" id="" placeholder="请输入姓名"></p>

		<p><input type="number" name="age" id="" placeholder="请输入年龄"></p>

		<p><input type="file" name="head"></p>

		<p><input type="button" value="提交"></p>
	</form>


	<script>
		$("input[name=username]").blur(function(){
			var username=$(this).val();
			$(this).next().remove();
			if (username=='') {
				$(this).after('<b style="color:red">请输入名称</b>');
				return false;
			}

			var reg=/^\w{3,30}$/;
			if (!reg.test(username)) {
				$(this).after('<b style="color:red">请已数字字母下划线组成且不少于3字符不大于30字符</b>');
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
				url:"/user/checkname",
				data:{username:username},
			}).done(function(msg){
				if (msg.code==0) {
					// alert(msg.msg);
					$("input[name=username]").after('<b style="color:red">'+msg.msg+'</b>');
				}
			});
		});

		$("input[name=age]").blur(function(){
			var age=$(this).val();
			$(this).next().remove();
			if (age=='') {
				$(this).after('<b style="color:red">请输入年龄</b>');
				return false;
			}

			var reg=/^\d{1,3}$/;
			if (!reg.test(age)) {
				$(this).after('<b style="color:red">请年龄合法</b>');
				return false;
			}
		});

		$("input[type=button]").click(function(){
			var obj_name=$("input[name=username]");
			var username=obj_name.val();
			obj_name.next().remove();
			if (username=='') {
				obj_name.after('<b style="color:red">请输入名称</b>');
				return false;
			}

			var reg=/^\w{3,30}$/;
			if (!reg.test(username)) {
				obj_name.after('<b style="color:red">请已数字字母下划线组成且不少于3字符不大于30字符</b>');
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
				url:"/user/checkname",
				data:{username:username},
			}).done(function(msg){
				if (msg.code==0) {
					// alert(msg.msg);
					$("input[name=username]").after('<b style="color:red">'+msg.msg+'</b>');
					return false;
				}
			});
		


			var obj_age=$("input[name=age]");
			var age=obj_age.val();
			obj_age.next().remove();
			if (age=='') {
				obj_age.after('<b style="color:red">请输入年龄</b>');
				return false;
			}

			var reg=/^\d{1,3}$/;
			if (!reg.test(age)) {
				obj_age.after('<b style="color:red">请年龄合法</b>');
				return false;
			}

			$('form').submit();
			
		});
		
	</script>
</body>
</html>
