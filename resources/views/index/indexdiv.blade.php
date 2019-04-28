@foreach($good as $k => $v)
            <div class="index-pro1-list">
                <dl>
                    <dt>
                        <a href="/index/proinfo/{{$v->good_id}}">
                            <img src="{{config('app.uploads')}}/{{$v->good_img}}"/>
                        </a>
                    </dt>
                    <dd class="ip-text">
                        <a href="/index/proinfo/{{$v->good_id}}">{{$v->good_name}}</a>
                        <span>已售：{{$v->out_num}}</span>
                    </dd>
                    <dd class="ip-price"><strong>¥{{$v->good_onprice}}</strong> <span>¥{{$v->good_outprice}}</span></dd>
                </dl>
            </div>
@endforeach
            <div class="clearfix"></div>