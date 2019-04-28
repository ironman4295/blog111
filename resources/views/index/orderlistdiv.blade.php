@foreach($order as $k=>$v)
    <div class="dingdanlist">
        <table>
            <tr>
                <td colspan="2" width="65%">订单号：<strong>{{$v['order_on']}}</strong></td>
                <td width="35%" align="right">
                    <div class="qingqu"><a href="javascript:;" class="orange order_id" order_id="{{$v['order_id']}}">取消订单</a>
                    </div>
                </td>
            </tr>
            @foreach($v['son'] as $k=>$x)
                <tr>
                    <td class="dingimg" width="15%">
                        <a href="/index/goodlist/{{$x['good_id']}}">
                            <img src="{{config('app.uploads')}}/{{$x['good_img']}}"/>
                        </a>
                    </td>
                    <td width="50%">
                        <a href="/index/goodlist/{{$x['good_id']}}">
                            <h3>{{$x['good_name']}}</h3>
                        </a>
                        <time>下单时间：{{date('Y-m-d h:i',$v['create_time'])}}</time>
                    </td>
                    <td align="right">
                        <a href="/index/goodlist/{{$x['good_id']}}">
                            <img src="images/jian-new.png"/>
                        </a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3"><strong class="orange">¥{{$v['totalmoney']}}</strong></th>
            </tr>

        </table>
    </div><!--dingdanlist/-->
@endforeach