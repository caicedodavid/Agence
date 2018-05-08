<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaoUsuario;

class CaoUsuarioController extends Controller
{

    public function get(Request $request)
    {
        $caoUsuario = new CaoUsuario();
        dd($caoUsuario->all());
    }

}
