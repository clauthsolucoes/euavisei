@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/ocorrencia.css')}}">
@endsection
@section('content')
    <div class="container">
        <table class="table tbl-ocorrencia">
            <thead>
                <tr>
                    <th class="t-center">Tipo</th>
                    <th>Responsável</th>
                    <th>Animal</th>
                    <th class="t-center">Contato</th>
                    <th class="t-center">Data</th>
                    <th class="t-center">Status</th>
                    <th class="t-center">Operação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ocorrencias as $item)
                <tr>
                    <td class="t-center">{{$item->tipo}}</td>
                    <td>{{$item->dono}}</td>
                    <td>{{$item->animal}}</td>
                    <td class="t-center">{{$item->contato}}</td>
                    <td class="t-center">{{date("d/m/Y", strtotime($item->data))}}</td>
                    <td class="t-center">@if($item->status == 0) Ativo @else Finalizado @endif</td>
                    <td class="op">
                        @if($item->status == 0)  
                            <button class="btn-finalizar" data-toggle="modal" data-target="#exampleModal">
                                <i class="fa fa-check"></i>
                            </button>
                        @else 
                            <i class="fa fa-check ended"></i>
                        @endif
                    </td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deseja finalizar essa ocorrência?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Sim</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/ocorrencia.js')}}"></script>
@endsection