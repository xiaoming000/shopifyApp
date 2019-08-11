@extends('admin')

@section('content')
    <div class="container">
        @foreach ($order_variant as $user)
            {{ $user->id }}
        @endforeach
    </div>

@endsection