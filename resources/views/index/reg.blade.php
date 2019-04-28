@extends('layouts.shop')
@section('title', '注册')

@section('content')
    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>会员注册</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public.headimg')
        </div><!--head-top/-->

        <h5 style="margin-top: 30px;margin-bottom: 10px">已经有账号了？点此<a class="orange" href="/index/login">登陆</a></h5>
        <div class="lrBox">
            <div class="lrList">
                <input type="text" placeholder="输入手机号码或者邮箱号" id="id"/>
            </div>
            <div class="lrList2">
                <input type="text" placeholder="输入验证码" id="code"/>
                <button id="sendcode">获取验证码</button>
            </div>
            <div class="lrList">
                <input type="text" placeholder="设置密码（6-18位数字或字母）" id="psd"/>
            </div>
            <div class="lrList">
                <input type="text" placeholder="再次输入密码" id="pwd"/>
            </div>
        </div><!--lrBox/-->
        <div class="lrSub">
            <input type="submit" value="立即注册" id="submit"/>
        </div>

@section('footer')
    @include('public/footer')
@endsection

    </div><!--maincont-->
    <script>
        $(function () {
            layui.use('layer', function () {
                var layer = layui.layer;
                $('#sendcode').click(function(){
                    var _this=$(this);
                    var val=$('#id').val();
                    //验证非空||格式 //成功返回类型email或phone，失败false
                    var type=checkname();
                    if(!type){
                        return false;
                    };
                    //发送消息（控制器判断类型发送）
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post(
                        "/send/reg",
                        {type: type,val:val},
                        function (msg) {
                            layer.msg('发送成功');
                        }
                    );
                    //倒计时
          _this.text(60+'s');
          setI=setInterval(timeLess,1000);
          function timeLess(){
            var time=parseInt(_this.text());
            if (time<=0) {
              _this.text('获取验证码');
              clearInterval(setI);
              //允许点击
              _this.css('pointerEvents','auto');
            }else{
              time=time-1;
              _this.text(time+'s');
              _this.css('pointerEvents','none');

            }
          }

                });
                $('#submit').click(function(){
                    var type=checkname();
                    if (type&&checkcode()&&checkpsd()&&checkpwd()){
                        var id=$('#id').val();
                        var code=$('#code').val();
                        var pwd=$('#pwd').val();
                        var psd=$('#psd').val();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            method: "POST",
                            url: "/index/doreg",
                            data: {type: type,id:id,code:code,pwd:pwd,psd:psd},
                            dataType:'json'
                        }).done(function (msg) {
                            if (msg.code==1){
                                layer.msg('注册成功',{icon:1});
                                location.href="{{route('login')}}";
                            }else{
                                layer.msg('注册失败',{icon:2});
                            }
                        });
                    }
                });


                //验证非空||格式 //成功返回类型email或phone，失败false
                function checkname(){
                    var _id=$('#id');
                    var val=_id.val();
                    var type='';
                    //验证为空
                    if(val==''){
                        layer.msg('账号不能为空',{icon:2});
                        return false;
                    }
                    //验证邮箱手机正则
                    reg_phone=/^1[34578]\d{9}$/;
                    reg_email=/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
                    if (reg_phone.test(val)){
                        type='phone';
                    }else if(reg_email.test(val)){
                        type='email';
                    }else{
                        layer.msg('请输入手机号码或邮箱',{icon:2});
                        return false;
                    }

                    //验证唯一
                    var flag=true;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        async:false,
                        method: "POST",
                        url: "/index/unique",
                        data: {type: type,val:val}
                    }).done(function (msg) {
                        if (msg==1){
                            layer.msg('账号已存在',{icon:2});
                            flag=false;
                        }
                    });
                    if (flag){
                        return type;
                    }else{
                        return false;
                    }


                }
                //验证密码
                function checkpsd(){
                    var val=$('#psd').val();
                    if (val==''){
                        layer.msg('密码不能为空',{icon:2});
                        return false;
                    }
                    var reg=/^[\da-zA-Z]{6,18}$/;
                    if (!reg.test(val)){
                        layer.msg('密码为6-18位数字或字母组成',{icon:2});
                        return false;
                    }
                    return true;
                }
                //确认密码
                function checkpwd() {
                    var pwd=$('#pwd').val();
                    var psd=$('#psd').val();
                    if (pwd!=psd){
                        layer.msg('两次密码不一致',{icon:2});
                        return false;
                    }
                    return true;
                }
                //验证码非空
                function checkcode() {
                    var val=$('#code').val();
                    if (val==''){
                        layer.msg('验证码不能为空',{icon:2});
                        return false;
                    }
                    return true;
                }
            });//layui
        });
    </script>
@endsection

