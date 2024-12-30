<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function testing(Request $request){
        return "Probando el controlador de usuarios";
    }

    public function register(Request $request){
        return "Aqui se registrara el usuario";
    }
    public function login(Request $request){
        return "Aqui se logueara el usuario";
    }
}
