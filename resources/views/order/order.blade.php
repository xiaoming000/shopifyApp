@extends('admin')

@section('content')

<div style="padding: 15px;">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <input type="text" name="title" placeholder="订单号" class="layui-input">
            <button type="button" class="layui-btn">搜索</button>
        <div class="layui-form-item">
    </form>
</div>

<div style="padding: 15px;">
    <table class="layui-table" lay-filter="demo">
        <tbody>
            <tr>
                <td>店铺名称</td>
                <td>订单编号</td>
                <td>订单客户</td>
                <td>商品列表</td>
                <td>订单总价</td>
                <td>发货状态</td>
                <td>邮件状态</td>
                <td>订单状态</td>
            </tr>
            @foreach ($data as $d)
            <tr>
                <td>{{ $d['shop_name'] }}</td>
                <td>{{ $d['order_id'] }}</td>
                <td>{{ $d['customer_name'] }}</td>
                <td>
                    @foreach ($d['goods'] as $good)
                        <a href= {{ $good['url'] }}>{{ $good['title'] }}</a></br>
                    @endforeach
                </td>
                <td>{{ $d['order_total_price'] }}$</td>
                <td>
                    @if ($d['order_is_send'])
                        <p>已发货</p>
                    @else
                        <input type="text" name="title" placeholder="跟踪号" class="layui-input">
                        <button type="button" class="layui-btn">发货</button>
                    @endif
                </td>
                <td>
                    @if ($d['order_is_send_email'])
                        <p>已发送</p>
                    @else
                        <p>未发送</p>
                    @endif
                </td>
                <td>
                    @if ($d['order_is_close'])
                        <p>已取消</p>
                    @else
                        <button type="button" class="layui-btn">取消订单</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="/layui/layui.js" charset="utf-8"></script>
<script>
    var table = layui.table;

    //转换静态表格
    table.init('demo', {
        height: 315 //设置高度
            ,
        // limit: 10 //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
        //支持所有基础参数
    });
</script>


@endsection