let preparo;
let preparoConf = false;

function salvarPreparo(){
    $("#finalizadoPreparo").val(true);
    var preparo = $("#preparo_input").val();
    if(!preparo){
        alert("O campo de preparo do exame não pode estar em branco");
        return false;
    }
    var token = $('meta[name=csrf-token]').attr("content");
    var exame_id =  $("#preparoExameId").val();
    var clinica_id = $("#clinica").val();
    $.ajax({
        url: '/exames/clinica/preparo',
        type: 'post',
        data: {
            '_token': token,
            exame_id: exame_id,
            clinica_id: clinica_id,
            preparo: preparo
        },
        success: function(dt){
            if($("#prep_"+exame_id)){
                $("#prep_"+exame_id).val(preparo);
                return false;
            }
            $("#prep_"+exame_id).val(preparo);
            var ex = $("#ex_"+exame_id).val();
            let div = '<button id="divexame_'+exame_id+'" type="button" class="btn btn-icon comment" onclick="modalPreparo('+exame_id+', "'+ex+'")" data-toggle="modal" data-target="#modalPreparo">'+
                '<i class="fas fa-comment-medical icons-primary"></i>' +
            '</button>';
            $("#dv_"+exame_id).append(div);
        },  
        error:function(err){
            console.log(err);
        },
        complete: function(){
            $("#modalPreparo").modal('hide');
        }
    });
}

$("#confPreparo").on('click', function(){
    preparoConf = false;
    $("#modalConf").modal('hide');
    $(preparo).prop('checked', false);
    var exame_id =  $("#preparoId").val();
    var token = $('meta[name=csrf-token]').attr("content");
    var clinica_id = $("#clinica").val();
    $.ajax({
        url: '/exames/clinica/preparo',
        type: 'post',
        data: {
            '_token': token,
            exame_id: exame_id,
            clinica_id: clinica_id,
            preparo: ""
        },
        success: function(dt){
            $("#prep_"+exame_id).val(preparo);
            $("#divexame_"+exame_id).remove();
            let id = $(preparo).attr('name');
            let val = "0" + $(preparo).attr('value').substring(1);
            let inp = "input[name='"+id+"'][value='"+val+"']";
            $(inp).prop('checked', true);
        },  
        error:function(err){
            console.log(err);
        },
        complete: function(){
            $("#modalPreparo").modal('hide');
        }
    });
});

$("#modalPreparo").on("hidden.bs.modal", function () {
    let cond = $("#finalizadoPreparo").val();
    if(cond == 'false'){
        let id = $(preparo).attr('name');
        let val = "0" + $(preparo).attr('value').substring(1);
        let inp = "input[name='"+id+"'][value='"+val+"']";
        $(inp).prop('checked', true);
        $(preparo).prop('checked', false);
    }
});

$("#modalConf").on('hidden.bs.modal', function(){
    if(preparoConf){
        let id = $(preparo).attr('name');
        let val = "1" + $(preparo).attr('value').substring(1);
        let inp = "input[name='"+id+"'][value='"+val+"']";
        $(inp).prop('checked', true);
        $(preparo).prop('checked', false);
    }
    preaproConf = false;
});

$(".agendamento").on('change', function(){
    let vals = $(this).val().split("|");
    let check = vals[0];
    let exame = vals[1];
    var clinica_id = $("#clinica").val();
    var token = $('meta[name=csrf-token]').attr("content");
    $.ajax({
        url: '/exames/clinica/agendamento',
        type: 'post',
        data: {
            '_token': token,
            exame_id: exame,
            clinica_id: clinica_id,
            agendamento: check
        },
        success: function(dt){
            console.log(dt);
            console.log('Atualizado com sucesso.');
        },  
        error:function(err){
            console.log(err);
        }
    });
});

$(".preparo").on('change', function(){
    $("#preparo_input").val("");
    let vals = $(this).val().split("|");
    let check = vals[0];
    $("#preparoId").val(vals[1]);
    preparo = $(this);
    if(check == '0'){
        $("#btnPreparoConf").click();
        preparoConf = true;
        return false;
    }
    $("#preparoExameId").val(vals[1]);
    $("#spnPreparo").text(vals[2]);
    $("#finalizadoPreparo").val(false);
    $("#btnPreparo").click();
});

function salvarAgedamento(){

}

function  removerExame(){

}


function modalPreparo(id, exame){
    $("#preparoExameId").val(id);
    $("#spnPreparo").text(exame);
    let preparo = $("#prep_"+id).val();
    $("#preparo_input").val(preparo);
}

function modalAgendamento(id, exame){
    $("#agendamentoExameId").val(id);
}

function modalRemover(id, exame){
    $("#removerExameId").val(id);
}