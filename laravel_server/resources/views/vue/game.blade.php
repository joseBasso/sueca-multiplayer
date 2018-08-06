@extends('master')


@section('title', 'Sueca Online')

@section('links')
    <nav-items></nav-items>
@endsection

@section('content')
    <div class="container">
        <router-view></router-view>
    </div>
@endsection

@section('pagescript')
    <script src="js/app.js"></script>

@stop
