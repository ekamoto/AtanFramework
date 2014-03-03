<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Description of GeradorCodigoModel
 *
 * @author Leandro Shindi Ekamoto
 */
class GeradorCodigoModel
{
    function __construct()
    {
//        echo teste;
    }

}