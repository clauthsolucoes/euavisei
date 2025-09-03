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

function pageEditar(url){
    window.location.href = url;
}