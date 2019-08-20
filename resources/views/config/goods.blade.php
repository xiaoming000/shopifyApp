@extends('admin')

@section('content')
    <div style="padding: 15px;font-size: 20px;text-align: center;">商店配置</div>
    <div style="padding: 20px;width: 500px;">
        <div class="layui-collapse" lay-accordion="">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">自动商品上传</h2>
                <div class="layui-colla-content layui-show">
                    @if(!empty(session('shops')))
                        @foreach(json_decode(session('shops'),true) as $shop)
{{--                            <dd><a href=""></a></dd>--}}
                            <div>
                                <div>
                                    <span style="line-height: 40px;">
                                        <i class="layui-icon layui-icon-set-fill" style="padding-right: 10px;"></i>
                                        {{$shop['shop']}}
                                    </span>
                                    <div class="layui-form" style="float: right;">
                                        <input id="push-open" type="checkbox" name="zzz" lay-skin="switch" lay-text="开启|关闭">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>

        layui.use(['element', 'layer'], function(){
            var element = layui.element;
            var layer = layui.layer;

            //监听折叠
            element.on('collapse(test)', function(data){
                layer.msg('展开状态：'+ data.show);
            });
        });
        layui.use('form', function(){
            var form = layui.form;

            //监听提交
            // form.on('submit(formDemo)', function(data){
            //     layer.msg(JSON.stringify(data.field));
            //     return false;
            // });
        });
        //主动加载jquery模块
        layui.use(['jquery', 'layer'], function(){
            var $ = layui.$ //重点处
                ,layer = layui.layer;
            //后面就跟你平时使用jQuery一样
            $("#push-open").click(function () {
                alert("");
            });
        });
    </script>

@endsection