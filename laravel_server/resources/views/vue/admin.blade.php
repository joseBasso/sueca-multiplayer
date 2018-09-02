@extends('master')


@section('title', 'Administration')

@section('links')
    <nav-items-admin></nav-items-admin>
@endsection

@section('content')
    <div class="container">
        <router-view></router-view>
    </div>

@endsection

@section('pagescript')
    <script src="js/admin.js"></script>

@stop
