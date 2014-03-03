/* 
 * Descri��o: Fun��es prontas para bloqueio e desbloqueio da tela do JQuery BlockUI
 * Data: 23/03/2010
 * Autor: Fernando Miyahira
 */

//funcao para bloquear a tela (escurecer) com texto padr�o Aguarde...
function bloquearTela(msg, tempo) {
    if (!msg) bloqueio = 'Aguarde...';
    if (!tempo) {
        $.blockUI({
            message: '<strong>'+msg+'</strong>',
            centerY: 0,
            css: {
                top: '150px',
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
    } else {
        $.blockUI({
            message: '<strong>' + msg + '</strong>',
            centerY: 0,
            css: {
                top: '150px',
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            },
            timeout: tempo
        });
    }
}

//funcao para descbloquear a tela
function desbloquearTela() {
    $.unblockUI();
}