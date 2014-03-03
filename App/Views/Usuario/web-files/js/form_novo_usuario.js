$(function(){
    $(".botao_cadastrar").click(function() {
        var campos_ok = $(".campos_obrigatorio").validaFormulario();
        if(campos_ok) {
            bloquearTela('Aguarde...');
            $.ajax({ 
                    url: "cadastrarUsuario", 
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
                    $("#nome").val("");
                    if(retorno.rs) {
                        $("#msg").css("background-color","#98FB98");
                    } else {
                        $("#msg").css("background-color","#FF4500");
                    }
                    desbloquearTela();
                }
            });   
        }
    });
    $(".botao_voltar").click(function(){
        history.back(); 
    });
});