<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

?>
<html>
    <head>
        <title>Quebra Gereba</title><!-- kkk kk -->
    </head>
    
    <body>
        <p>Outra pagina</p>
    </body>
    
</html>
    