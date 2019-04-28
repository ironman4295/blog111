@extends('layouts.shop')
@section('title', '首页')

@section('content')

    <div class="maincont">
        @if(empty($user))
            <div class="head-top">
                @include('public/headimg')
                <ul>
                    <li>
                        <a href="/index/prolist">
                            <strong>{{$goodallnum}}</strong>
                            <p>全部商品</p></a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <strong style="color: red">{{$alloutnum}}</strong>
                            <p>总销售量</p>
                        </a>
                    </li>
                    <li style="background:none;">
                        <a href="javascript:;">
                            <strong>{{$visitcount}}</strong>
                            <p>总访问次数</p>
                        </a>
                    </li>
                </ul>
            </div><!--head-top/-->
            <form action="/index/prolist/" method="get" class="search">
                <input type="text" class="seaText fl" placeholder="搜索商品"/>
                <input type="submit" value="搜索" class="seaSub fr"/>
            </form><!--search/-->
            <ul class="reg-login-click">
                <li><a href="/index/login">登录</a></li>
                <li><a href="/index/reg" class="rlbg">注册</a></li>
                <div class="clearfix"></div>
            </ul><!--reg-login-click/-->
        @else
            <div class="head-top">

                @include('public/headimg')


                <dl>
                    <dt>
                        <a href="/index/user">
                            <img src="/shop/images/touxiang.jpg"/>
                        </a>
                    </dt>
                    <dd>
                        <h1 class="username">Bb Man<a href="/index/logout" style="font-size:12px;color:orangered;margin-left: 10px">注销</a>
                        </h1>
                        <ul>
                            <li>
                                <a href="/index/car">
                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                    <p>购物车</p></a>
                            </li>
                            <li>
                                <a href="/index/enshrine">
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    <p>收藏夹</p>
                                </a>
                            </li>
                            <li style="background:none;">
                                <a href="/index/orderlist">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <p>订单</p>
                                </a>
                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </dd>
                    <div class="clearfix"></div>
                </dl>
            </div><!--head-top/-->
            <form action="#" method="get" class="search">
                <input type="text" class="seaText fl"/>
                <input type="submit" value="搜索" class="seaSub fr"/>
            </form><!--search/-->

        @endif
        <div id="sliderA" class="slider">
            @foreach($slideshow as $k =>$v)
                <a href="/index/proinfo/{{$v->good_id}}">
                    <img src="{{config('app.uploads')}}/{{$v->good_img}}" width="100%"/>
                </a>
            @endforeach
        </div><!--sliderA/-->
        <ul class="pronav">
            <li class="recommend" type="1"><a href="javascript:void(0);">精品推荐</a></li>
            <li class="recommend" type="2"><a href="javascript:void(0);">新品推荐</a></li>
            <li class="recommend" type="3"><a href="javascript:void(0);">热销推荐</a></li>
            <li class="recommend" type="4"><a href="javascript:void(0);">价格推荐</a></li>
            <div class="clearfix"></div>
        </ul><!--pronav/-->
        <div class="index-pro1" id="indexdiv">
            @foreach($good as $k => $v)
                <div class="index-pro1-list">
                    <dl>
                        <dt>
                            <a href="/index/proinfo/{{$v->good_id}}">
                                <img src="{{config('app.uploads')}}/{{$v->good_img}}" width="100%" height="100%"/>
                            </a>
                        </dt>
                        <dd class="ip-text">
                            <a href="/index/proinfo/{{$v->good_id}}">{{$v->good_name}}</a>
                            <span>已售：{{$v->out_num}}</span>
                        </dd>
                        <dd class="ip-price"><strong>¥{{$v->good_onprice}}</strong> <span>¥{{$v->good_outprice}}</span>
                        </dd>
                    </dl>
                </div>
            @endforeach
            <div class="clearfix"></div>
        </div><!--index-pro1/-->
        <div class="prolist">
            @foreach($good2 as $k=>$v)
                <dl>
                    <dt><a href="/index/proinfo/{{$v->good_id}}">
                            <img src="{{config('app.uploads')}}/{{$v->good_img}}" width="100" height="100"/>
                        </a>
                    </dt>
                    <dd>
                        <h3>
                            <a href="/index/proinfo/{{$v->good_id}}">{{$v->good_name}}</a>
                        </h3>
                        <div class="prolist-price">
                            <strong>¥{{$v->good_onprice}}</strong>
                            <span>¥{{$v->good_outprice}}</span>
                        </div>
                        <div class="prolist-yishou">
                            <span>5.0折</span>
                            <em>已售：{{$v->out_num}}</em>
                        </div>
                    </dd>
                    <div class="clearfix"></div>
                </dl>
            @endforeach

        </div><!--prolist/-->
        <div class="joins">
            <a href="fenxiao.html"><img src=""/></a>
        </div>
        <div class="copyright">Copyright &copy; <span class="blue">Paint</span></div>

        @section('footer')
            @include('public/footer')
        @endsection
    </div><!--maincont-->
    <script>
        $(function () {
            $('.recommend').click(function () {
                var type = $(this).attr('type');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/index/index",
                    data: {type: type}
                }).done(function (msg) {
                    if (msg) {
                        $('#indexdiv').html(msg);
                    }
                });
            });
        })
    </script>
@endsection
