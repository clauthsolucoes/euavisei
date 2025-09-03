@extends('../utils/main')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datlabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/chart.min.css')}}">
@endsection
@section('content')
<div class="row">
    <p class="tlt">Ocorrências ativas</p>    
</div>
<div class="row">
    @foreach($counter as $item)
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{$item->ocorrencia}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$item->quant}}</div>
                        <img src="{{asset('assets/img/'.$item->ocorrencia.'.png')}}" class="img-tipos">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quantidade de ocorrências por tipo</h6>
            </div>
            <div class="card-body">
                <canvas id="chartPieTipo"></canvas>
                <script>
                    var canvas = document.getElementById('chartPieTipo');
                    var ctx = canvas.getContext("2d");
                    var data = {
                        datasets: [{
                            data: [
                            @foreach($geral as $data)
                                {{$data->quant}},
                            @endforeach
                            ],
                            backgroundColor: ['#B1B1B1', '#EA0404', '#FF5403', '#1567E2'] ,
                            label: 'Ocorrências'
                        }],
                        labels: [
                            @foreach($geral as $data)
                                '{{$data->ocorrencia}}',
                            @endforeach
                        ]
                    };
                    var myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: {
                            animation: {
                                duration: 0
                            },
                            responsive: true,
                            legend: {
                                position: 'right'
                            },
                            plugins: {
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        let sum = 0;
                                        let dataArr = ctx.chart.data.datasets[0].data;
                                        dataArr.map(data => {
                                            sum += parseInt(data);
                                        });
                                        let percentage = (value*100 / sum).toFixed(1)+"%";
                                        return percentage;
                                    },
                                    color: '#fff',
                                }
                            },
                            onClick: function (event, item) {
                                var activePoints = this.getElementAtEvent(event);
                                var index = activePoints[0]._index;
                                var element = activePoints[0]['_chart'].config.data.labels[index];
                            },
                            onHover: (event, chartElement) => {
                                event.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                            }
                        }
                    });
                </script>
            </div>
            <div class="card-footer py-3">
                <h6 style='float: left' class="m-0 font-weight-bold text-primary"><strong>Total de ocorrências: </strong>{{$total}}</h6>  
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quantidade de ocorrências por dia</h6>
                </div>
                <div class="card-body">
                    <canvas id="line-chart4"></canvas>
                    <script>
                        var arr = [];
                        @foreach($tempo as $val)
                        arr.push('{{$val->diames}}');
                        @endforeach
                        var bg = ['#00bfa5', '#00b8d4', '#0091ea', '#ce93d8', '#aa00ff', '#f06292'];
                        new Chart(document.getElementById("line-chart4"), {
                            type: 'line',
                            data: {
                                labels: arr,
                                datasets: [ 
                                    {    
                                        data: [
                                            @foreach($tempo as $val)
                                                '{{$val->quant}}',
                                            @endforeach
                                        ],
                                        label: "Quantidade de ocorrências",
                                        borderColor: bg[3],
                                        fill: false
                                    }
                                ]
                                
                            },
                            options: {}
                        });
                    </script>
                </div>
                <div class="card-footer py-3">
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset('assets/js/dashboard.js')}}"></script>
@endsection