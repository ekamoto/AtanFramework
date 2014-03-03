$(function() {
    
    // Plugin para validar campos obrigatórios
    jQuery.fn.validaFormulario = function() {
        var campos_ok = true;
        var campos_invalidos = '';
        var i = 1;
        var nome_campo = '';
        $(this).each(function() {
            nome_campo = $(this).attr('name');
            if(!$(this).val()) {
                campos_ok = false;
                campos_invalidos += i + "-" + nome_campo + "\n";
                i++;
                $("#msg_"+nome_campo).html("Campo " + nome_campo + " obrigatório.");
            } else {
                $("#msg_"+nome_campo).html("");
            }
        });
        if(!campos_ok) {
            return false;
        }
        return true;
    };
    
    // Bloquear tela
    function bloquearTela(msg) {
        $.blockUI({
            message: "<h1>" + msg + "</h1>"
        }); 
    }
    
    // Desbloquear tela
    function desbloquearTela() {
        $.unblockUI();
    }

    $( ".data" ).datepicker({   
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
    });
    $('.data_mascara').mask("99/99/9999");
    
    $("#gerar_modulo").click(function(){
        $.ajax({ 
                url: "getModulo", 
                global: false, 
                type: "POST", 
                data: ({ 
                dados_form:$("#form_1").serializeArray()
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
            }
        });   
    });
});