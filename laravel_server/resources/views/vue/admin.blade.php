@extends('master')


@section('title', 'Administration')

@section('links')
    <b-navbar-nav>
        <b-nav-item href="#">Administration</b-nav-item>
        <b-nav-item href="#">Platform Email</b-nav-item>
        <b-nav-item href="#">Deck Management</b-nav-item>
        <b-nav-item href="#">Statistics</b-nav-item>
    </b-navbar-nav>
@endsection

@section('content')
    <div class="container">
        Aqui vai ser a administração
    </div>
@endsection

@section('pagescript')
    <script src="js/admin.js"></script>

@stop
