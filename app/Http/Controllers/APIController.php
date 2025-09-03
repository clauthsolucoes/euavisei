<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OpenWeather;

class APIController extends Controller
{
    public function getOcorrencias(Request $request){
        $usuario_id = $request->get('usuario_id');
        try{
            $ocorrencias = \DB::table('ocorrencias as o')->select('o.logradouro as logradouro', 'o.bairro as bairro', 'o.cidade as cidade', 'o.estado as estado', 'o.numero as numero' ,'o.data_hora_ocorrencia as data_hora_ocorrencia', 'o.descricao as descricao', 'o.extraordinario as extraordinario', 'o.id as id', 'o.imagem as imagem', 'o.latitude as latitude', 'o.longitude as longitude', 'o.tempo_duracao as tempo_duracao', 'o.tipo_ocorrencia as tipo_ocorrencia', 'o.titulo as titulo', \DB::raw('(select count(*) from comentarios where ocorrencia_id = o.id) as comentarios'), \DB::raw('(select count(*) from likes_ocorrencia where ocorrencia_id = o.id and like_num = 1) as likes'), \DB::raw('(select like_num from likes_ocorrencia where ocorrencia_id = o.id and usuario_id = '.$usuario_id.') as meu_like'))->where('tempo_duracao', '>', date('Y-m-d H:i:s'))->get();
            return $ocorrencias;
        }catch(Exception $e){
            return $e;
        }
    }

    public function getAnimaisPerdidos(Request $request){
        $usuario_id = $request->get('usuario_id');
        try{
            $ocorrencias = \DB::table('animal_ocorrencia as o')->select('o.logradouro as logradouro', 'o.bairro as bairro', 'o.cidade as cidade', 'o.estado as estado', 'o.numero as numero' ,'o.data_hora_ocorrencia as data_hora_ocorrencia', 'o.nome_animal as animal', 'o.nome_dono as dono', 'o.id as id', 'o.foto as imagem', 'o.latitude as latitude', 'o.longitude as longitude', 'o.contato as contato', 'o.tipo_animal as tipo_animal', 'o.status as status', 'o.status_relato as relato', \DB::raw('(select count(*) from comentarios_animal where ocorrencia_id = o.id) as comentarios'), \DB::raw('(select count(*) from likes_animal_ocorrencia where animal_ocorrencia_id = o.id) as likes'), \DB::raw('(select like_num from likes_animal_ocorrencia where animal_ocorrencia_id = o.id and usuario_id = '.$usuario_id.') as meu_like'))->where('o.status', 0)->where(\DB::raw("datediff(curdate(), o.data_hora_ocorrencia)"), "<=", 30)->get();
            return $ocorrencias;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function getEventos(Request $request){
        $usuario_id = $request->get('usuario_id');
        try{
            $eventos = \DB::table('eventos as e')->select('e.logradouro as logradouro', 'e.bairro as bairro', 'e.cidade as cidade', 'e.estado as estado', 'e.numero as numero','e.data_hora_inicio as inicio','e.data_hora_fim as fim' , 'e.descricao as descricao', 'e.extraordinario as extraordinario', 'e.id as id', 'e.imagem as imagem', 'e.latitude as latitude', 'e.longitude as longitude',  'e.tipo_evento as tipo_evento', 'e.titulo as titulo', \DB::raw('(select count(*) from comentarios_evento where evento_id = e.id) as comentarios'), \DB::raw('(select count(*) from likes_evento where evento_id = e.id) as likes'), \DB::raw('(select like_num from likes_evento where evento_id = e.id and usuario_id = '.$usuario_id.') as meu_like'))->where('data_hora_fim', '>', date('Y-m-d H:i:s'))->get();
            return $eventos;
        }catch(Exception $e){
            return $e;
        }
    }

    public function cadastrarUsuario(Request $request){
        $telefone = $request->get('telefone');
        try{
            $usuario = \DB::table('usuarios')->where('telefone', $telefone)->get();
            if(count($usuario) > 0){
                return $usuario;
            }
            $cadastro = \DB::table('usuarios')->insert(
                array(
                    'telefone' => $telefone
                )
            );
            $usuario = \DB::table('usuarios')->where('telefone', $telefone)->get();
            return $usuario;
        }catch(Exception $e){
            return $e;
        }
    }
    
    function get_lat_long($address){
        $address = str_replace(" ", "+", $address);
        $key='AIzaSyB4tTzmUhhxt-p7s91Y6gLFMYrbiYvilt8';
        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=$key");
        $json = json_decode($json);
        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        $position = array(
            'lat' => $lat,
            'long' => $long
        );
        return $position;
    }
    
    function returnLatLong(Request $request){
        $address = $request->get('endereco');
        $id = $request->get('id');
        try{
            $latLong = '';
            $ponto = '';
            if($address){
                $latLong = $this->get_lat_long($address);
                $ponto = json_encode($latLong);
            }
            \DB::table('usuarios')->where('id', $id)->update(array(
                'ponto_notificacao' => $ponto    
            ));
            return $latLong;
        }catch(Exception $e){
            return $e;
        }
    }
    
    function getLocalidade(Request $request){
        try{
            $localidades = \DB::table('bairros as b')->join('cidades as c', 'b.cidade_id', '=', 'c.id')->join('estados as e', 'e.id', '=', 'c.uf_id')->select('b.bairro as bairro', 'c.cidade as cidade', 'e.uf as uf')->get();
            return $localidades;
        }catch(Exception $e){
            return $e;
        }
    }
    
    function getWeather(Request $request){
        $lat = $request->get('lat');
        $long = $request->get('long');
        try{
            $open = new OpenWeather();
            $open->setLatitude($lat);
            $open->setLongitude($long);
            $api = $open->callApi();
            return $api;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function like(Request $request){
        $ocorrencia_id = $request->get('ocorrencia_id');
        $usuario_id = $request->get('user_id');
        try{
            $like = \DB::table('likes_ocorrencia')->where('ocorrencia_id', $ocorrencia_id)->where('usuario_id', $usuario_id)->first();
            if(!$like){
                $l = \DB::table('likes_ocorrencia')->insert(
                    array(
                        'usuario_id' => $usuario_id,
                        'ocorrencia_id' => $ocorrencia_id,
                        'like_num' => 1
                    )
                );
                return $l;
            }
            $l = \DB::table('likes_ocorrencia')->where('ocorrencia_id', $ocorrencia_id)->where('usuario_id', $usuario_id)->update(
                array(
                    'like_num' => ($like->like_num == 0)? 1 : 0
                )
            );
            return $l;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentarios(Request $request){
        $ocorrencia_id = $request->get('ocorrencia_id');
        try{
            $comentarios = \DB::table('comentarios')->where('ocorrencia_id', $ocorrencia_id)->orderBy('data_hora_registro', 'desc')->get();
            return $comentarios;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentar(Request $request){
        $usuario_id = $request->get('usuario_id');
        $ocorrencia_id = $request->get('ocorrencia_id');
        $comentario = $request->get('comentario');
        $vals = array(
            'usuario_id' => $usuario_id,
            'ocorrencia_id' => $ocorrencia_id,
            'comentarios' => $comentario
        );
        try{
            \DB::table('comentarios')->insert($vals);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function like_animal(Request $request){
        $ocorrencia_id = $request->get('ocorrencia_id');
        $usuario_id = $request->get('user_id');
        try{
            $like = \DB::table('likes_animal_ocorrencia')->where('animal_ocorrencia_id', $ocorrencia_id)->where('usuario_id', $usuario_id)->first();
            if($like){
                $v = \DB::table('likes_animal_ocorrencia')->where('animal_ocorrencia_id', $ocorrencia_id)->where('usuario_id', $usuario_id)->update(
                    array(
                        'like_num' => ($like->like_num == 0)? 1 : 0
                    )
                );
            }else{
                $v = \DB::table('likes_animal_ocorrencia')->insert(
                    array(
                        'usuario_id' => $usuario_id,
                        'animal_ocorrencia_id' => $ocorrencia_id,
                        'like_num' => 1
                    )
                );
            }
            return array('status' => $v);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentarios_animal(Request $request){
        $ocorrencia_id = $request->get('ocorrencia_id');
        try{
            $comentarios = \DB::table('comentarios_animal')->where('ocorrencia_id', $ocorrencia_id)->orderBy('data_hora_registro', 'desc')->get();
            return $comentarios;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentar_animal(Request $request){
        $usuario_id = $request->get('usuario_id');
        $ocorrencia_id = $request->get('ocorrencia_id');
        $comentario = $request->get('comentario');
        $vals = array(
            'usuario_id' => $usuario_id,
            'ocorrencia_id' => $ocorrencia_id,
            'comentarios' => $comentario
        );
        try{
            \DB::table('comentarios_animal')->insert($vals);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function likeEvento(Request $request){
        $evento_id = $request->get('evento_id');
        $usuario_id = $request->get('user_id');
        try{
            $like = \DB::table('likes_evento')->where('evento_id', $evento_id)->where('usuario_id', $usuario_id)->value('like_num');
            if(isset($like)){
                $update = \DB::table('likes_evento')->where('evento_id', $evento_id)->where('usuario_id', $usuario_id)->update(
                    array(
                        'like_num' => ($like == 0)? 1 : 0
                    )
                );
                return $update;
            }else{
                $insert = \DB::table('likes_evento')->insert(
                    array(
                        'usuario_id' => $usuario_id,
                        'evento_id' => $evento_id,
                        'like_num' => 1
                    )
                );
                return $insert;
            }
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentariosEvento(Request $request){
        $evento_id = $request->get('evento_id');
        try{
            $comentarios = \DB::table('comentarios_evento')->where('evento_id', $evento_id)->orderBy('data_hora_registro', 'desc')->get();
            return $comentarios;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function comentarEvento(Request $request){
        $usuario_id = $request->get('usuario_id');
        $evento_id = $request->get('evento_id');
        $comentario = $request->get('comentario');
        $vals = array(
            'usuario_id' => $usuario_id,
            'evento_id' => $evento_id,
            'comentarios' => $comentario
        );
        try{
            \DB::table('comentarios_evento')->insert($vals);
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function editarComentarioEvento(Request $request){
        $id = $request->get('id');
        $comentario = $request->get('comentario');
        try{
            \DB::table('comentarios_evento')->where('id', $id)->update(
                array(
                    'comentarios' => $comentario
                )  
            );
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function apagarComentarioEvento(Request $request){
        $id = $request->get('id');
        try{
            \DB::table('comentarios_evento')->where('id', $id)->delete();
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function updateNotifications(Request $request){
        $usuario_id = $request->get('usuario_id');
        $notification = $request->get('notifications');
        try{
            \DB::table('usuarios')->where('id', $usuario_id)->update(
                array(
                    'notifications' => $notification 
                )
            );
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function editarComentario(Request $request){
        $id = $request->get('comentario_id');
        $comentario = $request->get('comentario');
        try{
            $up = \DB::table('comentarios')->where('id', $id)->update(
                array(
                    'comentarios' => $comentario
                )  
            );
            return $up;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function apagarComentario(Request $request){
        $id = $request->get('id');
        try{
            \DB::table('comentarios')->where('id', $id)->delete();
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function editarComentarioAnimal(Request $request){
        $id = $request->get('id');
        $comentario = $request->get('comentario');
        try{
            \DB::table('comentarios_animal')->where('id', $id)->update(
                array(
                    'comentario' => $comentario
                )  
            );
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function apagarComentarioAnimal(Request $request){
        $id = $request->get('id');
        try{
            \DB::table('comentarios_animal')->where('id', $id)->delete();
        }catch(Exception $e){
            return $e;
        }
    }
    
    public function getPosts(Request $request){
        $response = Http::get('https://saogoncaloinforma.com.br/wp-json/wp/v2/posts?_embed&per_page=20');
        $wordpress = $response->json();
        return $wordpress;
    }
    
    public function atualizaOneSignal(Request $request){
        $id = $request->get('id');
        $onesignal = $request->get('oneSignal');
        try{
            \DB::table('usuarios')->where('id', $id)->update(
                array(
                    'one_signal_id' => $onesignal
                )
            );
        }catch(Exception $e){
            return $e;
        }
    }

    public function attImg(Request $request){
        $pasta =  strval(date('d_m_Y')). '/';
        $file = $request->get('file');
        $fileUpload = false;
        if(!$file){
            $file = $request->file('file');
            $fileUpload = true;
        }
        try{
            if(!$fileUpload){
                $file = str_replace('data:image/jpeg;base64,', '', $file);
                $file = str_replace(' ', '+', $file);
                $file = base64_decode($file);
            }
            $f = \Storage::disk('public_uploads')->put($pasta, $file);
            $ex = explode("/", $f);
            $arq = $ex[count($ex) - 1];
            return array('img' => $pasta . $arq);
        }catch(Exception $e){
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
    
    function getLocationByCoordinates(Request $request){
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        try{
            $key='AIzaSyB4tTzmUhhxt-p7s91Y6gLFMYrbiYvilt8';
            $endpoint = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false&key=$key";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            return $response;
            $result = json_decode($response);
            curl_close($ch);
            return $result;
        }catch(Exception $e){
            return $e;
        }
    }
    
    function relatarAnimal(Request $request){
        $ocorrencia_id = $request->get('ocorrencia_id');
        $usuario_id = $request->get('usuario_id');
        $endereco = $request->get('endereco');
        $data_hora = $request->get('data_hora');
        $img = $request->get('imagem');
        try{
            $insert = array(
                'ocorrencia_id' => $ocorrencia_id,
                'usuario_id' => $usuario_id,
                'data_hora_encontrado' => $data_hora,
                'local_encontro' => $endereco,
                'img' => $img
            );
            $db = \DB::table('relato_animal')->insert($insert);
            return $db;
        }catch(Exception $e){
            return $e;
        }
    }
    
    function enviarOcorrencia(Request $request){
        $tipo_ocorrencia = $request->get('tipo_ocorrencia');
        $tipo = $request->get('tipo');
        $titulo = $request->get('titulo');
        $desc = $request->get('desc');
        $pet = $request->get('pet');
        $dono = $request->get('dono');
        $contato = $request->get('contato');
        $logradouro = $request->get('logradouro');
        $bairro = $request->get('bairro');
        $cidade = $request->get('cidade');
        $estado = $request->get('estado');
        $numero = $request->get('numero');
        $cep = $request->get('cep');
        $base64Image = $request->get('base64');
        $formato = $request->get('formato');
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        $usuario = $request->get('usuario');
        $usuario_telefone = $request->get('usuario_telefone');
        $tipo_urbano = $request->get('tipo_urbano');
        try{
            $i = array(
                'tipo' => $tipo,
                'tipo_ocorrencia' => $tipo_ocorrencia,
                'titulo' => $titulo,
                'descricao' => $desc,
                'pet' => $pet,
                'dono' => $dono,
                'contato' => $contato,
                'logradouro' => $logradouro,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'numero' => $numero,
                'cep' => $cep,
                'imagem' => $this->imgToPublic($base64Image, $formato),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'usuario_id' => $usuario,
                'usuario_telefone' => $usuario_telefone,
                'tipo_urbano' => $tipo_urbano
            );
            $insert = \DB::table('ocorrencias_recebidas')->insert($i);
            return array('status' => 200, 'insert' => $insert);
        }catch(Exception $e){
            return $e;
        }
    }
    
    private function imgToPublic($file, $ext){
        $replace = 'data:image/jpeg;base64,';
        if(str_contains($file, 'image/png')){
            $ext = 'png'; 
            $replace = 'data:image/png;base64,';
        }
        if(str_contains($file, 'image/jpg')){
            $ext = 'jpg'; 
            $replace = 'data:image/jpg;base64,';
        }
        $file = str_replace($replace, '', $file);
        $file = str_replace(' ', '+', $file);
        $file = base64_decode($file);
        $arq = $this->generateRandomString(10).".".$ext;
        $path = public_path('/imgs/recebidas/') . $arq;
        file_put_contents($path, $file);
        return $arq;
    }
}
