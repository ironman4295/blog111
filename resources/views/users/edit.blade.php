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
	<form action="{{url('/user/update/'.$data->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		<p><input type="text" name="username" value="{{$data->username}}" placeholder="请输入姓名"></p>

		<p><input type="number" name="age" value="{{$data->age}}" placeholder="请输入年龄"></p>

		<p><img src="http://uploads.17shou.com/{{$data->head}}" height="80" width="100"><input type="file" name="edit_head"></p>
	
		<input type="hidden" name="head" value="{{$data->head}}" />

		<p><input type="button" value="修改"></p>
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

<!-- 简化方式 -->
<!-- <script>
    $(function(){
        
        //用户名的失去焦点事件
        $('input[name=user_name]').blur(function(){
            checkUser();
        });


        //身份证的失去焦点事件
        $('input[name=user_card]').blur(function(){
            checkCard();
        });


        var flag = false;
        //用户名的验证方法
        function checkUser()
        {
            //获取当前文本框内的值
            var user_name = $('input[name=user_name]').val();
            if (user_name == '') {
                $('input[name=user_name]').next().html("<font style='color: red'>用户名不能为空</font>");
                flag = false;
            }else{
                //判断唯一性
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                //发送ajax
                $.post(
                    '/user/checkName',
                    {user_name:user_name},
                    function(res){
                        if (res.code == 1) {
                            $('input[name=user_name]').next().html("<font style='color: red'>"+res.msg+"</font>");
                            flag = false;
                        }else{
                            $('input[name=user_name]').next().html("<font style='color: green'>"+res.msg+"</font>");
                            flag = true;
                        }
                    }
                );
            }
        };


        //身份证的验证方法
        function checkCard()
        {
            //获取身份证的文本框值
            var user_card = $('input[name=user_card]').val();
            if (user_card == '') {
                $('input[name=user_card]').next().html("<font style='color: red'>身份证不能为空</font>");
                return false;
            }else{
                //判断身份证必须为数字
                var reg = /^[0-9]{10,18}$/;
                if (!reg.test(user_card)) {
                    $('input[name=user_card]').next().html("<font style='color: red'>身份证号码必须是10-18个数字</font>");
                    return false;
                }else{
                    $('input[name=user_card]').next().html("<font style='color: green'>✔</font>");
                    return true;
                }
            }
        };


        //按钮的点击事件
        $('button').click(function(){
            checkUser();
            if (checkCard() && flag) {
                return true;
            }else{
                return false;
            }
        });
    });
</script> -->