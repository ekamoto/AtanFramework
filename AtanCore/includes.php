<?php
/**
 * Esse arquivo faz include em todos os arquivos da pasta includes
 * 
 * 
 */
if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

$handle = opendir($caminho . '/Includes');
if ($handle)
{
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != "..")
        {
            $ext = substr($file, -4);
            if ($ext == ".php")
            {
                include 'Includes/' . $file;
            }
        }
    }
    closedir($handle);
    unset ($handle, $file, $ext);
} else {
    echo "Falha ao abrir diretório!";
}
?>