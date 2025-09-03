<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RootController extends Controller
{
    public function loginPage(Request $request){
    	if($request->session()->has('id')){
    		return \Redirect::route('dashboard');
    	}
    	return view('login', ['status' => 0]);
    }
    
    public function efetuarLogin(Request $request){
    	$usuario = $request->get('user');
    	$senha = $request->get('senha');
    	try{
    		$user = \DB::table('__data_access')->where('login', $usuario)->first();
    		if(!$user){
    			return \Redirect::route('login', ['status' => 2]);
    		}
    		if(!password_verify($senha,  substr( $user->senha, 0, 60 ))){
    			return \Redirect::route('login', ['status' => 1]);
    		}
    		$request->session()->put('id', $user->id);
    		$request->session()->put('usuario', $user->nome_usuario);
    		$request->session()->put('nivel', $user->nivel);
    		return \Redirect::route('dashboard');
    	}catch(Exception $e){
    		return $e;
    	}
    }
    
    public function recuperarSenhaPage(Request $request){
    	if($request->session()->has('id')){
    		return \Redirect::route('dashboard');
    	}
    	return view('restore');
    }
    
    public function recuperarSenha(){
    	$email = $request->get('email');
    	try{
    		
    	}catch(Exception $e){
    		return $e;
    	}
    }
    
    public function dashboardPage(Request $request){
    	if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
		try{
			$counter = \DB::table('tipo_ocorrencia as t')->select('t.tipo as ocorrencia', \DB::raw('(select count(*) from ocorrencias as o where o.tipo_ocorrencia = t.id and o.tempo_duracao > now()) as quant'))->groupBy('t.id')->get();
			$geral = \DB::table('tipo_ocorrencia as t')->select('t.tipo as ocorrencia', \DB::raw('(select count(*) from ocorrencias as o where o.tipo_ocorrencia = t.id) as quant'))->groupBy('t.id')->get();
			$total = \DB::table('ocorrencias')->count();
			$tempo = \DB::table('ocorrencias')->select(\DB::raw('CONCAT(DAY(data_hora_ocorrencia), "/", MONTH(data_hora_ocorrencia)) as diames'), \DB::raw('COUNT(tipo_ocorrencia) AS quant'))->where('data_hora_ocorrencia', '>=', \DB::raw('NOW() - INTERVAL 10 DAY'))->groupBy(\DB::raw('DATE(data_hora_ocorrencia)'))->orderBy('data_hora_ocorrencia', 'ASC')->get();
			return view('admin/dashboard', ['counter' => $counter, 'geral' => $geral, 'total' => $total, 'tempo' => $tempo]);
		}catch(Exception $e){
			return $e;
		}
    }
    
    public function logout(Request $request){
    	$request->session()->flush();
    	return \Redirect::route('login');
    }
    
    public function settingsPage(Request $request){
    	if(!$request->session()->has('id')){
    		return \Redirect::route('login');
    	}
    	return view('admin/settings');
    }
    
    public function alterarSenha(Request $request){
    	$id = $request->session()->get('id');
    	$senha= $request->get('nova_senha');
    	try{
    		$nova_senha = password_hash($senha , PASSWORD_BCRYPT);
    		\DB::table('__data_access')->where('id', $id)->update(
    			array(
    				'senha' => $nova_senha
    			)
    		);
    		return \Redirect::route('settings');
    	}catch(Exception $e){
    		return $e;
    	}
    }
}