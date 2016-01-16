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
<form id="addCategory" method="post" action="{{URL::route('admin/category/add/post')}}">
	<div>
		<span>上级分类：</span>
		<select name="parent_id">
			<option value="0">顶级分类</option>
			@foreach($list as $value)
				<option value="{{$value['id']}}">
					@if(isset($value['html']))
						{{$value['html']}}
					@endif
					{{$value['category_name']}}
				</option>
			@endforeach
		</select>
	</div>
	<div>
		<span>分类名：</span>
		<input type="text" name="category_name"/>
	</div>
	<div>
		<span>别名：</span>
		<input type="text" name="as_name"/>
	</div>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div>
		<span>SEO标题：</span>
		<input type="text" name="seo_title"/>
	</div>
	<div>
		<span>SEO关键字：</span>
		<input type="text" name="seo_key"/>
	</div>
	<div>
		<span>SEO描述：</span>
		<input type="text" name="seo_desc"/>
	</div>
	<div>
		<input type="submit" value="添加"/>
	</div>
</form>
@stop