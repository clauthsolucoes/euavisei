@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/notificacoes.css')}}">
@endsection
@section('content')
    <div class="content">
        <p class="p-not">Envio de notificações</p>
        <form action="{{url('/notificar')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Tipo de ocorrência</label>
                        <select name="ocorrencia" class="form-control" required>
                            @foreach($ocorrencias as $item)
                            <option value="{{$item->id}}">{{$item->tipo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea name="desc" cols="30" rows="10" class="form-control" required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ocorrência</th>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Data e Hora de Envio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $item)
                        <tr>
                            <td>{{$item->ocorrencia}}</td>
                            <td>{{$item->titulo}}</td>
                            <td>{{$item->descricao}}</td>
                            <td>{{$item->data_hora_envio}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="form-group">
                    <input type="submit" value="Enviar" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
@endsection