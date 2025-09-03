function adicionar(segmento){
    var txt = 'Dor de cabeça, enxaqueca, etc...';
    if(segmento == 'exame'){
        txt = 'Hemograma, Exame de Urina, etc...';
    }
    let html = `<div class="row"><div class="col-sm-8"><div class="form-group"><input type="text" class="form-control" placeholder="Ex.: ${txt}" name="${segmento}[]"></div></div><div class="col-sm-1"><div class="form-group"><button type="button" class="btn btn-danger btn-remover"><i class="fa fa-times"></i></button></div></div></div>`;

    $("#box-add").append(html);

    $(".btn-remover").click(function(){
        $(this).parent().parent().parent().remove();
    });
}

$(".btn-remover").click(function(){
    $(this).parent().parent().parent().remove();
});