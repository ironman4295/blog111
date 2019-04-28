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