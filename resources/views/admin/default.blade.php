<html>
<head>
    @section('head')
    @show
	<style type="text/css">
		body{margin:0;}
		a{text-decoration:none;}
		#menu{
			padding:5px;
			background-color:#dddddd;
		}
		#menu li{
			float:left;
			list-style:none;
		}
		#menu li a{
			display:block;
			padding-right:20px;
		}
		.clr{clear:both;}
		.content{margin:20px;}
		
	@section('css')
    @show
    </style>

</head>
<body>

    <div id="menu">
        <ul>
            <li><a href="{{URL::route('admin/article/get')}}">文章管理</a></li>
            <li><a href="{{URL::route('admin/category/get')}}">分类管理</a></li>
            <li><a href="{{URL::route('admin/tag/get')}}">标签管理</a></li>
        </ul>
		<div class="clr"></div>
    </div>
	
    <div class="content">
        @yield('content')
    </div>

</body>
</html>