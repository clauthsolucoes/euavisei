@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}">
@endsection
@section('content')
    <div class="content">
        <table class="table tbl-ocorrencia">
            <thead>
                <tr>
                    <th>Telefone</th>
                    <th>Apelido</th>
                    <th class="t-center">Data e hora cadastrado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $item)
                <tr>
                    <td class="tel">{{$item->telefone}}</td>
                    <td>{{$item->apelido}}</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->data_hora_registro))}}</td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endsection