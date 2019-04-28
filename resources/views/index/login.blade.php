@extends('layouts.shop')
@section('title','登陆')
@section('content')
    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>会员登陆</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public.headimg')
        </div><!--head-top/-->
        <form action="/index/login" method="post" class="reg-login">
            @csrf
            <h3>还没有账号？点此<a class="orange" href="/index/reg">注册</a></h3>
            
            <div class="lrBox">
                <div class="lrList">
                    <input type="text" name="name" id="name" placeholder="输入手机号码或者邮箱号"/>
                </div>
                <div class="lrList">
                    <input type="password" name="password" id="pwd" placeholder="输入密码"/>
                </div>
            </div><!--lrBox/-->
            <div class="lrSub">
                <input type="submit" id="btn" value="立即登录"/>
            </div>
        </form><!--reg-login/-->
@section('footer')
    @include('public/footer')
@endsection
</div><!--maincont-->
  <!--   <script>
        $(function () {
            layui.use('layer', function () {
                var layer = layui.layer;

                $('#btn').click(function(){
                    var name=$('#name').val();
                    var password=$('#pwd').val();

                    if (name=='') {
                        layer.msg('手机号或邮箱必填',{icon:2});
                        return false;
                    }
                    if (password=='') {
                        layer.msg('密码必填',{icon:2});
                        return false;
                    }

                    //传给控制器
                    $.ajax({
                        method: "post",
                        url: "/index/login",
                        data: {name:name,password:password},
                        dataType:'json'
                    }).done(function (msg) {
                        if (msg==1){
                            layer.msg('登陆成功',{icon:1});
                            location.href="/index/index";
                        }else{
                            layer.msg('登陆失败',{icon:2});
                        }
                    });
                });
            });
        });
    </script> -->
@endsection