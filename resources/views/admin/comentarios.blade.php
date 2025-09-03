@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/comentarios.css')}}">
@endsection
@section('content')
    <div class="content">
        <table class="table tbl-ocorrencia">
            <thead>
                <tr>
                    <th>Comentário</th>
                    <th class="t-center">Data e hora cadastrado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comentarios as $item)
                <tr>
                    <td>{{$item->comentarios}}</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->data_hora_registro))}}</td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endsection