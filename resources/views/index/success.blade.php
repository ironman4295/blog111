@extends('layouts.shop')
@section('title','订单')
@section('content')

    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="susstext">订单提交成功</div>
     <div class="sussimg">&nbsp;</div>
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="50%">
         <h3>订单号：{{$order['order_on']}}</h3>
         <time>创建日期：{{date('Y-m-d',$order['create_time'])}}<br />
            失效日期：{{date('Y-m-d',$order['create_time']+(3600*24))}}</time>
         <strong class="orange">¥{{$order['totalmoney']}}</strong>
        </td>
        <td align="right"><span class="orange">等待支付</span></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     <div class="succTi orange">请您尽快完成付款，否则订单将被取消</div>
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <td width="50%"><a href="/index/prolist" class="jiesuan" style="background:#5ea626;">继续购物</a></td>
       <td width="50%"><a href="/pay/{{$order['order_on']}}" class="jiesuan">立即支付</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
@endsection
