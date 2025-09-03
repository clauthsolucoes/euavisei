<?php

namespace App\Http\Controllers;

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version2X;

use App\Models\OneSignal;

use Illuminate\Http\Request;

class EventoController extends Controller{
    
    public function listarEventos(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        }
        try{
            $eventos = \DB::table('eventos as e')
            ->join('tipo_evento as t', 't.id', '=', 'e.tipo_evento')
            ->select(
                'e.id as id',
                'e.titulo as titulo',
                'e.data_hora_inicio as inicio',
                'e.data_hora_fim as fim',
                't.tipo as tipo'
            )
            ->orderBy('e.data_hora_registro', 'desc')
            ->get();
            return view('admin/listar-evento', ['eventos' => $eventos]);
        }catch(Exception $e){
            return $e;
        }
    }	
    
    public function cadastrarPageEvento(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        }
        try{
            $tipo_evento = \DB::table('tipo_evento')->get();
            return view('admin/criar-evento', ['tipo_evento' => $tipo_evento]);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function alterarPageEvento(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        }
        $evento_id = $request->get('id');
        try{
            $evento = \DB::table('eventos')->where('id', $id)->first();
            return view('admin/editar-evento', ['evento' => $evento]);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function cadastrarEvento(Request $request){
        $end_completo = $request->get('logradouro') . " " . $request->get('numero') . " " .$request->get('bairro') . " " . $request->get('cidade') . " " . $request->get('estado');

        $api = explode('||', $this->get_lat_long($end_completo));
        $lat = $api[0];
        $long = $api[1];
        $img = $request->get('imagem_buscada');
        
        $dados = array(
            'titulo' => $request->get('titulo'),
            'descricao' => $request->get('descricao'),
            'data_hora_inicio' => $request->get('data-hora-inicio'),
            'data_hora_fim' => $request->get('data-hora-fim'),
            'cep' => $request->get('cep'),
            'logradouro' => $request->get('logradouro'),
            'bairro' => $request->get('bairro'),
            'cidade' => $request->get('cidade'),
            'estado' => $request->get('estado'),
            'numero' => $request->get('numero'),
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_evento' => $request->get('tipo_evento')
        );
        
        if(!$img){
            if($request->file('imagem') !== null){
                $arr = $request->file('imagem');
                $md5Name = md5_file($arr->getRealPath());
                $guessExtension = $arr->guessExtension();
                $file = $md5Name.'.'.$guessExtension;
                $arr->move(public_path('/imgs/eventos'), $file);
                $dados['imagem'] = $file;
                $img = $file;
            }
        }else{
            $newName = explode("/", $img)[1];
            \File::move(public_path('novas_ocorrencias/'. $img), public_path('/imgs/eventos/'. $newName));
            $dados['imagem'] = $newName;
        }
        try{
            $query = "(select calcularDistancia(substring(ponto_notificacao, 8, position(',&quot;long&quot;:' in ponto_notificacao) - 8), substring(ponto_notificacao, position(',&quot;long&quot;:' in ponto_notificacao) + 8, char_length(substring(ponto_notificacao, position(',&quot;long&quot;:' in ponto_notificacao) + 8, char_length(ponto_notificacao))) - 1), $lat, $long))";
            $query = str_replace("&quot;", '"', $query);
            $users_ids = \DB::table('usuarios')
                ->select('one_signal_id')
                ->where('ponto_notificacao', '!=', '')
                ->whereNotNull('ponto_notificacao')
                ->whereNotNull('one_signal_id')
                ->where(\DB::raw($query), '<=', 5)
                ->get();
            $ids = array();
            foreach($users_ids as $item){
                array_push($ids, $item->one_signal_id);
            } 
            $evento = \DB::table('eventos')->insertGetId($dados);
            $os = new OneSignal();
            $os->setUsers($ids);
            //$os->setContent();
            //$os->setHeadings();
            $os->setPost();
            $res = $os->callApi();
            $io = array(
                'id' => $evento,
                'titulo' => $request->get('titulo'),
                'descricao' => $request->get('descricao'),
                'data_hora_inicio' => $request->get('data-hora-inicio'),
                'data_hora_fim' => $request->get('data-hora-fim'),
                'cep' => $request->get('cep'),
                'logradouro' => $request->get('logradouro'),
                'bairro' => $request->get('bairro'),
                'cidade' => $request->get('cidade'),
                'estado' => $request->get('estado'),
                'numero' => $request->get('numero'),
                'latitude' => $lat,
                'longitude' => $long,
                'tipo_evento' => $request->get('tipo_evento'),
                'prioridade' => $request->get('extraoridnario')
            );
            
            $elephant = new Elephant(new Version2X("euavisei.herokuapp.com:80"));
            $elephant->initialize();
            $elephant->emit('registrar-evento', $io);
            $elephant->close();
            
            if($evento){
                return \Redirect::route('eventos');
            }
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function alterarEvento(Request $request){
        
    }
    
    public function deletarEvento(Request $request){
        
    }
    
    public function listarComentarios(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        }
    }
    
    public function comentar(Request $request){
        
    }
    
    public function deletarComentario(Request $request){
        
    }
    
    function get_lat_long($address){
        $address = str_replace(" ", "+", $address);
        $key='AIzaSyB4tTzmUhhxt-p7s91Y6gLFMYrbiYvilt8';
        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=$key");
        $json = json_decode($json);
        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        return $lat.'||'.$long;
    }
}