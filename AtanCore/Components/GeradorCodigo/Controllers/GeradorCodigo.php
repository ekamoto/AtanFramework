<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Classe que gerá códigos
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
class GeradorCodigo
{

    private $app;
    private $nome_controller;
    private $nome_model;
    private $autor;
    private $descricao_controller;
    private $descricao_model;
    private $pasta_view;
    private $_tabela;
    private $nome_view;

    function __construct()
    {
        $this->pasta_view = false;
        $model = new GeradorCodigoModel();
    }
    
    
    public function getNomeView()
    {
        return $this->nome_view;
    }

    public function setNomeView($nome_view)
    {
        $this->nome_view = $nome_view;
    }

    public function getTabela()
    {
        return $this->_tabela;
    }

    public function setTabela($_tabela)
    {
        $this->_tabela = $_tabela;
    }

    public function getPastaView()
    {
        return $this->pasta_view;
    }

    public function setPastaView($pasta_view)
    {
        $this->pasta_view = $pasta_view;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;
    }
    
    public function getDescricaoController() {
        return $this->descricao_controller;
    }

    public function setDescricaoController($descricao_controller) {
        $this->descricao_controller = $descricao_controller;
    }

    public function getDescricaoModel() {
        return $this->descricao_model;
    }

    public function setDescricaoModel($descricao_model) {
        $this->descricao_model = $descricao_model;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setApp($app)
    {
        $this->app = $app;
    }

    public function getNomeController()
    {
        return $this->nome_controller;
    }

    public function setNomeController($nome_controller)
    {
        $this->nome_controller = $nome_controller;
    }

    public function getNomeModel()
    {
        return $this->nome_model;
    }

    public function setNomeModel($nome_model)
    {
        $this->nome_model = $nome_model;
    }

    public function addController()
    {
        $arquivo = CONTROLLERS . $this->nome_controller . 'Controller.php';

        // Abre arquivo
        $fp = fopen($arquivo, 'w');

        // Deixa primeira letra em maiúscula
        $nome = ucfirst($this->nome_controller);

        /* -------------------------------Gera Controller--------------------------------- */
// Tem que ser dessa forma, senão não fica identado,
// Observação: descobrir outra forma para identar
        $cod_php = "
<?php
/**
* " . $this->descricao_controller . "
*
* @author " . $this->autor . "
* @version 1.0
* @copyright 2012 Atan tecnologia
* 
*/
class " . $this->nome_controller . "Controller extends ControllerAtan 
{
    public " . '$nome_controller' . " = '" . $nome . "';

    public function index_action() 
    {
        " . '$this->view("index");' . "
    }
}";

        $cod_php = str_replace('"', "'", $cod_php);

        // Escreve
        $escreve = fwrite($fp, $cod_php);

        // Fecha
        fclose($fp);

        chmod($arquivo, 0777);
    }

    public function addPastaView()
    {
        // Deixa primeira letra em maiúscula
        $nome = ucfirst($this->nome_controller);

        $pasta_nova = VIEWS . $nome . '/';

        if (isset($this->pasta_view) && !empty($this->pasta_view) && !is_dir($pasta_nova))
        {
            mkdir($pasta_nova, 0777);
            chmod($pasta_nova, 0777);
        }
    }

    public function addModel()
    {

        // Deixa primeira letra em maiúscula
        $nome = ucfirst($this->nome_controller);

        $arquivo = MODELS . $nome . 'Model.php';

        // Abre arquivo
        $fp = fopen($arquivo, 'w');

        /* -------------------------------Gera Model--------------------------------- */
// Tem que ser dessa forma, senão não fica identado,
// Observação: descobrir outra forma para identar
        $cod_php = '
<?php

/**
 * ' . $this->descricao_model . '
 *
 * @author ' . $this->autor . '
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
class ' . $nome . 'Model extends ModelAtan 
{
    public $_tabela = "' . $this->_tabela . '";
        
    function __construct() {
        parent::__construct("' . $this->_tabela . '");
    }
}';

        // Escreve
        $escreve = fwrite($fp, trim($cod_php));

        // Fecha
        fclose($fp);

        chmod($arquivo, 0777);
    }

    public function addView()
    {
        // Deixa primeira letra em maiúscula
        $nome = ucfirst($this->nome_controller);

        $arquivo = VIEWS . $nome . '/' . $this->nome_view . '.phtml';

        // Abre arquivo
        $fp = fopen($arquivo, 'w');

        /* -------------------------------Gera Controller--------------------------------- */
// Tem que ser dessa forma, senão não fica identado,
// Observação: descobrir outra forma para identar
        $cod_php = "<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /> 
    </head>
    <body>
        View $this->nome_view criada com sucesso!
    </body>
</html>
";

        $cod_php = str_replace("'", '"', $cod_php);

        // Escreve
        $escreve = fwrite($fp, $cod_php);

        // Fecha
        fclose($fp);

        chmod($arquivo, 0777);
    }

}