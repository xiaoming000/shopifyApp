@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span>商店</span>
                    <a href="{{url('register_shop')}}" style="float: right">添加</a>
                </div>

                <div class="card-body">
                    <ul class="list-group">
                        @if(empty($shops))
                            <li class="list-group-item">您还没有添加任何商店，去添加！</li>
                        @else
                            <li class="list-group-item"><a href="{{url('admin/shop/0')}}">MANAGEMENT ALL</a></li>
                            @foreach($shops as $shop)
                                <li class="list-group-item"><a href="{{url('admin/shop/'.$shop['id'])}}">{{$shop['shop']}}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
