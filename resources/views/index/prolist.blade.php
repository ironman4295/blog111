@extends('layouts.shop')
@section('title', '商品')

@section('content')
    <div class="maincont">
        <header>
           <a href="javascript:history.back(-1)" class="back-off fl">
               <span class="glyphicon glyphicon-menu-left"></span>
           </a>
            <div class="head-mid">
                <form class="prosearch" style="width:550px">
                    <input type="text" id="sousuo"/>
                </form>
                <input type="button" width="50" id="souclick" value="🔍" style="position: relative;left: 555px;top: -35px;height:30px;">
            </div>
        </header>
        
        <p align="center" id="show" style="color:#999999">- v - 展开 - v -</p>
        <div id="catelist" style="display:none">
            <ul class="pro-select">
                <?php $num=1 ?>
                @foreach($cateIfo as $k=>$v)
                    <li class="now" cate_id="{{$v->cate_id}}">
                        <a href="javascript:;">{{$v->cate_name}}</a>
                    </li>
                    @if($num%3==0)
                        </ul><ul class="pro-select">
                    @endif
                <?php $num++ ?>
                @endforeach
            </ul><!--pronav/-->
            <p align="center" id="hide" style="color:#999999">- ^ - 收起 - ^ -</p>
        </div>
        <ul class="pro-select">

            <li class="now1 pro-selCur" type="1"><a href="javascript:;">销量</a></li>
            <li class="now1" type="2"><a href="javascript:;">新品</a></li>
            <li class="now1" type="3"><a href="javascript:;">价格</a></li>
        </ul><!--pro-select/-->
        <div class="prolist">
            @foreach($goodInfo as $k=>$v)
            <dl>
                <dt><a href="/index/proinfo/{{$v->good_id}}"><img src="{{config('app.uploads')}}/{{$v->good_img}}" width="100" height="100"/></a></dt>
                <dd>
                    <h3><a href="/index/proinfo">{{$v->good_name}}</a></h3>
                    <div class="prolist-price"><strong>¥{{$v->good_onprice}}</strong> <span>¥{{$v->good_outprice}}</span></div>
                    <div class="prolist-yishou"><span>5.0折</span> <em>已售：{{$v->out_num}}</em></div>
                </dd>
                <div class="clearfix"></div>
            </dl>
            @endforeach
        </div><!--prolist/-->
        <p align="center" id="more" floor="1">👉 <a href="javascript:;">加载更多...</a></p>


        @section('footer')
            @include('public/footer')
        @endsection
        <script>
            $(function () {
                layui.use('layer',function(){
                    //切换
                    $(document).on('click','#show',function () {
                        $('#catelist').show();
                        $(this).remove();
                    });
                    $('#hide').click(function () {
                        $('#catelist').hide();
                        $('#catelist').before('<p align="center" id="show" style="color:#999999">- v - 展开 - v -</p>');
                    });
                    $('.now').click(function () {
                        $('.now').removeClass('pro-selCur');
                        $(this).addClass('pro-selCur');
                        goodload();
                        $('#more').attr('floor',1);
                    });
                    $('.now1').click(function () {
                        $('.now1').removeClass('pro-selCur');
                        $(this).addClass('pro-selCur');
                        goodload();
                        $('#more').attr('floor',1);
                    });
                    $('#souclick').click(function () {
                        goodload();
                        $('#more').attr('floor',1);
                    });
                    $('#more').click(function () {
                        var floor=$(this).attr('floor');
                        $(this).attr('floor',parseInt(floor)+1);
                        goodload(parseInt(floor)+1);
                    });

                    //公用ajax
                    function goodload(floor=1){
                        var sousuo=$('#sousuo').val();
                        var cate_id=$('.now.pro-selCur').attr('cate_id');
                        var type=$('.now1.pro-selCur').attr('type');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.post(
                            "",
                            {sousuo:sousuo,cate_id:cate_id,type:type,floor:floor},
                            function(res){
                                if (res){
                                    if (floor!=1){
                                        $('.prolist').append(res);
                                    }else{
                                        $('.prolist').html(res);
                                    }
                                }else{
                                    layer.msg('到底了');
                                }
                            }
                        );
                    }
                });
            })
        </script>
    </div><!--maincont-->
@endsection