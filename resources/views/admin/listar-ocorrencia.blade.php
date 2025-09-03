@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/ocorrencia.css')}}">
@endsection
@section('content')
    <div class="container">
        <table class="table tbl-ocorrencia">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th class="t-center">Data</th>
                    <th class="t-center">Tipo</th>
                    <th class="t-center">Extra</th>
                    <th class="t-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ocorrencias as $item)
                <tr onclick="pageEditar('{{url('/ocorrencias/editar/'). '/' .$item->id}}')">
                    <td>{{$item->titulo}}</td>
                    <td>@if(strlen($item->descricao) > 25) {{substr($item->descricao, 0, 25) . "..."}} @else {{$item->descricao}} @endif</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->data))}}</td>
                    <td class="t-center">{{$item->tipo}}</td>
                    <td class="t-center">@if($item->extraordinario) Sim @else Não @endif</td>
                    <td class="t-center">@if(new DateTime($item->duracao) > new DateTime()) Ativo @else Inativo @endif</td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/ocorrencia.js')}}"></script>
@endsection