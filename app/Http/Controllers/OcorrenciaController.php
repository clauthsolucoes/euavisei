<?php

namespace App\Http\Controllers;

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X;

use App\Models\OneSignal;

use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    public function listarOcorrencias(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        } 
        try{
            $ocorrencias = \DB::table('ocorrencias as o')
            ->join('tipo_ocorrencia as t', 't.id', '=', 'o.tipo_ocorrencia')
            ->select(
                'o.id as id',
                'o.titulo as titulo',
                'o.descricao as descricao',
                'o.data_hora_ocorrencia as data',
                't.tipo as tipo',
                'o.extraordinario as extraordinario',
                'o.tempo_duracao as duracao'
            )
            ->orderBy('o.data_hora_ocorrencia', 'desc')
            ->get();
            return view('admin/listar-ocorrencia', ['ocorrencias' => $ocorrencias]);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function registrarOcorrencia(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        } 
        try{
            $tipos_ocorrencia = \DB::table('tipo_ocorrencia')->select('id as id', 'tipo as tipo')->get();
            return view('admin/criar-ocorrencia', ['tipo_ocorrencia' => $tipos_ocorrencia]);
        }catch(Exception $e){
            return $e;
        }
    }

    public function alterarOcorrencia(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        }
        $id = $request->id; 
        try{
            $tipos_ocorrencia = \DB::table('tipo_ocorrencia')->select('id as id', 'tipo as tipo')->get();
            $ocorrencia = \DB::table('ocorrencias')->where('id', $id)->first();
            return view('admin/editar-ocorrencia', ['ocorrencia' => $ocorrencia, 'tipo_ocorrencia' => $tipos_ocorrencia]);
        }catch(Exception $e){
            return $e;
        }
    }

    public function cadastrarOcorrencia(Request $request){
        $titulo = $request->get('titulo');
        $descricao = $request->get('descricao');
        $logradouro = $request->get('logradouro');
        $data_hora_ocorrencia = $request->get('data-hora-ocorrencia');
        $bairro = $request->get('bairro');
        $cidade = $request->get('cidade');
        $estado = $request->get('estado');
        $numero = $request->get('numero');
        $img = $request->get('imagem_buscada');

        $end_completo = $logradouro . " " . $numero . " " .$bairro . " " . $cidade . " " . $estado;

        $api = explode('||', $this->get_lat_long($end_completo));
        $lat = $api[0];
        $long = $api[1];
        $tipo = $request->get('tipo-ocorrencia');
        $timer = $request->get('timer');

        $extraordinario = $request->get('extraoridnario');
        $dados = array(
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_hora_ocorrencia' => $data_hora_ocorrencia,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_ocorrencia' => $tipo,
            'tempo_duracao' => $timer,
            'extraordinario' => $extraordinario
        );
        if(!$img){
            if($request->file('imagem') !== null){
                $arr = $request->file('imagem');
                $md5Name = md5_file($arr->getRealPath());
                $guessExtension = $arr->guessExtension();
                $file = $md5Name.'.'.$guessExtension;
                $arr->move(public_path('/imgs/ocorrencias'), $file);
                $dados['imagem'] = $file;
                $img = $file;
            }
        }else{
            $newName = explode("/", $img)[1];
            \File::move(public_path('novas_ocorrencias/'. $img), public_path('/imgs/ocorrencias/'. $newName));
            $dados['imagem'] = $newName;
        }

        try {
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
            $ocorrencia = \DB::table('ocorrencias')->insertGetId($dados);
            $os = new OneSignal();
            $os->setUsers($ids);
            //$os->setContent();
            //$os->setHeadings();
            $os->setPost();
            $res = $os->callApi();
            $io = array(
                'id' => $ocorrencia,
                'title' => $titulo,
                'descricao' => $descricao,
                'ocorrencia' => $data_hora_ocorrencia,
                'imagem' => $img,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'timer' => $timer,
                'icone' => $tipo,
                'lat' => $lat,
                'long' => $long,
                'prioridade' => $extraordinario
            );
            
            $elephant = new Elephant(new Version1X('https://euavisei.adaptable.app'));
            $elephant->initialize();
            $elephant->emit('registrar-ocorrencia', $io);
            $elephant->close();
            
            if($ocorrencia){
                return \Redirect::route('ocorrencias');
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function editarOcorrencia(Request $request){
        $id = $request->get('id');
        $titulo = $request->get('titulo');
        $descricao = $request->get('descricao');
        $logradouro = $request->get('logradouro');
        $data_hora_ocorrencia = $request->get('data-hora-ocorrencia');
        $bairro = $request->get('bairro');
        $cidade = $request->get('cidade');
        $estado = $request->get('estado');
        $numero = $request->get('numero');
        $img = $request->get('imagem_buscada');

        $end_completo = $logradouro . " " . $numero . " " .$bairro . " " . $cidade . " " . $estado;

        $api = explode('||', $this->get_lat_long($end_completo));
        $lat = $api[0];
        $long = $api[1];
        $tipo = $request->get('tipo-ocorrencia');
        $timer = $request->get('timer');

        $extraordinario = $request->get('extraoridnario');
        $dados = array(
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_hora_ocorrencia' => $data_hora_ocorrencia,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_ocorrencia' => $tipo,
            'tempo_duracao' => $timer,
            'extraordinario' => $extraordinario,
            'imagem' => $img
        );
        if(!$img){
            if($request->file('imagem') !== null){
                $arr = $request->file('imagem');
                $md5Name = md5_file($arr->getRealPath());
                $guessExtension = $arr->guessExtension();
                $file = $md5Name.'.'.$guessExtension;
                $arr->move(public_path('/imgs/ocorrencias'), $file);
                $dados['imagem'] = $file;
                $img = $file;
            }
        }
        try {
            $ocorrencia = \DB::table('ocorrencias')->where('id', $id)->update($dados);
            $io = array(
                'id' => $id,
                'title' => $titulo,
                'descricao' => $descricao,
                'ocorrencia' => $data_hora_ocorrencia,
                'imagem' => $img,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'timer' => $timer,
                'icone' => $tipo,
                'lat' => $lat,
                'long' => $long,
                'prioridade' => $extraordinario
            );
            
            $elephant = new Elephant(new Version1X('https://euavisei.adaptable.app'));
            $elephant->initialize();
            $elephant->emit('editar-ocorrencia', $io);
            $elephant->close();
            
            if($ocorrencia){
                return \Redirect::route('listar-ocorrencia');
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    function returnLocal($b, $c, $uf){
        $l = $b . ", " . $c . " - " . $uf;
        return $l;
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
    
    function criarOcorrenciaV2(Request $request){
        $titulo = $request->get('titulo');
        $descricao = $request->get('descricao');
        $logradouro = $request->get('logradouro');
        $data_hora_ocorrencia = $request->get('data-hora-ocorrencia');
        $bairro = $request->get('bairro');
        $cidade = $request->get('cidade');
        $estado = $request->get('estado');
        $numero = $request->get('numero');
        $img = $request->get('imagem_buscada');
        $file = $request->get('imagem');

        $end_completo = $logradouro . " " . $numero . " " .$bairro . " " . $cidade . " " . $estado;

        $api = explode('||', $this->get_lat_long($end_completo));
        $lat = $api[0];
        $long = $api[1];
        $tipo = $request->get('tipo-ocorrencia');
        $categoria_tipo_ocorrencia = $request->get('categoria_tipo_ocorrencia');
        $timer = $request->get('timer');

        $extraordinario = $request->get('extraordinario');
        
        $dados = array(
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_hora_ocorrencia' => $data_hora_ocorrencia,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_ocorrencia' => $tipo,
            'tempo_duracao' => $timer,
            'extraordinario' => $extraordinario,
            'categoria_tipo_ocorrencia' => $categoria_tipo_ocorrencia
        );
        if(!$img){
            if($file){
               $ext = '.jpeg'; 
                $replace = 'data:image/jpeg;base64,';
                if(str_contains($file, 'image/png')){
                    $ext = '.png'; 
                    $replace = 'data:image/png;base64,';
                }
                if(str_contains($file, 'image/jpg')){
                    $ext = '.jpg'; 
                    $replace = 'data:image/jpg;base64,';
                }
                $file = str_replace($replace, '', $file);
                $file = str_replace(' ', '+', $file);
                $file = base64_decode($file);
                $arq = $this->generateRandomString(10).$ext;
                file_put_contents(public_path('/imgs/ocorrencias/'.$arq), $file);
                $dados['imagem'] = $arq;
                $img = $arq;
            }
        }
        try {
            $ocorrencia = \DB::table('ocorrencias')->insertGetId($dados);
            $io = array(
                'id' => $ocorrencia,
                'titulo' => $titulo,
                'descricao' => $descricao,
                'ocorrencia' => $data_hora_ocorrencia,
                'imagem' => $img,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'timer' => $timer,
                'icone' => $tipo,
                'latitude' => $lat,
                'longitude' => $long,
                'extraordinario' => $extraordinario,
                'tipo' => $tipo,
                'localidade' => $end_completo,
                'tipo_ocorrencia' => $tipo,
                'categoria_tipo_ocorrencia' => $categoria_tipo_ocorrencia
            );
            
            return $io;
        } catch (Exception $e) {
            return $e;
        }
    }
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    function buscarCategoriasTipoOcorrencias(Request $request){
        $tipo_ocorrencia = $request->get('tipo_ocorrencia');
        try{
            $categorias = \DB::table('categoria_tipo_ocorrencia')->where('tipo_ocorrencia', $tipo_ocorrencia)->orderBy('categoria', 'asc')->get();
            return $categorias;
        }catch(Exception $e){
            return $e;
        }
    }
}
