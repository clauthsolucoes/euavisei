<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'RootController@loginPage')->name('login');
Route::post('/login', 'RootController@efetuarLogin');
Route::get('/dashboard', 'RootController@dashboardPage')->name('dashboard');
Route::get('/settings', 'RootController@settingsPage')->name('settings');
Route::post('/alterarSenha', 'RootController@alterarSenha');
Route::post('/logout', 'RootController@logout');

Route::get('/ocorrencias/listar', 'OcorrenciaController@listarOcorrencias')->name('listar-ocorrencia');
Route::get('/ocorrencias/criar', 'OcorrenciaController@registrarOcorrencia')->name('ocorrencias');
Route::get('/ocorrencias/editar/{id}', 'OcorrenciaController@alterarOcorrencia')->name('alterar-ocorrencia');
Route::post('/ocorrencias/cadastrar', 'OcorrenciaController@cadastrarOcorrencia');
Route::post('/ocorrencias/editar', 'OcorrenciaController@editarOcorrencia');
Route::post('/ocorrencias/subcategorias/listar', 'OcorrenciaController@buscarCategoriasTipoOcorrencias');

Route::post('/ocorrencias/socket/cadastrar', 'OcorrenciaController@criarOcorrenciaV2');

Route::get('/animais/listar', 'AnimalController@listarOcorrencias')->name('listar-ocorrencia-animal');
Route::get('/animais/criar', 'AnimalController@registrarAnimalPage')->name('ocorrencia_animal');
Route::get('/animais/comentarios', 'AnimalController@comentariosPage')->name('comentarios_animal');
Route::post('/animais/cadastrar', 'AnimalController@registrarOcorrenciaAnimal');

Route::get('/usuarios', 'UsuarioController@usurariosPage')->name('listar-usuarios');
Route::get('/comentarios', 'UsuarioController@comentariosPage')->name('listar-comentarios');

Route::get('/notificacoes', 'NotificacoesController@indexPage')->name('notifications');
Route::post('/notificar', 'NotificacoesController@enviarNotification');

Route::get('/eventos/listar', 'EventoController@listarEventos')->name('eventos');
Route::get('/eventos/criar', 'EventoController@cadastrarPageEvento')->name('eventos-cadastrar');
Route::get('/eventos/editar/{id}', 'EventoController@alterarPageEvento')->name('eventos-alterar');
Route::post('/eventos/cadastrar', 'EventoController@cadastrarEvento');
Route::post('/eventos/editar', 'EventoController@alterarEvento');
Route::post('/eventos/deletar', 'EventoController@deletarEvento');