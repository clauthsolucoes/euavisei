@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/ocorrencia.css')}}">
@endsection
@section('content')
    <div class="container">
        <form method="post" enctype='multipart/form-data' action="{{url('/ocorrencias/editar')}}">
            <input type="hidden" name="id" value="{{$ocorrencia->id}}">
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
                                <input type="text" class="form-control" name="titulo" value="{{$ocorrencia->titulo}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" name="descricao" required rows="5">{{$ocorrencia->descricao}}</textarea>
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
                                    <img id="img-tag" src="{{asset('imgs/ocorrencias/'. $ocorrencia->imagem)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Tipo de Ocorrência</label>
                        <select class="form-control" required name='tipo-ocorrencia'>
                            @foreach($tipo_ocorrencia as $arr)
                                <option value="{{$arr->id}}" @if($ocorrencia->tipo_ocorrencia == $arr->id) selected @endif>{{$arr->tipo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Data e Hora da Ocorrência</label>
                        <input type="datetime-local" class="form-control" required name='data-hora-ocorrencia' value="{{date('Y-m-d', strtotime($ocorrencia->data_hora_ocorrencia))}}T{{date('H:i:s', strtotime($ocorrencia->data_hora_ocorrencia))}}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="hora-ocorrencia">Tempo de duração</label>
                        <input type="datetime-local" class="form-control" required name='timer' value="{{date('Y-m-d', strtotime($ocorrencia->tempo_duracao))}}T{{date('H:i:s', strtotime($ocorrencia->tempo_duracao))}}">
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
                        <input type="text" class="form-control" name="logradouro" value="{{$ocorrencia->logradouro}}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" class="form-control" required name="bairro" value="{{$ocorrencia->bairro}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="bairro">Cidade</label>
                        <input type="text" class="form-control" required name="cidade" value="{{$ocorrencia->cidade}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" required name="estado" maxlength="2" value="{{$ocorrencia->estado}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" name="numero" value="{{$ocorrencia->numero}}">
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
                        <input class="form-check-input" type="radio" value="0" name="extraoridnario" @if($ocorrencia->extraordinario == 0) checked @endif required>
                        <label class="form-check-label">Não</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="1" name="extraoridnario" @if($ocorrencia->extraordinario == 1) checked @endif >
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
    <script type="text/javascript" src="{{asset('assets/js/ocorrencia.js')}}"></script>
@endsection