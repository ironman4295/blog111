@extends('layouts.shop')
@section('title', '收货地址')

@section('content')
    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>添加收货地址</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public.headimg')
        </div><!--head-top/-->
        <table class="shoucangtab">
            <tr>
                <td width=""><a href="/index/add_address" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
            </tr>
        </table>

        <div class="dingdanlist">
            <table>
                @foreach($address as $k=>$v)
                    <tr>
                        <td width="50%">
                            <h3>{{$v['name']}} {{$v['tel']}}</h3>
                            <time>{{$v['province']}}{{$v['city']}}{{$v['area']}}{{$v['detailed']}}</time>
                        </td>
                        <td align="right">
                            <a href="/index/update_address/{{$v['id']}}" class="hui">
                                <span class="glyphicon glyphicon-check"></span>
                                修改
                            </a>
                            <a href="javascript:;" class="orange del" address_id="{{$v['id']}}"
                               style="margin-left: 20px">删除</a>
                            @if($v['default']==1)
                                <a class="orange" class="is_default" address_id="{{$v['id']}}" style="margin-left: 20px;">已默认</a>
                            @else
                                <a href="javascript:;" address_id="{{$v['id']}}" class="is_default" class="orange"
                                   style="margin-left: 20px">设为默认</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div><!--dingdanlist/-->

        @section('footer')
            @include('public/footer')
        @endsection
    </div><!--maincont-->
    <script>
        $(function () {
            $('.del').click(function () {
                _this = $(this);
                var id = _this.attr('address_id');
                $.post(
                    '/index/del_address',
                    {id: id},
                    function (msg) {
                        if (msg.code == 1) {
                            alert(msg.msg);
                            _this.parents('tr').hide();
                        } else {
                            alert(msg.msg);
                        }
                    },
                    'json'
                );
            });
            $('.is_default').click(function () {
                _this = $(this);
                var id = _this.attr('address_id');
                $.post(
                    '/index/is_default',
                    {id: id},
                    function (msg) {
                        if (msg.code == 1) {
                            alert(msg.msg);
                            location.href="";
                        } else {
                            alert(msg.msg);
                        }
                    },
                    'json'
                );
            });
        });
    </script>
@endsection