<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function testing(Request $request){
        return "Probando el controlador de categorias";
    }
}
