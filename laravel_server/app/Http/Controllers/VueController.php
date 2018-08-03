<?php

namespace App\Http\Controllers;

class VueController extends Controller
{
    public function game()
    {
        return view('vue.game');
    }

    public function admin()
    {
        return view('vue.admin');
    }
}
