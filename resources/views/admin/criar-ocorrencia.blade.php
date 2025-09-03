@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/ocorrencia.css')}}">
@endsection
@section('content')
    <div class="container">
        <form method="post" enctype='multipart/form-data' action="{{url('/ocorrencias/cadastrar')}}">
            @csrf
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="imagem">Buscar uma Imagem</label>
                                <input type="text" id="buscar-file-img" class="form-control" name="imagem_buscada">
                                <input type="hidden" name="imagem" id="img-buscada"> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" class="form-control" name="titulo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" name="descricao" required rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="imagem">Adicionar uma Imagem</label>
                                <button type="button" class="btn btn-warning" id="btn-add-img">Adicionar</button>
                                <input type="file" id="file-img" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div id="img-ocorrencia">
                                    <img id="img-tag">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Tipo de Ocorrência</label>
                        <select class="form-control" required name='tipo-ocorrencia'>
                            @foreach($tipo_ocorrencia as $arr)
                                <option value="{{$arr->id}}">{{$arr->tipo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Subcategoria da Ocorrência</label>
                        <select class="form-control" required name='categoria_tipo-ocorrencia' disabled>
                            
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Data e Hora da Ocorrência</label>
                        <input type="datetime-local" class="form-control" required name='data-hora-ocorrencia'>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Tempo de duração</label>
                        <input type="datetime-local" class="form-control" required name='timer'>
                    </div>
                </div>
            </div>
            <hr>
            <label class="end">Local da Ocorrência</label>
            <hr>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" class="form-control" name="logradouro">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" class="form-control" required name="bairro">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="bairro">Cidade</label>
                        <input type="text" class="form-control" required name="cidade">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" required name="estado" maxlength="2">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" name="numero">
                    </div>
                </div>
            </div>
            <hr>
            <label class="end">Prioridade da Ocorrência</label>
            <hr>
            <div class="row">
                <div class="col-sm-5">
                    <label>A ocorrência é extraordinária?</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="0" name="extraordinario" checked required>
                        <label class="form-check-label">Não</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="1" name="extraordinario">
                        <label class="form-check-label">Sim</label>
                    </div>
                </div>
            </div>
            <div class="row dv-btn">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Registrar" id="btn-registrar">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.6.1/socket.io.js" integrity="sha512-xbQU0+iHqhVt7VIXi6vBJKPh3IQBF5B84sSHdjKiSccyX/1ZI7Vnkt2/8y8uruj63/DVmCxfUNohPNruthTEQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="{{asset('assets/js/socket.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/ocorrencia.js')}}"></script>
@endsection