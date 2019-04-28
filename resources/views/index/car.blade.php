@extends('layouts.shop')
@section('title', '购物车')

@section('content')
    <div class="maincont">
        <header>
            <a href="javascript:history.back(-1)" class="back-off fl"><span
                        class="glyphicon glyphicon-menu-left"></span></a>
            <div class="head-mid">
                <h1>购物车</h1>
            </div>
        </header>
        <div class="head-top">
            @include('public/headimg')
        </div><!--head-top/-->
        <table class="shoucangtab">
            <tr>
                <td width="75%"><span class="hui">已选：<strong class="orange" id="checkednum">0</strong>件商品</span></td>
                <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
                    <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
                </td>
            </tr>
        </table>
        <div class="dingdanlist">
            <table>
                <tr>
                    <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" id="allbox"/> 全选</a></td>
                </tr>
            </table>
        </div><!--dingdanlist/-->
       
            @foreach($flag as $k => $v)
                <div class="dingdanlist">
                    <table>
                        <tr good_id="{{$v->good_id}}">
                            <td width="4%">
                                <input type="checkbox" class="box"/>
                            </td>
                            <td class="dingimg" width="15%">
                                <a href="/index/proinfo/{{$v->good_id}}">
                                    <img src="{{config('app.uploads')}}/{{$v->good_img}}"/>
                                </a>
                            </td>
                            <td width="50%">
                                <h3>
                                    <a href="/index/proinfo/{{$v->good_id}}">
                                    {{$v->good_name}}
                                    </a>
                                </h3>
                                <time>加入时间：{{date('Y-m-d H:i',$v->add_time)}}</time>
                            </td>
                            <td align="right">
                                <div good_num="{{$v->good_num}}" good_id="{{$v->good_id}}">
                                    <button style="float:right" class="add">+</button>
                                    <input type="text" style="width: 40px;float:right" class="amount" value="{{$v->buy_num}}"/>
                                    <button style="float:right" class="less">-</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" width="20%">
                                <strong class="orange">¥
                                    <b class="orange">{{$v->total}}</b>

                                </strong>

                            </th>
                            <th>
                                <span style="font-size:12px;color: #888888;">单价：{{$v->good_onprice}}</>
                            </th>
                            <th width="60%" align="right">
                                <a href="javascript:;" class="del" style="font-size:12px;color: #a94442;">删除</a>
                            </th>
                        </tr>
                    </table>
                </div><!--dingdanlist/-->
            @endforeach
        

        <div class="height1"></div>
        @include('public.footertopay')
    </div><!--maincont-->
<script>
    $(function () {
        layui.use('layer',function(){
            var layer=layui.layer;
            //加/减/失去焦点
            $('.add').click(function(){
                var amount=parseInt($(this).next('input').val());//购买总计
                var good_num=parseInt($(this).parent('div').attr('good_num'));//库存
                var good_id=parseInt($(this).parent('div').attr('good_id'));//商品id
                if (amount>=good_num) {
                    $(this).prop('disabled',true);
                    $(this).next('input').val(good_num);
                }else{
                    amount=amount+1;
                    $(this).next('input').val(amount);
                    $(this).next('button').prop('disabled',false);
                }
                saveBuynum(good_id,amount);
                $(this).parents('tr').find('input[class="box"]').prop('checked',true);
                gettotal($(this));
                getMoneytotal();
            });
            $('.less').click(function(){
                var good_id=parseInt($(this).parent('div').attr('good_id'));//商品id
                var amount=parseInt($(this).prev('input').val());
                var good_num=parseInt($(this).parent('div').attr('good_num'));//库存
                if (amount<=1) {
                    $(this).prop('disabled',true);
                    $(this).prev('input').val(1);
                }else{
                    amount=amount-1;
                    $(this).prev('input').val(amount);
                    $(this).prev().prev().prop('disabled',false);
                }
                saveBuynum(good_id,amount);
                $(this).parents('tr').find('input[class="box"]').prop('checked',true);
                gettotal($(this));
                getMoneytotal();
            });
            $('.amount').blur(function(){
                var amount=$(this).val();
                var good_id=parseInt($(this).parent('div').attr('good_id'));//商品id
                var good_num=parseInt($(this).parent('div').attr('good_num'));//库存
                var reg=/^\d{1,}$/;
                if (amount==''||parseInt(amount)<1||!reg.test(amount)) {
                    $(this).val(1);
                }else if(parseInt(amount)>good_num){
                    $(this).val(good_num);
                }else{
                    $(this).val(amount);
                }
                saveBuynum(good_id,amount);
                $(this).parents('tr').find('input[class="box"]').prop('checked',true);
                gettotal($(this));
                getMoneytotal();
            });
            //点击删除
            $(document).on('click','.del',function(){
                var _this=$(this);
                var good_id=_this.parents('tr').prev('tr').attr('good_id');
                //ajax删除
                $.ajax({
                 type : "post",
                  url : "/index/cartdel",
                  data :{good_id:good_id},
                  async : false,
                }).done(function( msg ) {
                    if (msg==1) {
                      alert('删除成功');
                      _this.parents('div [class="dingdanlist"]').hide();
                      _this.parents('div [class="dingdanlist"]').find('input[type="checkbox"]').prop('checked',false);
                    }else{
                      alert('删除失败');
                    }
                });
                getMoneytotal();
            });
            //删除选中
            $(document).on('click','#delcart',function(){
                var good_id='';
                $('.box').each(function(index){
                    if ($(this).prop('checked')==true) {
                        good_id+=$(this).parents('tr').attr('good_id')+',';
                    }
                });
              good_id=good_id.substr(0,good_id.length-1);
                //ajax删除
                $.ajax({
                 type : "post",
                  url : "/index/cartdel",
                  data :{good_id:good_id},
                  async : false,
                }).done(function( msg ) {
                    if (msg==1) {
                      layer.alert('删除成功');
                      location.href="/index/car";
                    }else{
                      alert('删除失败');
                    }
                });
                getMoneytotal();
            });
            //全选
            $('#allbox').click(function(){
                var status=$(this).prop('checked');
                $('.box').prop('checked',status);
                getMoneytotal();
            });
            //多选
            $('.box').click(function(){
                getMoneytotal();
            });
            //点击结算
            $('#close').click(function () {
                var id = '';
                $('.box').each(function () {
                    if ($(this).prop('checked') == true) {
                        id += $(this).parents('tr').attr('good_id') + ',';
                    }
                });
                id = id.substr(0, id.length - 1);
                location.href = "/index/order/" + id;
            });

            //更新选中数量
            function checkednum() {
                var num=0;
                $('.box').each(function(index){
                    if ($(this).prop('checked')==true) {
                      num=num+1;
                    }
                });
                $('#checkednum').text(num);

            }
            //获取小计
            function gettotal(_this){
              good_id=_this.parents('tr').attr('good_id');
              $.post(
                "/index/gettotal",
                {good_id:good_id},
                function(msg){
                  _this.parents('tr').next('tr').find('b[class=orange]').text(msg);
                }
              );
            }
            //获取总价
            function getMoneytotal(){
              var good_id='';
              $('.box').each(function(index){
                if ($(this).prop('checked')==true) {
                  good_id+=$(this).parents('tr').attr('good_id')+',';
                }
              });
              good_id=good_id.substr(0,good_id.length-1);
              $.post(
                  "/index/gettotal",
                  {good_id:good_id},
                  function (msg) {
                      if (msg) {
                          $('#total').text(msg);
                      }
                  }
              );
              checkednum();
            }
            //更新购买数量ok
            function saveBuynum(good_id,buy_num){
              $.ajax({
                 type : "post",
                  url : "/index/savecar",
                  data :{good_id:good_id,buy_num:buy_num},
                  async : false,
                  success : function(data){
                    console.log(data);
                  }
              });
            }

        });

    });
</script>

@endsection
