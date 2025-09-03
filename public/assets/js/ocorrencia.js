$("#btn-add-img").on('click', function(){
    $("#file-img").click();
});

$("#file-img").on('change', function(ev){
    const [file] = this.files
    if (file) {
        document.getElementById("img-tag").src = URL.createObjectURL(file);
    }
});

$("#buscar-file-img").on('blur', function(){
    let end = 'https://sistema.euavisei.app/public/novas_ocorrencias/' + $(this).val();
    document.getElementById("img-tag").src = end;
});

$("#btn-registrar").on('click', async function(event){
     event.preventDefault();
     let file = ($("#file-img")[0].files.length > 0)? await toBase64($("#file-img")[0].files[0]) : '';
    let token = $('meta[name=csrf-token]').attr("content");
     let vals = {
         '_token': token,
        'titulo': $("input[name='titulo']").val(),
        'descricao': $("textarea[name='descricao']").val(),
        'imagem': file,
        'tipo-ocorrencia': $("select[name='tipo-ocorrencia']").val(),
        'categoria_tipo_ocorrencia':  $("select[name='categoria_tipo-ocorrencia']").val(),
        'data-hora-ocorrencia': $("input[name='data-hora-ocorrencia']").val(),
        'timer': $("input[name='timer']").val(),
        'logradouro': $("input[name='logradouro']").val(),
        'bairro': $("input[name='bairro']").val(),
        'cidade': $("input[name='cidade']").val(),
        'estado': $("input[name='estado']").val(),
        'numero': $("input[name='numero']").val(),
        'extraordinario': $("input[name='extraordinario']:checked").val(),
        'imagem_buscada': $("input[name='imagem_buscada']").val()
     };
     if(!vals['titulo'] || !vals['descricao'] || !vals['data-hora-ocorrencia'] || !vals['tipo-ocorrencia'] || !vals['timer'] || !vals['bairro'] || !vals['cidade'] || !vals['estado']){
         alertInputs(vals);
         return false;
     }
     $.ajax({
        url: '/ocorrencias/socket/cadastrar',
        type: 'post',
        data: vals,
        success: function(res){
            socket.emit('registrar-ocorrencia', res);
            //location.reload();
        },
        error: function(err){
            console.log(err);
        }
     });
});

$("select[name='tipo-ocorrencia']").on('change', function(){
   let value = $(this).val();
   let token = $('meta[name=csrf-token]').attr("content");
    $.ajax({
        url: '/ocorrencias/subcategorias/listar',
        type: 'post',
        data: {
           '_token': token,
           'tipo_ocorrencia': value
        },
        success: function(res){
            $("select[name='categoria_tipo-ocorrencia']").empty();
            if(res.length < 1){
                $("select[name='categoria_tipo-ocorrencia']").prop('disabled', true);
                return false;
            }
            let options = '';
            res.forEach((el) =>{
                options += `<option value="${el.id}">${el.categoria}</option>`; 
            });
            $("select[name='categoria_tipo-ocorrencia']").append(options);
            $("select[name='categoria_tipo-ocorrencia']").prop('disabled', false);
       },
       error: function(err){
           console.log(err);
       }
   })
   console.log(value); 
});

function pageEditar(url){
    window.location.href = url;
}

const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = reject;
});

function alertInputs(vals){
    if(!vals['titulo']){
        alert("Preencha o título");
        return false;
    }
    if(!vals['descricao']){
        alert("Preencha a descrição");
        return false;
    }
    if(!vals['data-hora-ocorrencia']){
        alert("Preencha a data e hora do início da ocorrência");
        return false;
    }
    if(!vals['timer']){
        alert("Preencha a data e hora do fim da ocorrência");
        return false;
    }
    if(!vals['tipo-ocorrencia']){
        alert("Selecione o tipo de ocorrência");
        return false;
    }
    if(!vals['bairro']){
        alert("Preencha o bairro");
        return false;
    }
    if(!vals['cidade']){
        alert("Preencha a cidade");
        return false;
    }
    if(!vals['estado']){
        alert("Preencha o estado");
        return false;
    }
}

