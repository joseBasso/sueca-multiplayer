@extends('master')


@section('title', 'Sueca Online')

@section('links')
    <b-navbar-nav>
        <b-nav-item href="#">Login</b-nav-item>
        <b-nav-item href="#">Register</b-nav-item>
        <b-nav-item href="#">Account</b-nav-item>
        <b-nav-item href="#">Statistics</b-nav-item>
        <b-nav-item href="#">Lobby</b-nav-item>
        <b-nav-item href="#">Game</b-nav-item>

    </b-navbar-nav>
@endsection

@section('content')
    <div class="container">
        Aqui vai ser o jogo
    </div>
@endsection

@section('pagescript')
    <script src="js/app.js"></script>

@stop
