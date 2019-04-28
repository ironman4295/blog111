<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登陆噢</title>
	<script src="{{asset('shop/layui/layui.js')}}"></script>
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
</head>
<body>
	<form action="/iron/login" method="post" class="reg-login">
            @csrf
		<div>
			邮箱：<input type="text" name="email" id="email" placeholder="输入手机号码或者邮箱号"/>
		</div>
		
		<div>
			密码：<input type="password" name="password" id="password" placeholder="输入密码"/>
		</div>

		<div>
            <input type="submit" id="btn" value="立即登录"  />
        </div>
	</form>
</body>
</html>

<script>
	$(function(){
		layui.use(['layer'],function(){
	        var layer=layui.layer;
	        $('#btn').click(function(){
	        	var email=$('#email').val();
	        	var password=$('#password').val();
	        	if (email=='') {
	        		layer.msg('邮箱必填',{icon:2});
					return false;
	        	}
	        	if (password=='') {
	        		layer.msg('密码必填',{icon:2});
					return false;
	        	}
	        	//把账号 密码通过ajax传给控制器
				$.post(
				    "/iron/login",
					{email:email,password:password},
					function(msg){
				        layer.msg(msg.font,{icon:msg.code});
				        if(msg.code==1){
				        	
				            location.href="/iron/first"
						}
					},
					'json'
				);
	        });


	    });
	});

</script>