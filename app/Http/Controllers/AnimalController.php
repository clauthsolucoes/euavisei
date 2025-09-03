<?php

namespace App\Http\Controllers;

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version2X;

use App\Models\OneSignal;

use Illuminate\Http\Request;

class AnimalController extends Controller{
	
    public function listarOcorrencias(Request $request){
        if(!$request->session()->has('id')){
            return \Redirect::route('login');
        } 
        try{
            $ocorrencias = \DB::table('animal_ocorrencia as o')
            ->join('tipo_animal as t', 't.id', '=', 'o.tipo_animal')
            ->select(
                'o.id as id',
                'o.nome_animal as animal',
                'o.nome_dono as dono',
                'o.data_hora_ocorrencia as data',
                't.tipo as tipo',
                'o.contato as contato',
                'o.status as status'
            )
            ->orderBy('o.data_hora_ocorrencia', 'desc')
            ->get();
            return view('admin/listar-ocorrencia-animal', ['ocorrencias' => $ocorrencias]);
        }catch(Exception $e){
            return $e;
        }
    }

	public function registrarAnimalPage(Request $request){
		if(!$request->session()->has('id')){
            		return \Redirect::route('login');
        	} 
        	try{
            		$tipo_animal = \DB::table('tipo_animal')->select('id as id', 'tipo as tipo')->get();
            		return view('admin/criar-ocorrencia-animal', ['tipo_animal' => $tipo_animal]);
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
            $tipos_ocorrencia = \DB::table('tipo_animal')->select('id as id', 'tipo as tipo')->get();
            $ocorrencia = \DB::table('animal_ocorrencia')->where('id', $id)->first();
            return view('admin/editar-ocorrencia', ['ocorrencia' => $ocorrencia, 'tipo_animal' => $tipos_ocorrencia]);
        }catch(Exception $e){
            return $e;
        }
    }
	
	public function registrarOcorrenciaAnimal(Request $request){
	    $nome_animal = $request->get('nome_animal');
        $nome_dono = $request->get('nome_dono');
        $contato = $request->get('contato');
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
        $tipo = $request->get('tipo-animal');

        $dados = array(
            'nome_animal' => $nome_animal,
            'nome_dono' => $nome_dono,
            'contato' => $contato,
            'data_hora_ocorrencia' => $data_hora_ocorrencia,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_animal' => $tipo
        );
        if(!$img){
            if($request->file('imagem') !== null){
                $arr = $request->file('imagem');
                $md5Name = md5_file($arr->getRealPath());
                $guessExtension = $arr->guessExtension();
                $file = $md5Name.'.'.$guessExtension;
                $arr->move(public_path('/imgs/animais'), $file);
                $dados['foto'] = $file;
                $img = $file;
            }
        }
        
        if($tipo == 1){
            $km = 3;
        }else if($tipo == 2){
            $km = 4;
        }else if($tipo == 3){
            $km = 5;
        }
        
        try{
            $query = "(select calcularDistancia(substring(ponto_notificacao, 8, position(',&quot;long&quot;:' in ponto_notificacao) - 8), substring(ponto_notificacao, position(',&quot;long&quot;:' in ponto_notificacao) + 8, char_length(substring(ponto_notificacao, position(',&quot;long&quot;:' in ponto_notificacao) + 8, char_length(ponto_notificacao))) - 1), $lat, $long))";
            $query = str_replace("&quot;", '"', $query);
            $users_ids = \DB::table('usuarios')
                ->select('one_signal_id')
                ->where('ponto_notificacao', '!=', '')
                ->whereNotNull('ponto_notificacao')
                ->whereNotNull('one_signal_id')
                ->where(\DB::raw($query), '<=', $km)
                ->get();
            $ids = array();
            foreach($users_ids as $item){
                array_push($ids, $item->one_signal_id);
            } 
            $ocorrencia = \DB::table('animal_ocorrencia')->insertGetId($dados);
            $os = new OneSignal();
            $os->setUsers($ids);
            //$os->setContent();
            //$os->setHeadings();
            $os->setPost();
            $res = $os->callApi();
            if($ocorrencia){
                return \Redirect::route('listar-ocorrencia-animal');
            }
        }catch(Exception $e){
            return $e;
        }
	}

    public function editarOcorrenciaAnimal(Request $request){
        $id = $request->get('id');
	    $nome_animal = $request->get('nome_animal');
        $nome_dono = $request->get('nome_dono');
        $contato = $request->get('contato');
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
        $tipo = $request->get('tipo-animal');

        $dados = array(
            'nome_animal' => $nome_animal,
            'nome_dono' => $nome_dono,
            'contato' => $contato,
            'data_hora_ocorrencia' => $data_hora_ocorrencia,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'numero' => $numero,
            'latitude' => $lat,
            'longitude' => $long,
            'tipo_animal' => $tipo,
            'foto' => $img
        );
        if(!$img){
            if($request->file('imagem') !== null){
                $arr = $request->file('imagem');
                $md5Name = md5_file($arr->getRealPath());
                $guessExtension = $arr->guessExtension();
                $file = $md5Name.'.'.$guessExtension;
                $arr->move(public_path('/imgs/animais'), $file);
                $dados['foto'] = $file;
                $img = $file;
            }
        }
        
        if($tipo == 1){
            $km = 3;
        }else if($tipo == 2){
            $km = 4;
        }else if($tipo == 3){
            $km = 5;
        }else if($tipo == 4){
            $km = 5;
        }
        
        try{
            $ocorrencia = \DB::table('animal_ocorrencia')->where('id', $id)->update($dados);
            if($ocorrencia){
                return \Redirect::route('ocorrencia');
            }
        }catch(Exception $e){
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

    public function comentariosPage(Request $request){
        if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
        try{
            $comentarios = \DB::table('comentarios_animal')->get();
            return view('admin/comentarios_animal', ['comentarios' => $comentarios]);
        }catch(Exception $e){
            return $e;
        }
    }
	
	
}