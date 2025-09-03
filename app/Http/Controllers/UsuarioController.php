<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function adicionarApelido(Request $request){
        $id = $request->get('id');
        $apelido = $request->get('apelido');
        try{
            $update = \DB::table('usuarios')->where('id', $id)->update(
                array(
                    'apelido' => $apelido
                )
            );
            return $update;
        }catch(Exception $e){
            return $e;
        }
    }

    public function usurariosPage(Request $request){
        if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
        try{
            $usuarios = \DB::table('usuarios')->get();
            return view('admin/usuarios', ['usuarios' => $usuarios]);
        }catch(Exception $e){
            return $e;
        }
    }

    public function comentariosPage(Request $request){
        if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
        try{
            $comentarios = \DB::table('comentarios')->get();
            return view('admin/comentarios', ['comentarios' => $comentarios]);
        }catch(Exception $e){
            return $e;
        }
    }
}
