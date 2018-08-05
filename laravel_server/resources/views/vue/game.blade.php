@extends('master')


@section('title', 'Sueca Online')

@section('links')
    <b-navbar-nav>
        @guest
            <b-nav-item><router-link to="/login" tag="button">Login</router-link></b-nav-item>
            <b-nav-item><router-link to="/register" tag="button">Register</router-link></b-nav-item>

        @else
        <b-nav-item href="#">Account</b-nav-item>
        <b-nav-item href="#">Statistics</b-nav-item>
        <b-nav-item href="#">Lobby</b-nav-item>
        <b-nav-item href="#">Game</b-nav-item>
        @endguest

    </b-navbar-nav>
@endsection

@section('content')
    <div class="container">
        <router-view></router-view>
    </div>
@endsection

@section('pagescript')
    <script src="js/app.js"></script>

@stop
