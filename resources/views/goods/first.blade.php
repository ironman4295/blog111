
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>展示列表哟</title>
	<link href="{{asset('css/page.css')}}" rel="stylesheet" type="text/css" />
</head>
<body>	
	<h3 align="center"><a href="{{'add'}}">添加</a></h3>
	<form action="" align="center">
		<input name="uname" value="{{$uname}}" placeholder="请输入名称关键字">
		<button>搜索</button>
	</form>
	<br />
		<table border="1" align="center" width="900">
			<tr>
				<th>排序</th>
				<th>网站名称</th>
				<th>LOGO</th>
				<th>所属分类</th>
				<th>网站联系人</th>
				<th>网站类型</th>
				<th>操作</th>
			</tr>

		@foreach($data as $key=>$val)
			<tr align="center">
				<td>{{$val->id}}</td>
				<td>{{$val->uname}}</td>
				<td>
					@if($val->class==1)LOGO链接
					@else文字链接
					@endif
				</td>
				<td><img src="http://uploads.17shou.com/{{$val->logo}}" height="80" width="100"></td>
				<td>{{$val->man}}</td>
				<td>
					@if($val->show==1)是
					@else否
					@endif
				</td>
				<td>
					<a href="{{route('editgoods',['id'=>$val->id])}}">编辑</a> |
					<a href="{{route('delgoods',['id'=>$val->id])}}">删除</a>
				</td>
			</tr>
		@endforeach
		</table>
	
</body>
</html>
<div align="center">
{{$data->appends($query)->links()}}
</div>