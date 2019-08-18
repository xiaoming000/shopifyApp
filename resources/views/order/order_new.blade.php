<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Aphrodite</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
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
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="" href="javascript:;">订单管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="{{url('order')}}">已付款订单</a></dd>
                            <!-- <dd><a href="javascript:;">其他订单</a></dd> -->
                            <!-- <dd><a href="javascript:;">列表三</a></dd> -->
                        </dl>
                    </li>
                    {{-- <li class="layui-nav-item">--}}
                    {{-- <a href="javascript:;">商品管理</a>--}}
                    {{-- <dl class="layui-nav-child">--}}
                    {{-- <dd><a href="javascript:;">列表一</a></dd>--}}
                    {{-- <dd><a href="javascript:;">列表二</a></dd>--}}
                    {{-- <dd><a href="">超链接</a></dd>--}}
                    {{-- </dl>--}}
                    {{-- </li>--}}
                    {{-- <li class="layui-nav-item"><a href="">用户管理</a></li>--}}
                    {{-- <li class="layui-nav-item"><a href="">配置管理</a></li>--}}
                    {{-- <li class="layui-nav-item"><a href="">系统管理</a></li>--}}
                    <li class="layui-nav-item"><a href="">配置管理</a></li>
                </ul>
            </div>
        </div>

        <div class="layui-body">
            <!-- 内容主体区域 -->
            <div class="layui-fluid">
                <br>
                <div class="layui-form-item layui-form-pane">
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 85px;">店铺</label>
                        <div class="layui-input-inline" style="width: 165px;">
                            <input type="text" name="merchant_no" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 85px;">订单</label>
                        <div class="layui-input-inline" style="width: 165px;">
                            <input type="text" name="order_no" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 95px;">时间</label>
                        <div class="layui-input-inline" style="width: 165px;">
                            <input type="text" class="layui-input" name="start_time" id="test5" placeholder="yyyy-MM-dd HH:mm:ss">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 165px;">
                            <input type="text" class="layui-input" name="end_time" id="test6" placeholder="yyyy-MM-dd HH:mm:ss">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button id="fuck-btn" class="layui-btn layui-btn-normal" data-type="reload"><i class="layui-icon">&#xe615;</i>查询</button>
                        <button id="reloadtable" class="layui-btn layui-btn-normal"><i class="layui-icon">&#x1002;</i>刷新</button>
                    </div>
                </div>
                <table class="layui-hide" id="test" lay-filter="tableclick"></table>
            </div>

        </div>

        <div class="layui-footer">
            <!-- 底部固定区域 -->
            {{-- © layui.com--}}
        </div>
    </div>

    <script src="/layui/layui.js"></script>
    <script>
        //JavaScript代码区域
        layui.use('element', function() {
            var element = layui.element;

        });
    </script>

    <script>
        // 加载需要用到的模块，如果有使用到自定义模块也在此加载
        layui.use(['laydate', 'form', 'table'], function() {
            // 初始化元素，如果有大量的元素操作可以也引入和初始化element模块
            var table = layui.table;
            var form = layui.form;
            var laydate = layui.laydate;
            var $ = layui.$;
            // 定义时间选择器
            laydate.render({
                elem: '#test5',
                type: 'datetime'
            });
            laydate.render({
                elem: '#test6',
                type: 'datetime'
            });
            // 动态数据表渲染
            table.render({
                elem: '#test' /* 绑定表格容器id */ ,
                url: '/order/paid/' /* 获取数据的后端API URL */ ,
                method: 'get' /* 使用什么协议，默认的是GET */ ,
                cellMinWidth: 60 /* 最小单元格宽度 */ ,
                cols: [
                        [{
                            field: 'shop_name',
                            title: '店铺',
                            align: 'center',
                        }, {
                            field: 'shopify_id',
                            title: '订单编号',
                            align: 'center'
                        }, {
                            field: 'customer_name',
                            title: '客户',
                            align: 'center'
                        }, {
                            field: 'goods',
                            title: '商品列表',
                            align: 'center',
                        }, {
                            field: 'total_price',
                            title: '订单总价',
                            align: 'center',
                            sort:  'true',
                        }, {
                            field: 'is_send',
                            title: '发货状态',
                            align: 'center',
                        }, {
                            field: 'is_send_email',
                            title: '邮件状态',
                            align: 'center',
                        }, {
                            field: 'shopify_created_at',
                            title: '创建时间',
                            align: 'center',
                            sort:  true,
                        }, {
                            field: 'options',
                            title: '操作',
                            align: 'center',
                            toolbar: '#options',
                        }]
                    ] // 使用sort将自动为我们添加排序事件，完全不用人工干预
                    ,
                page: true,
                limit: 10,
                id: 'testReload' // 这里就是重载的id
            });
            // 数据表重载，这个是配合上面的表格一起使用的
            var active = {
                reload: function() {
                    table.reload('testReload', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            merchant_no: $("input[name='merchant_no']").val(),
                            order_no: $("input[name='order_no']").val(),
                            start_time: $("input[name='start_time']").val(),
                            end_time: $("input[name='end_time']").val()
                        }
                    });
                }
            };

            form.render(); // 渲染表单
            // 查找点击时间，这里的事件绑定建议使用on来绑定,因为元素都是后期渲染过的
            $("#fuck-btn").click(function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
            $("#reloadtable").click(function() {
                active.reload();
            });

            table.on('tool(tableclick)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的DOM对象

                if (layEvent === 'send') { //发货
                    if (obj.data.is_send_email) {
                        layer.msg('已发送, 别重复操作');
                    } else {                       
                        $.ajax({
                            url: '/order/isSend',
                            type: 'get',
                            data: {
                                order_id: obj.data.shopify_id
                            },
                            beforeSend: function() {
                                this.index = layer.load(0, {
                                    shade: [0.5, '#393D49']
                                });
                            },
                            success: function(data) {
                                if (data.status == 'error') {
                                    layer.msg(data.msg, {
                                        icon: 5
                                    }); //失败的表情
                                    o.removeClass('layui-btn-disabled');
                                    return;
                                } else {
                                    layer.msg(data.msg, {
                                        // icon: 6, //成功的表情
                                        time: 0001 //1秒关闭（如果不配置，默认是3秒）
                                    }, function() {
                                        location.reload();
                                    });
                                }
                            },
                            complete: function() {
                            },
                        });
                    }

                } 
                
                if (layEvent === 'cancel' && obj.data.is_send == 0) { //删除
                    layer.confirm('确定取消订单' + obj.data.shopify_id + '?', function(index) {
                        //向服务端发送取消指令
                        $.ajax({
                            url: '/order/isCancel',
                            type: 'get',
                            data: {
                                order_id: obj.data.shopify_id
                            },
                            beforeSend: function() {
                                this.index = layer.load(0, {
                                    shade: [0.5, '#393D49']
                                });
                            },
                            success: function(data) {
                                if (data.status == 'error') {
                                    layer.msg(data.msg, {
                                        icon: 5
                                    }); //失败的表情
                                    o.removeClass('layui-btn-disabled');
                                    return;
                                } else {
                                    layer.msg(data.msg, {
                                        // icon: 6, //成功的表情
                                        time: 0001 //1秒关闭（如果不配置，默认是3秒）
                                    }, function() {
                                        location.reload();
                                    });
                                }
                            },
                            complete: function() {
                                // obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                // layer.close(index);
                            },
                        });
                    });
                }

                if (layEvent === 'cancel' && obj.data.is_send == 1) {
                    layer.msg('已发货, 暂时不支持取消订单');
                }
            });
        });
    </script>

    <script type="text/html" id="options">
        <a class="layui-btn layui-btn-xs" lay-event="send">发货</a>
        <a class="layui-btn layui-btn-xs" lay-event="cancel">取消订单</a>
    </script>


</body>

</html>