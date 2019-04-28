@extends('layouts.shop')
@section('title','详情')
@section('content')
    <input type="hidden" value="{{$goodInfo->good_id}}" id="good_id">
    <div class="maincont">
        <header>
            <input type="hidden" id="good_id" value="{{$goodInfo->good_id}}">
            <input type="hidden" id="good_num" value="{{$goodInfo->good_num}}">
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>产品详情</h1>
            </div>
        </header>
        <div id="sliderA" class="slider">
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}"/>
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}"/>
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}"/>
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}"/>
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}"/>
        </div><!--sliderA/-->
        <table class="jia-len">
            <tr>
                <th>
                    <strong class="orange">
                        售价： ￥<a href="javascript:;" class="orange" id="good_onprice">{{$goodInfo->good_onprice}}</a>

                    </strong>
                    <div class="prolist-price">
                        <b style="color: #888888">市场价：</b><span>¥{{$goodInfo->good_outprice}}</span>
                    </div>
                </th>

                <td>
                    <input type="text" class="spinnerExample"/>
                </td>

            </tr>
            <tr>
                <td>
                    <strong>{{$goodInfo->good_name}}</strong>
                    <p class="hui">{{$goodInfo->good_keyword}}</p>
                </td>
                <td align="right">
                    <a href="javascript:;" class="shoucang">
                        @if($enshrine)
                            <span class="glyphicon glyphicon-star-empty" id="addenshrine" style="color:red;"></span>
                        @else
                            <span class="glyphicon glyphicon-star-empty" id="addenshrine"></span>
                        @endif
                    </a>
                </td>
            </tr>
        </table>

        <div class="height2"></div>
        <div class="zhaieq">
            <a href="javascript:;" class="zhaiCur">商品简介</a>
            <a href="javascript:;">商品参数</a>
            <a href="javascript:;" style="background:none;">订购列表</a>
            <div class="clearfix"></div>
        </div><!--zhaieq/-->
        <div class="proinfoList">
            {{$goodInfo->good_description}}
            <img src="{{config('app.uploads')}}/20190411/20190411005320162.gif" width="636"
                 height="822"/>
        </div><!--proinfoList/-->
        <div class="proinfoList">
            {{$goodInfo->good_description}}
            <img src="{{config('app.uploads')}}/{{$goodInfo->good_img}}" width="636" height="822"/>
        </div><!--proinfoList/-->
        <div class="proinfoList">
            暂无信息......
        </div><!--proinfoList/-->

        @include('public.footertopay')
    </div><!--maincont-->
    <script>
        $(function () {
            layui.use('layer', function () {
                var layer = layui.layer;

                //点击结算
                $('#close').click(function () {
                    var good_id=$('#good_id').val();
                    var buy_num=$('.spinnerExample.value').val()
                    location.href="/index/order/"+good_id+'/'+buy_num;
                });
                //初始化购买数量为1
                $('.spinnerExample.value.passive').removeClass('passive').val(1);
                //获得库存
                var good_num = parseInt($('#good_num').val());
                //点击加+
                $('.increase').click(function () {
                    var amount = parseInt($('.spinnerExample.value').val());//购买总计
                    if (amount >= good_num) {
                        $(this).prop('disabled', true);
                    } else {
                        $('.decrease').prop('disabled', false);
                    }
                    totalmoney();
                });
                //点击减——
                $('.decrease').click(function () {
                    var amount = parseInt($('.spinnerExample.value').val());
                    if (amount <= 1) {
                        $(this).prop('disabled', true);
                        $('.spinnerExample.value').val(1);
                    } else {
                        $('.increase').prop('disabled', false);
                    }
                    totalmoney();
                });
                //手动输入
                $('.spinnerExample.value').blur(function () {
                    var amount = $(this).val();
                    // alert(amount);
                    var reg = /^\d{1,}$/;
                    if (amount == '' || parseInt(amount) < 1 || !reg.test(amount)) {
                        $(this).val(1);
                    } else if (parseInt(amount) > good_num) {
                        $(this).val(good_num);
                    } else {
                        $(this).val(amount);
                    }
                    totalmoney();
                });

                //添加收藏
                $('#addenshrine').click(function () {
                    var _this=$(this);
                    var good_id = $('#good_id').val();
                    $.ajax({
                        method: "POST",
                        url: "/index/addenshrine",
                        data: {good_id:good_id},
                        dataType :'json'
                    }).done(function (msg) {
                        if (msg.code==1){
                            layer.msg(msg.msg);
                            _this.css('color','red');
                        }else if(msg.code==3){
                            layer.msg(msg.msg);
                            _this.css('color','#999999');
                        }else{
                            layer.msg(msg.msg);
                        }

                    });

                });
                //添加到购物车
                $('#addcart').click(function () {
                    //验证是否登陆
                    $.post(
                        "/index/islogin",
                        '',
                        function (msg) {
                            if(msg==0){
                                layer.msg('登陆后才可加入');
                            }
                        }
                    );
                    var id = $('#good_id').val();
                    var good_buynum = $('.spinnerExample.value').val();
                    $.post(
                        "/index/addcar",
                        {good_id: id, good_buynum: good_buynum},
                        function (msg) {
                            if (msg.code == 1) {
                                layer.confirm('是否前往结算', {icon: 1, title: '加入购物车成功'}, function (index) {
                                    location.href = "/index/car";
                                    layer.close(index);
                                });
                            } else {
                                layer.msg(msg.msg);
                            }

                        },
                        'json'
                    );
                });

                //计算总价
                function totalmoney() {
                    var good_id = $('#good_id').val();
                    var good_buynum = parseInt($('.spinnerExample.value').val());
                    if (good_buynum == 0 || good_id == '') {
                        layer.msg('至少购买一个商品');
                    } else {
                        $.post(
                            "/index/gettotalmoney",
                            {good_id: good_id, good_buynum: good_buynum},
                            function (msg) {
                                if (msg) {
                                    $('#total').text(msg);
                                }
                            }
                        );
                    }
                }
            });


        });
    </script>
@endsection