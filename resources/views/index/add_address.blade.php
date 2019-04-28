@extends('layouts.shop')
@section('title', '收货地址')

@section('content')
    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>收货地址</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public.headimg')
        </div><!--head-top/-->
        <form action="login.html" method="get" class="reg-login">
            <div class="lrBox">
                <div class="lrList"><input type="text" id="name" placeholder="收货人"/></div>

                <div class="lrList">
                    <select class="area" id="province" name="province" type="0">
                        <option selected='selected'>省份/直辖市</option>
                        @foreach($area as $k =>$v)
                            <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lrList">
                    <select id="city" class="area" name="city" type="1">
                        <option selected value="null">请选择...</option>
                    </select>
                </div>
                <div class="lrList">
                    <select type="2" id="area" class="area" name="area">
                        <option selected value="null">请选择...</option>
                    </select>
                </div>
                <div class="lrList"><input type="text" id="detailed" placeholder="详细地址"/></div>
                <div class="lrList"><input type="text" id="tel" placeholder="手机"/></div>

            </div><!--lrBox/-->
            <div class="lrSub">
                <input type="button" id="add" value="保存"/>
            </div>
        </form><!--reg-login/-->

        @section('footer')
            @include('public/footer')
        @endsection
    </div><!--maincont-->
    <script>
        $(function () {
            //三级联动
            $(document).on('change', '.area', function () {
                var type=parseInt($(this).attr('type'));
                var option = '<option selected="selected">请选择...</option>';
                _this = $(this);
                $('.area').each(function () {
                    if ( parseInt($(this).attr('type'))>type){
                        $(this).html(option);
                    }
                });
                $.post(
                    "/index/add_address",
                    {id: _this.val()},
                    function (msg) {
                        for (var i in msg) {
                            option += '<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>';
                        }
                        $('.area').eq(type+1).html(option);
                    },
                    'json'
                );
            });

            //点击添加
            $('#add').click(function () {
                var _obj = {};
                _obj.province = $('#province').val();
                _obj.city = $('#city').val();
                _obj.area = $('#area').val();
                _obj.name = $('#name').val();
                _obj.tel = $('#tel').val();
                _obj.detailed = $('#detailed').val();
                $.post(
                    "/index/doadd_address",
                    _obj,
                    function (msg) {
                        if (msg.code == 1) {
                            alert(msg.msg);
                            location.href = "/index/address";
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