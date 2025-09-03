$(document).ready(function(){
    $('input[name="cep"]').on('change', function(){
        console.log($(this).val());
        if($(this).val().length == 8){
            $.ajax({
                url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/',
                success: function(data){
                    $("input[name='logradouro']").val(data.logradouro);
                    $("input[name='bairro']").val(data.bairro);
                    $("input[name='cidade']").val(data.localidade);
                    $("input[name='estado']").val(data.uf);
                    $("input[name='numero']").focus();
                },
                error: function(err){
                    alert("Não foi possível buscar o CEP");
                }
            });
        }else{
            alert("CEP inválido");
        }
    });
});