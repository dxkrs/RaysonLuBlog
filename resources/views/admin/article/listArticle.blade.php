@extends('admin.default')

@section('head')
<script src="{{URL::asset('js/jquery.min.js')}}"></script>

@stop


@section('content')
<div style="margin:10px">
    <a style="padding:5px;background-color:#5cb85c;color:white;" href="{{URL::route('admin/article/add')}}">添加文章</a>
</div>

<div>
	<table border=1 cellspacing=0 style="width:60%;">
		<tr>
			<th>#</th>
			<th>标题名</th>
			<th style="width:40px;">是否显示</th>
			<th style="width:30%;">操作</th>
		</tr>
		
		@foreach($articleList as $value)
			<tr>
				<td>{{isset($i)?++$i:$i=1}}</td>
				<td>{{$value['title']}}</td>
                <td>
                    <span>
                    @if ($value['is_showed'] == 1)
                        是
                    @elseif($value['is_showed'] == 0)
                        否
                    @else
                        null
                    @endif
                    </span>
                </td>
				<td><input type="button" id="modify" name="modify" value="修改"
                           onclick="window.open('{{URL::route('admin/article/modify')}}?id={{$value['id']}}')"
                            />
				<input type="button" id="delete" name="delete" value="删除" onclick="delete_click({{$value['id']}})"/></td>
			</tr>
			
		@endforeach
	</table>
</div>

    <script type="text/javascript">
        function modify_click(id){
            alert(id);
        }
        var delete_flag = 1;
        function delete_click(id){
            if(delete_flag = 0){
                return false;
            }
            $.ajax({
                    type:"POST",
                    url:"{{URL::route('admin/article/delete/post')}}",
                    data:{ id:id},
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    beforeSend:function(){
                        delete_flag = 0;
                    },
                    success:function(data){
                        if(data.data = false){
                            alert(data.msg);
                        }else{
                            alert(data.msg)
                        }
                    },
                    complete:function(){
                        delete_flag = 0;
                        window.location.reload();
                    }
            });
        }
    </script>
@stop