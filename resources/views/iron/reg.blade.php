<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>注册噢</title>
	<script src="{{asset('shop/layui/layui.js')}}"></script>
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
</head>
<body>
	@csrf
	<div>
		邮箱：<input type="email" placeholder="输入邮箱号" id="id">
	</div>

	<div>
		验证码：<input type="text" placeholder="输入验证码" id="code"/>
        <button id="sendcode">获取验证码</button>
	</div>

	<div>
		密码：<input type="text" placeholder="输入密码" id="psd"/>
	</div>

	<div>
		确认密码：<input type="text" placeholder="再次输入密码" id="pwd"/>
	</div>

	<div>
        <input type="submit" value="立即注册" id="submit"/>
    </div>
</body>
</html>

<script>
	$(function(){
		layui.use('layer', function () {
            var layer = layui.layer;

            $("#sendcode").click(function(){

            	var _this=$(this);
            	var email=$('#id').val();
            	var type=checkname();
            	if (!type) {

            		return false;
            	};

            	//发送消息（控制器判断类型发送）
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "/iron/send",
                    {email:email},
                    function (msg) {
                        layer.msg('发送成功');
                    }
                );

            });

            $("#submit").click(function(){
            	
            	if (checkname()&&checkcode()&&checkpsd()&&checkpwd()) {
            		var id=$('#id').val();
                    var code=$('#code').val();
                    var pwd=$('#pwd').val();
                    var psd=$('#psd').val();
                    var email=$('#id').val();
            	}


             	$.ajaxSetup({
					headers:{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					}	
				});

				$.ajax({
					method:"POST",
					url:"/iron/doreg",
					data:{email:email,id:id,code:code,pwd:pwd,psd:psd},
					dataType:'json',
				}).done(function(msg){
					if (msg.code==1){
                        layer.msg('注册成功',{icon:1});
                        location.href="/iron/first";
                    }else{
                        layer.msg('注册失败',{icon:2});
                    }
				});
            });


            //验证非空||格式 
            function checkname()
            {
             	var _id=$('#id');
             	var email=_id.val();
             	var flag=true;
             	//验证非空
             	if (email=='') {
             		layer.msg('邮箱不能为空',{icon:2});
             		return false;
             	}

             	reg=/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
             	if (!reg.test(email)) {
             		layer.msg('邮箱非法',{icon:2});
             		return false;
             	}

             	//验证唯一
             	$.ajaxSetup({
					headers:{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					}	
				});
				$.ajax({
					async:false,
					method:"POST",
					url:"/iron/uniques",
					data:{email:email},
				}).done(function(msg){
					if (msg==1) {
						layer.msg('账号已存在',{icon:2});
                        flag= false;
					}
				});
				return flag;
				
            }

            //验证密码
            function checkpsd()
            {
            	var psd=$('#psd').val();
            	if (psd=='') {
            		layer.msg('密码不能为空',{icon:2});
            		return false;
            	}

            	var reg=/^[\da-zA-Z]{6,18}$/;
            	if (!reg.test(psd)) {
            		layer.msg('密码格式非法',{icon:2});
            		return false;
            	}
            	return true;
            }
            //确认密码
            function checkpwd()
            {
            	var pwd=$('#pwd').val();
            	var psd=$('#psd').val();
            	if (pwd=='') {
            		layer.msg('确认密码不为空',{icon:2});
            		return false;
            	}
            	if (pwd!=psd) {
            		layer.msg('两次密码不正确',{icon:2});
            		return false;
            	}
            	return true;
            }
            //验证码
            function checkcode()
            {
                var code=$('#code').val();
                if (code=='') {
                	layer.msg('验证码不为空',{icon:2});
                	return false;
                }
                return true;
       		}
        }); 
	});
</script>