@extends('admin.default')

@section('head')
    <script type="text/javascript" src="{{asset('plugin/ueditor/ueditor.config.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugin/ueditor/ueditor.all.min.js')}}"></script>
@stop

@section('css')
    #addArticleForm{
    margin-top:20px;
    margin-left:20px;
    }
    #addArticleForm>div{margin-bottom:20px;}
@stop

@section('content')

    <form id="addArticleForm">
        <div>
            <span>标题：</span>
            <input type="text"{{isset($article)?'value='.$article['title']:''}} name="title"/>
        </div>
        <div>
            <span>分类：</span>
            <select name="category_id">
                <option value="0">请选择</option>
                @foreach($categoryList as $value)
                    <option value="{{$value['id']}}"
                            {{ isset($article)?($article['category_id']==$value['id'])?'selected="selected"':'':''}}
                            >
                        @if(isset($value['html']))
                            {{$value['html']}}
                        @endif
                        {{$value['category_name']}}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <span>正文：</span>
            <script id="editor" name="content" type="text/plain" style="width:1024px;height:500px;"></script>
        </div>

        <div>
            <span>简介/描述：</span>
            <br>
            <textarea name="desc" cols="80" rows="8">{{isset($article)?$article['desc']:''}}</textarea>
        </div>

        <div>
            <span>标签：</span>
            <input type="text" {{isset($article)&&!empty($article['tag_id'])?'value='.$article['tag_id']:''}}
                   name="tag_id"/>
        </div>

        <div>
            <span>是否显示：</span>
            是<input type="radio" name="is_showed"
                    value="1" {{isset($article)?($article['is_showed']==1)?'checked="checked"':'':'checked="checked"'}}>
            否<input type="radio" name="is_showed"
                    value="0"{{isset($article)?($article['is_showed']==0)?'checked="checked"':'':''}}>
        </div>


        <input id='submit' type="button" value="提交"/>
    </form>

    <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript">
        var ue = UE.getEditor('editor');
        ue.ready(function () {
            @if(isset($article)&&!empty($article['content']))
            ue.setContent(htmldecode("{{$article['content']}}"));
            @endif


        });
        $(function () {
            $('#submit').click(function () {
                var postData = $('#addArticleForm').serialize();
                postData += "&_token={{ csrf_token() }}";

                @if(isset($article['id']))
                postData += "&id={{$article['id']}}";
                $.ajax({
                    type: 'POST',
                    url: "{{URL::route('admin/article/modify/post')}}",
                    dataType: 'json',
                    data: postData,
                    success: function (data) {
                        if (data.data == false) {
                            alert(data.msg);
                        } else {
                            alert(data.msg);
                        }
                    }
                });
                @else
                $.ajax({
                            type: 'POST',
                            url: "{{URL::route('admin/article/add/post')}}",
                            dataType: 'json',
                            data: postData,
                            success: function (data) {
                                if (data.data == false) {
                                    alert(data.msg);
                                } else {
                                    alert(data.msg);
                                }
                            }
                        });
                @endif


            });
        });
        function htmldecode(s) {
            var div = document.createElement('div');
            div.innerHTML = s;
            return div.innerText || div.textContent;
        }
    </script>
@stop