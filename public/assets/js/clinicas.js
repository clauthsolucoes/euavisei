function addTelefone(){
    var tag = "<div class='row'><div class='col-sm-4'><div class='form-group'><input type='tel' name='telefone[]' class='form-control' required></div></div><div class='col-sm-1'><div class='form-group'><button type='button' class='btn btn-danger del-tel'><i class='fas fa fa-times'></i></button></div></div></div>";
    $("#telefones-lista").append(tag);

    $(".del-tel").on('click', function(){
        $(this).parent().parent().parent().remove();
    });
}

$("#cep").on('change', function(){
    if($(this).val().length == 8){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/',
            success: function(data){
                $("#logradouro").val(data.logradouro);
                $("#bairro").val(data.bairro);
                $("#cidade").val(data.localidade);
                $("#estado").val(data.uf);
                $("#numero").focus();
            },
            error: function(err){
                alert("Não foi possível buscar o CEP");
            }
        });
    }else{
        alert("CEP inválido");
    }
}); 

$("#grupo").on('change', function(){
    let val = $(this).val();
    var token = $('meta[name=csrf-token]').attr("content");
    if($.fn.dataTable.isDataTable("#tblExame")){
        $("#tblExame").DataTable().destroy();
    }
    $.ajax({
        url: '/exames/lista/grupamento',
        type: 'post',
        data: {
            '_token': token,
            grupo: val
        },
        success: function(dt){
            $("#bodyExm").empty();
            dt.forEach(content =>{
                let option = "<tr class='tr-exame-click' onclick='adicionarExame("+content.id+", "+content.grupamento_id+")'>" + 
                    "<td class='cods'>"+content.thm_1992+"</td>" + 
                    "<td class='cods'>"+content.codigo_tuss+"</td>" +
                    "<td>"+content.exame+"</td>" + 
                    "<td class='cods' id='td_"+content.id+"'>"+ returnCheck(content.id) +"</td>" +
                "</tr>";
                $("#bodyExm").append(option);
            });
        },  
        error:function(err){
            console.log(err);
        },
        complete: function(data){
            $("#tblExame").DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                }
            });
        }
    });
});

function adicionarExame(id, grupo){
    let td = "td_" + id;
    let exame = $(".exameInput[value='"+id+"']");
    if(exame.length > 0){
        exame.remove();
        $("#"+td).empty();
    }else{
        let inp = "<input type='hidden' value='"+id+"' class='exameInput grupo_"+grupo+"' name='exames[]'>";
        $("#inputsExames").append(inp);
        $("#"+td).html("<i class='fas fa-check i-class'></i>");
    }
    let nome = $("#grupo option[value='"+grupo+"']").text();
    let i = (nome.split(" - "));
    $("#grupo option[value='"+grupo+"']").text(i[0] + " - " + $(".grupo_"+grupo).length);
}

function returnCheck(id){
    let exame = $(".exameInput[value='"+id+"']");
    if(exame.length > 0){
        return "<i class='fas fa-check i-class'></i>";
    }
    return "";
}

$(".del-tel").on('click', function(){
    $(this).parent().parent().parent().remove();
});

$(".val_ex").on('change', function(){
    let val = $(this).val();
    var token = $('meta[name=csrf-token]').attr("content");
    let id = $(this)[0].id;
    let clinica = $("#clinica").val();
    $.ajax({
        url: '/clinicas/exames/valor',
        type: 'post',
        data: {
            '_token': token,
            val: val,
            id_exame: id,
            clinica: clinica
        },
        success: function(dt){
            console.log(dt);
        },  
        error:function(err){
            console.log(err);
        }
    })
});

$("#img-upload").on('change', function(ev){
    console.log(ev);
    var output = document.getElementById('img-tag');
    output.src = URL.createObjectURL(ev.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
});

$(document).ready(function(){
    $("#cnpj").mask('00.000.000/0000-00');
    $(".cpf").mask('000.000.000-00');
    $(".tel").mask('(00) 0 0000-0000');
});