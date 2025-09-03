<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->post('/ocorrencias', 'APIController@getOcorrencias');
Route::middleware('api')->post('/usuario/login', 'APIController@cadastrarUsuario');
Route::middleware('api')->post('/localidades', 'APIController@getLocalidade');
Route::middleware('api')->post('/radius', 'APIController@returnLatLong');
Route::middleware('api')->post('/weather', 'APIController@getWeather');
Route::middleware('api')->post('/like-ocorrencia', 'APIController@like');
Route::middleware('api')->post('/comentarios', 'APIController@comentarios');
Route::middleware('api')->post('/comentar', 'APIController@comentar');
Route::middleware('api')->post('/notifications', 'APIController@updateNotifications');
Route::middleware('api')->post('/editar-comentario', 'APIController@editarComentario');
Route::middleware('api')->post('/remover-comentario', 'APIController@apagarComentario');
Route::middleware('api')->post('/noticias', 'APIController@getPosts');
Route::middleware('api')->post('/onesignal', 'APIController@atualizaOneSignal');
Route::middleware('api')->post('/animal-ocorrencias', 'APIController@getAnimaisPerdidos');
Route::middleware('api')->post('/like-ocorrencia-animal', 'APIController@like_animal');
Route::middleware('api')->post('/comentarios-animal', 'APIController@comentarios_animal');
Route::middleware('api')->post('/comentar-animal', 'APIController@comentar_animal');
Route::middleware('api')->post('/editar-comentario-animal', 'APIController@editarComentarioAnimal');
Route::middleware('api')->post('/remover-comentario-animal', 'APIController@apagarComentarioAnimal');
Route::middleware('api')->post('/send-img-to-link', 'APIController@attImg');
Route::middleware('api')->post('/adicionar-apelido', 'UsuarioController@adicionarApelido');
Route::middleware('api')->post('/buscar-eventos', 'APIController@getEventos');
Route::middleware('api')->post('/like-evento', 'APIController@likeEvento');
Route::middleware('api')->post('/comentarios-evento', 'APIController@comentariosEvento');
Route::middleware('api')->post('/comentar-evento', 'APIController@comentarEvento');
Route::middleware('api')->post('/editar-comentario-evento', 'APIController@editarComentarioEvento');
Route::middleware('api')->post('/remover-comentario-evento', 'APIController@apagarComentarioEvento');
Route::middleware('api')->post('/buscar-local', 'APIController@getLocationByCoordinates');
Route::middleware('api')->post('/relatar-animal', 'APIController@relatarAnimal');
Route::middleware('api')->post('/enviar-ocorrencia', 'APIController@enviarOcorrencia');