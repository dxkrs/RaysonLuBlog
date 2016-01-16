@extends('admin.default')

@section('head')

@stop

@section('content')

<div style="margin:10px">
<a style="padding:5px;background-color:#5cb85c;color:white;" href="{{URL::route('admin/tag/add')}}">创建标签</a>
</div>

<div>
	<table border=1 cellspacing=0 style="width:50%;">
		<tr>
			<th>#</th>
			<th>名称</th>
			<th>引用次数</th>
			<th>操作</th>
		</tr>
		
		@foreach($list as $value)
			<tr>
				<td>{{isset($i)?++$i:$i=1}}</td>
				<td>
				{{$value['name']}}
				</td>
				<td>
				{{$value['use_number']}}
				</td>
				<td><a>修改</a></td>
			</tr>
			
		@endforeach
	</table>
</div>
@stop