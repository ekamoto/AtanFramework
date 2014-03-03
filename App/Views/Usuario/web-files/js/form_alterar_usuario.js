$(function(){
    $(".botao_alterar").click(function() {
        var campos_ok = $(".campos_obrigatorio").validaFormulario();
        if(campos_ok) {
            $.blockUI({
                message: "<h1>Aguarde...</h1>"
            }); 
            $.ajax({ 
                    url: "editar_usuario", 
                    global: false, 
                    type: "POST", 
                    data: ({ 
                    dados_form:$("#form").serializeArray()
                }),
                dataType : "json",
                    async : false,
                    success:
                function(retorno) {
                    $("#msg").html(retorno.msg);
                    if(retorno.rs) {
                        $("#msg").css("background-color","#98FB98");
                    } else {
                        $("#msg").css("background-color","#FF4500");
                    }
                    $.unblockUI();
                }
            });   
        }
    });
    $(".botao_voltar").click(function(){
        history.back(); 
    });
});