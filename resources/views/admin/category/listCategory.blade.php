@extends('admin.default')

@section('head')

@stop

@section('content')

<div style="margin:10px">
<a style="padding:5px;background-color:#5cb85c;color:white;" href="{{URL::route('admin/category/add')}}">创建分类</a>
</div>

<div>
	<table border=1 cellspacing=0 style="width:90%;">
		<tr>
			<th>#</th>
			<th>分类名称</th>
			<th>别称</th>
			<th>操作</th>
		</tr>
		
		@foreach($list as $value)
			<tr>
				<td>{{isset($i)?++$i:$i=1}}</td>
				<td>
				@if(isset($value['html']))
				{{$value['html']}}
				@endif
				{{$value['category_name']}}
				</td>
				<td>
				{{$value['as_name']}}
				</td>
				<td><a>修改</a></td>
			</tr>
			
		@endforeach
	</table>
</div>
@stop