<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script>
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <title>收藏</title>
</head>
<body>
<a href="/"><h1>返回首页</h1></a>
<table border="1" width="100%">
    <tr>
        <th>商品名</th>
        <th>商品图</th>
        <th>价格</th>
        <th>操作</th>
    </tr>
    @foreach($enshrine as $k=>$v)
    <tr align="center">
        <td>{{$v['good_name']}}</td>
        <td><img src="{{config('app.uploads')}}/uploads/{{$v['good_img']}}" alt="" style="width:100px"></td>
        <td>{{$v['good_onprice']}}</td>
        <td><a href="javascript:;" class="del" good_id="{{$v['good_id']}}">取消收藏</a></td>
    </tr>
    @endforeach
</table>

<script>
    $(function () {
        $('.del').click(function () {
            _this=$(this);
            var good_id=_this.attr('good_id');
            $.post(
                '/index/addenshrine',
                {good_id:good_id},
                function (msg) {
                    if (msg.code==3){
                        alert(msg.msg);
                        _this.parents('tr').hide();
                    }else{
                        alert(msg.msg);
                    }
                },
                'json'
            );
        });
    })

</script>
</body>
</html>