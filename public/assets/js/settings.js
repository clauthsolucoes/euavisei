$(document).ready(function(){
	$("#alterar-btn").on('click', function(ev){
		ev.preventDefault();
		let senha = $("#senha").val();
		let repitirSenha= $("#repitirSenha").val();
		if(senha === repitirSenha){
			$("#form").submit();
		}else{
			alert('As senhas não coincidem');
		}
	});
});