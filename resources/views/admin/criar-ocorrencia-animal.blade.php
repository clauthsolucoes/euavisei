@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/ocorrencia.css')}}">
@endsection
@section('content')
    <div class="container">
        <form method="post" enctype='multipart/form-data' action="{{url('/animais/cadastrar')}}">
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
                                <label for="titulo">Nome do Animal</label>
                                <input type="text" class="form-control" name="nome_animal" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="descricao">Nome de quem Procura</label>
                                <input class="form-control" name="nome_dono" required >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="titulo">Número para contato</label>
                                <input type="tel" class="form-control" name="contato" required>
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
                                <input type="file" id="file-img" class="form-control" name="imagem">
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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Tipo de Animal</label>
                        <select class="form-control" required name='tipo-animal'>
                            @foreach($tipo_animal as $arr)
                                <option value="{{$arr->id}}">{{$arr->tipo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Data e Hora da Ocorrência</label>
                        <input type="datetime-local" class="form-control" required name='data-hora-ocorrencia'>
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
        </div>
            <div class="row dv-btn">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Registrar" id="btn-registrar-animal">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/ocorrencia.js')}}"></script>
@endsection