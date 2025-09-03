@extends('../utils/main')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/settings.css')}}">
@endsection
@section('content')
    <div class="container">
    	<div class="row">
    		<h3>Configurações</h3>
    	</div>
     	<form method="post" action="{{url('/alterarSenha')}}" id="form">
     		@csrf
     		<p class="title">Alterar a senha</p>
     		<div class="row">
     			<div class="col-sm-6">
     				<div class="form-group">
     					<label>Digite a nova senha.</label>
     					<input type="password" class="form-control" name="senha" id="senha" required>
     				</div>
     			</div>
     		</div>
     		<div class="row">
     			<div class="col-sm-6">
     				<div class="form-group">
     					<label>Repita a senha.</label>
     					<input type="password" class="form-control" id="repitirSenha" required>
     				</div>
     			</div>
     		</div>
     		<div class="row">
     			<div class="col-sm-6 div-btn">
     				<input type="submit" class="btn btn-primary" id="alterar-btn" value="Alterar">
     			</div>
     		</div>
     	</form>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/settings.js')}}"></script>
@endsection