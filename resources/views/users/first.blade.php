<link href="{{asset('css/page.css')}}" rel="stylesheet" type="text/css" />



<form action="">
	<input name="username" value="{{$username}}" placeholder="请输入姓名关键字">

	<input name="age" value="{{$age}}" placeholder="请输入年龄关键字">

	<button>搜索</button>
</form>


@foreach($data as $key=>$val)
<p>
	ID:{{$val->id}}
 	name:{{$val->username}}
  	年纪:{{$val->age}}
   	图片:<img src="http://uploads.17shou.com/{{$val->head}}" height="80" width="100">
   	<a href="{{route('edituser',['id'=>$val->id])}}">编辑</a> | 
   	<a href="{{route('deluser',['id'=>$val->id])}}">删除</a>
</p>
@endforeach

{{$data->links()}}