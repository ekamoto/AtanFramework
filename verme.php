<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/*
  Script distribuído por brasilphp.net
  Qualquer dúvida, escreva para contato@brasilphp.net
  Para criação e manutenção de scripts e sistemas, escreva para contato@sobralsites.com
 */

function varSet($VAR)
{
    return isset($_GET[$VAR]) ? $_GET[$VAR] : "";
}

$action = varSet("action");
$pasta = base64_decode(varSet("pasta"));

//Lista dos arquivos que nao serão listados
$denyFiles = array(".htaccess", "thumbs.db");

if ($action == "download")
{
    $file = base64_decode(varSet("file"));
    header("Content-disposition: attachment; filename=\"" . basename($file) . "\"");
    readfile(".$file");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Listagem de Arquivos</title>
        <style type="text/css">
            body {
                font:11px Verdana, Arial, Helvetica, sans-serif;
                padding:0px;
                margin:0px;
            }
            a {
                text-decoration:none;
                color:#003366;
            }
            a:hover { color:#0099CC }
            .row1 { background-color:#F7F7F7 }
            .row2 { background-color:#EBEBEB }
            .rowOver { background-color:#C7DCFC }
            .extCell { font-weight:bold }
        </style>
        <script language="javascript" type="text/javascript">
            function over(Obj) {
                nClass = Obj.className
                Obj.className = "rowOver"
                Obj.onmouseout = function() {
                    Obj.className = nClass
                }
            }
        </script>
    </head>

    <body>
        <?php
        if ($action == ""):
            $fdir = "./$pasta";
            chdir($fdir);
            $dir = opendir(".");
            while ($file = readdir($dir))
                if (is_dir($file))
                    $dirs[] = $file; else
                    $files[] = $file;
            $row = 2;
            ?>
            <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="50px;"><strong>P.S:</strong> "listar.php" &eacute; o arquivo deste sistema</td>
                </tr>
                <tr>
                    <td height="50px;"><strong>Exibindo:</strong> ROOT <?php echo empty($pasta) ? "" : $pasta; ?></td>
                </tr>
            </table>
            <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr style="font-weight:bold">
                    <td width="55" height="20">&nbsp;</td>
                    <td width="204">Nome</td>
                    <td width="130">Tamanho</td>
                    <td width="316">A&ccedil;&otilde;es</td>
                </tr>
    <?php if ($pasta != ""): ?>
                    <tr class="row<?php echo $row; ?>" onmouseover="over(this)">
                        <td align="center" width="55" height="20" class="extCell">[DIR]</td>
                        <td><a href="?pasta=<?php echo base64_encode(substr("$pasta", 0, strrpos($pasta, "/"))); ?>">..</a></td>
                        <td>-</td>
                        <td>&nbsp;</td>
                    </tr>
                <?php endif; ?>
                <?php
                if (is_array($dirs)) :
                    sort($dirs);
                    foreach ($dirs as $nome):
                        if ($nome == ".." || $nome == ".")
                            continue;
                        if ($row == 2)
                            $row = 1; else
                            $row = 2;
                        ?>
                        <tr class="row<?php echo $row; ?>" onmouseover="over(this)">
                            <td align="center" width="55" height="20" class="extCell">[DIR]</td>
                            <td><a href="?pasta=<?php echo base64_encode("$pasta/$nome"); ?>"><?php echo $nome; ?></a></td>
                            <td>-</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                <?php
                if (is_array($files)):
                    sort($files);
                    foreach ($files as $nome):
                        if (in_array(strtolower($nome), $denyFiles))
                            continue;
                        if ($row == 2)
                            $row = 1; else
                            $row = 2;
                        $tamanho = filesize("./$nome");
                        $info = pathinfo("./$nome");
                        ?>
                        <tr class="row<?php echo $row; ?>" onmouseover="over(this)">
                            <td align="center" width="55" height="20" class="extCell">[<?php echo strtoupper($info["extension"]); ?>]</td>
                            <td>
            <?php $link = "?action=download&file=" . base64_encode("$pasta/$nome"); ?>
                                <a href="<?php echo $link; ?>" ><?php echo $nome; ?></a>
                            </td>
                            <td><?php echo $tamanho > 1048576 ? round($tamanho / 1048576, 2) . " Mb" : round($tamanho / 1024, 2) . " Kb"; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </table>
<?php endif; ?>
    </body>
</html>
<?php closedir($dir); ?>
