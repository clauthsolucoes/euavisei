@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/eventos.css')}}">
@endsection
@section('content')
    <div class="container">
        <table class="table tbl-ocorrencia">
            <thead>
                <tr>
                    <th>Título</th>
                    <th class="t-center">Tipo</th>
                    <th class="t-center">Data início</th>
                    <th class="t-center">Data fim</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventos as $item)
                <tr onclick="pageEditar('{{url('/eventos/editar/'). '/' .$item->id}}')">
                    <td>{{$item->titulo}}</td>
                    <td class="t-center">{{$item->tipo}}</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->inicio))}}</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->fim))}}</td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/eventos.js')}}"></script>
@endsection