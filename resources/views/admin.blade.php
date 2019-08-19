<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Aphrodite</title>
  <link rel="stylesheet" href="/layui/css/layui.css">
  <script src="/layui/layui.js"></script>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">Aphrodite</div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item"><a href="">控制台</a></li>
<!--       <li class="layui-nav-item"><a href="">商品管理</a></li>
      <li class="layui-nav-item"><a href="">用户</a></li>
      <li class="layui-nav-item">
        <a href="javascript:;">其它系统</a>
        <dl class="layui-nav-child">
          <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd>
        </dl>
      </li> -->
    </ul>
    <ul class="layui-nav layui-layout-right">

      <li class="layui-nav-item" style="">
        <a href="javascript:;">
        <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
          @if(auth()->check())
            {{auth()->user()->name}}
          @endif
        </a>
        <dl class="layui-nav-child">
          @if(!empty(session('shops')))
            @foreach(json_decode(session('shops'),true) as $shop)
              <dd><a href="">{{$shop['shop']}}</a></dd>
            @endforeach
          @endif
        </dl>
      </li>
      <li class="layui-nav-item"><a href="{{url('home')}}">HOME</a></li>
    </ul>
  </div>

  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="javascript:;">订单管理</a>
          <dl class="layui-nav-child">
            <dd><a href="{{url('order')}}">已付款订单</a></dd>
            <!-- <dd><a href="javascript:;">其他订单</a></dd> -->
            <!-- <dd><a href="javascript:;">列表三</a></dd> -->
          </dl>
        </li>
{{--        <li class="layui-nav-item">--}}
{{--          <a href="javascript:;">商品管理</a>--}}
{{--          <dl class="layui-nav-child">--}}
{{--            <dd><a href="javascript:;">列表一</a></dd>--}}
{{--            <dd><a href="javascript:;">列表二</a></dd>--}}
{{--            <dd><a href="">超链接</a></dd>--}}
{{--          </dl>--}}
{{--        </li>--}}
{{--        <li class="layui-nav-item"><a href="">用户管理</a></li>--}}
{{--        <li class="layui-nav-item"><a href="">配置管理</a></li>--}}
        <li class="layui-nav-item"><a href="{{url('config/goods')}}">配置管理</a></li>
      </ul>
    </div>
  </div>

  <div class="layui-body">
    <!-- 内容主体区域 -->
    <!-- <div style="padding: 15px;">admin主页 内容待计划</div> -->
    @yield('content')
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
{{--    © layui.com--}}
  </div>
</div>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</body>
</html>