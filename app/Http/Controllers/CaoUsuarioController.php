<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaoUsuario;
use App\PermissaoSistema;

class CaoUsuarioController extends Controller
{

    public function get(Request $request)
    {
        $users = CaoUsuario::consultor();
        return view('home', ['users' => $users]);
    }

    public function getRelatorio(Request $request)
    {
    	$users = json_decode($request->info);
    	//$users= ["bruno.freitas", "felipe.chahad", "renato.pereira"];
    	//$inic = "2007-01";
    	//$fin = "2007-12";
        $data = CaoUsuario::getRelatorio($users, $request->inic, $request->fin);
        return $data->get();
    }

}
