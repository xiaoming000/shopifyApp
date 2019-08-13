@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="text-align: center;">
                <form role="form" class="form-inline" method="post" action="{{url('register_shop')}}" style="padding:20px;margin: 50px 0px;">
                    {{ csrf_field() }}
                    <div class="form-group" style="padding-left: 50px;">
{{--                        <label for="name">请输入您的商店名：</label>--}}
                        <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="商店名">
                        <span style="font-size: 20px;padding-left: 2px;">myshopify.com</span>
                    </div>
                    <button type="submit" class="btn btn-default" style="margin-left: 50px;">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
