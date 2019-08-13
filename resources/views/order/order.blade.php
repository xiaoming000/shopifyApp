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
                <li class="layui-nav-item">
                    <!--         <a href="javascript:;">
          <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
          贤心
        </a>
        <dl class="layui-nav-child">
          <dd><a href="">基本资料</a></dd>
          <dd><a href="">安全设置</a></dd>
        </dl> -->
                </li>
                <!-- <li class="layui-nav-item"><a href="">退了</a></li> -->
            </ul>
        </div>

        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="" href="javascript:;">订单管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="order">所有订单</a></dd>
                            <dd><a href="javascript:;">异常订单</a></dd>
                            <!-- <dd><a href="javascript:;">列表三</a></dd> -->
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">商品管理</a>
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;">列表一</a></dd>
                            <dd><a href="javascript:;">列表二</a></dd>
                            <dd><a href="">超链接</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="">用户管理</a></li>
                    <li class="layui-nav-item"><a href="">配置管理</a></li>
                    <li class="layui-nav-item"><a href="">系统管理</a></li>
                </ul>
            </div>
        </div>

        <div class="layui-body">
            <!-- 内容主体区域 -->
            <!-- <div style="padding: 15px;">admin主页 内容待计划</div> -->

            <div style="margin-bottom: 5px;">

                <!-- 示例-970 -->
                <ins class="adsbygoogle" style="display:inline-block;width:970px;height:90px" data-ad-client="ca-pub-6111334333458862" data-ad-slot="3820120620"></ins>

            </div>

            <div class="layui-btn-group demoTable">
                <button class="layui-btn" data-type="getCheckData">获取选中行数据</button>
                <button class="layui-btn" data-type="getCheckLength">获取选中数目</button>
                <button class="layui-btn" data-type="isAll">验证是否全选</button>
            </div>

            <table class="layui-table" lay-filter="demo">
                <tbody>
                    <tr>
                        <td>id</td>
                        <td>username</td>
                    </tr>
                    <tr>
                        <td>{{ $code }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="layui-footer">
            <!-- 底部固定区域 -->
            © layui.com - 底部固定区域
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
        var table = layui.table;

        //转换静态表格
        table.init('demo', {
            height: 315 //设置高度
                ,
            limit: 10 //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
            //支持所有基础参数
        });
    </script>

    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>


    <script src="/layui/layui.js" charset="utf-8"></script>
    <!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
    <script>
        layui.use('table', function() {
            var table = layui.table;
            //监听表格复选框选择
            table.on('checkbox(demo)', function(obj) {
                console.log(obj)
            });
            //监听工具条
            table.on('tool(demo)', function(obj) {
                var data = obj.data;
                if (obj.event === 'detail') {
                    layer.msg('ID：' + data.id + ' 的查看操作');
                } else if (obj.event === 'del') {
                    layer.confirm('真的删除行么', function(index) {
                        obj.del();
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    layer.alert('编辑行：<br>' + JSON.stringify(data))
                }
            });

            var $ = layui.$,
                active = {
                    getCheckData: function() { //获取选中数据
                        var checkStatus = table.checkStatus('idTest'),
                            data = checkStatus.data;
                        layer.alert(JSON.stringify(data));
                    },
                    getCheckLength: function() { //获取选中数目
                        var checkStatus = table.checkStatus('idTest'),
                            data = checkStatus.data;
                        layer.msg('选中了：' + data.length + ' 个');
                    },
                    isAll: function() { //验证是否全选
                        var checkStatus = table.checkStatus('idTest');
                        layer.msg(checkStatus.isAll ? '全选' : '未全选')
                    }
                };

            $('.demoTable .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>
</body>

</html>