@extends('admin.default')

@section('head')

@stop

@section('css')
	#addCategory{
		margin-top:20px;
		margin-left:20px;
	}
	#addCategory div{margin-bottom:10px;}
@stop

@section('content')
<h3>添加分类</h3>
<form id="addCategory" method="post" action="{{URL::route('admin/tag/add/post')}}">
	<div>
		<span>标签名称：</span>
		<input type="text" name="name"/>
	</div>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div>
		<input type="submit" value="添加"/>
	</div>
</form>
@stop