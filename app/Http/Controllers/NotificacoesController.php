<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OneSignal;

class NotificacoesController extends Controller
{
    public function indexPage(Request $request){
        if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
        try{
            $ocorrencias = \DB::table('tipo_ocorrencia')->get();
            $notificacoes = \DB::table('notificacoes as n')->join('tipo_ocorrencia as t', 't.id', '=', 'n.tipo_ocorrencia')->orderBy('n.data_hora_envio', 'desc')->get();
            return view('admin/notificacoes', ['ocorrencias' => $ocorrencias, 'notifications' => $notificacoes]);
        }catch(Exception $e){
            return $e;
        }
    }

    public function enviarNotification(Request $request){
        $tipo_ocorrencia = $request->get('ocorrencia');
        $titulo = $request->get('titulo');
        $descricao = $request->get('desc');
        $url = public_path('imgs/icons/');
        try{
            $users_ids = \DB::table('usuarios')
                ->select('one_signal_id')
            ->get();
            $ids = array();
            foreach($users_ids as $item){
                array_push($ids, $item->one_signal_id);
            } 

            if($tipo_ocorrencia == 1)
                $imagem = $url . 'tiroteio.png';
            if($tipo_ocorrencia == 3)
                $imagem = $url . 'incendio.png';
            if($tipo_ocorrencia == 5)
                $imagem = $url . 'acidente.png';
            if($tipo_ocorrencia == 6)
                $imagem = $url . 'enchente.png';
            
            $os = new OneSignal();
            $os->setUsers($ids);
            $os->setContent($descricao);
            $os->setHeadings($titulo);
            $os->setIcon($imagem);
            $os->setPost();
            $res = $os->callApi();
            return \Redirect::route('notifications');
        }catch(Exception $e){
            return $e;
        }
    }
}
