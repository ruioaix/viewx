<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index() {
        return view('home');
    }

    public function about() {
        return view('about');
    }

    public function onlyhas() {
        return view('rxqs.onlyhas');
    }

    public function onlyin() {
        return view('rxqs.onlyin');
    }

    public function all() {
        return view('rxqs.all');
    }
}
