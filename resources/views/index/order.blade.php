@extends('layouts.shop')
@section('title','订单生成')
@section('content')


    <input type="hidden" id="type" value="{{$type}}">
    <input type="hidden" id="good_id" value="{{$good_id}}">

    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>订单确认</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public.headimg')
        </div><!--head-top/-->
        <div class="dingdanlist">
            <table>
                @if(empty($address))
                    <tr id="location">
                        <td class="dingimg" width="75%" colspan="2"><a href="/index/add_address">新增收货地址</a></td>
                        <td align="right" id="address">
                            <a href="/index/add_address">
                                <img src="/shop/images/jian-new.png"/>
                            </a>
                        </td>
                    </tr>
                @else
                    @foreach($address as $k=>$v)
                        <tr class="address" address_id="{{$v['id']}}" @if($v['default']==0) style="display:none"@endif>
                            <td class="dingimg" width="75%" colspan="2">
                                收货人：{{$v['name']}}&nbsp;
                                手机号：{{$v['tel']}}&nbsp;
                                地址：{{$v['province']}}{{$v['city']}}{{$v['area']}}...
                            </td>
                            <td align="right" class="address_all">
                                <img src="/shop/images/jian-new.png"/>
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
                </tr>
                <tr>
                    <td class="dingimg" width="75%" colspan="2">支付方式</td>
                    <td align="right"><span class="hui">支付宝</span></td>
                </tr>
                <tr>
                    <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
                </tr>
                
                <tr>
                    <td class="dingimg" width="75%" colspan="3">商品清单</td>
                </tr>
                @foreach($goodInfo as $k=>$v)
                    <tr class="good_id_all" good_id="{{$v->good_id}}">
                        <td class="dingimg" width="15%">
                            <img src="{{config('app.uploads')}}/{{$v->good_img}}"/>
                        </td>
                        <td width="50%">
                            <h3>{{$v->good_name}}</h3>
                            <time>下单时间：{{date('Y-m-d h:i',$v->add_time)}}</time>
                        </td>
                        <td align="right">
                            <span class="qingdan">X
                                <span class="qingdan" id="buy_num">{{$v->buy_num}}</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <strong class="orange">¥{{$v->total}}</strong>
                            <span style="color: #888888;font-size: 12px;margin-left: 10px;">单价：{{$v->good_onprice}}</span>
                        </th>
                    </tr>
                @endforeach
                <tr>
                    <td class="dingimg" width="75%" colspan="2">商品金额</td>
                    <td align="right">
                        <strong class="orange">¥
                            <strong class="orange" id="moneytotal">{{$Moneytotal}}</strong>
                        </strong>

                    </td>
                </tr>
                
            </table>
        </div><!--dingdanlist/-->


    </div><!--content/-->

    <div class="height1"></div>
    <div class="gwcpiao">
        <table>
            <tr>
                <th width="10%"><a href="javascript:history.back(-1)"><span
                                class="glyphicon glyphicon-menu-left"></span></a></th>
                <td width="50%">总计：
                    <strong class="orange">¥
                        <strong class="orange" id="ordermoney">{{$Moneytotal}}</strong>
                    </strong>
                </td>
                <td width="40%"><b href="/index/success" id="submit" class="jiesuan">提交订单</b></td>
            </tr>
        </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <script>
        $(function () {
            //自动计算
            ordermoney();
            $('#location').click(function () {
                location.href = "/index/addressadd";
            });
            $(document).on('click','.address_all',function () {
                $('.address').show();
                $('.address_all').each(function () {
                    $(this).html('<input type="radio" class="radio" name="name">');
                    $(this).removeClass('address_all');
                });
            });
            $(document).on('click','.radio',function () {
                $('.address').hide();
                $(this).parents('tr').show();
                $('.radio').each(function () {
                    $(this).parent().addClass('address_all')
                    $(this).parent().html('<img src="{{asset('index/images/jian-new.png')}}"/>');
                });
            });

            //点击提交
            $('#submit').click(function () {

                var buy_num=$('#buy_num').text();//详情页路径提交用

                var type=$('#type').val();//详情页路径提交用
                var good_id=$('#good_id').val();//商品id
                var ordermoney=$('#ordermoney').text();//总价
                var address_id='';
                $('.address').each(function () {
                    if ($(this).css('display')!='none'){
                        address_id=$(this).attr('address_id');
                    }
                });
                $.post(
                    '/index/orderdoadd',
                    {type:type,good_id:good_id,ordermoney:ordermoney,address_id:address_id,buy_num:buy_num},
                    function (msg) {
                        if (msg.code==1){
                            alert(msg.msg);
                            location.href="/index/success/"+msg.order_id;
                        }else{
                            alert(msg.msg);
                        }
                    },
                    'json'
                );
            });

            //计算提交订单总价ok
            function ordermoney() {
                //获取单个购买数量
                var buy_num = $('#buy_num').text();
                //获取查询类型
                var type = $('#type').val();
                //获得全部id
                var good_id_all = '';
                $('.good_id_all').each(function () {
                    good_id_all += $(this).attr('good_id') + ',';
                });
                good_id_all = good_id_all.substr(0, good_id_all.length - 1);
                
                $.post(
                    '/index/ordermoney',
                    {
                        type: type,
                        buy_num: buy_num,
                        good_id_all: good_id_all,
                        
                    },
                    function (msg) {
                        if (msg.code == 1) {
                            $('#ordermoney').text(msg.msg);
                        } else {
                            alert('请刷新后重试');
                        }
                    },
                    'json'
                );
            }
        });

    </script>
@endsection