<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Description of testandoController
 *
 * @author Robson Lopes de Oliveira
 */
class testandoController extends ControllerAtan
{

    // Nome do controlador
    public $nome_controller = 'Testando';

    function __construct(){
        parent::__construct();
    }

    public function index_action(){
        
        $this->permissaoLogado(true);
        if ($_POST) {
                extract($_POST); 
            $this->view("index");
        } else
        {
            $this->view("index");
        }
    }

    public function quebra_gereba()
    {
        /* Setando a permissao para false, para o usuario nao liberado entrar nessa Action */
        //$this->permissaoAcesso(false);
        $this->view("quebra_gereba");
    }

    public function logar()
    {
        if ($this->isLogadoUsuario())
        {
            $direcionar = array("controller" => $this->getController(), "action" => "index");
            $this->redirect($direcionar);
        } else
        {
            $this->view("logar");
        }
    }
    
    public function noticia(){
        $grupo = new GrupoPersistencia(false, 1);
        $grupo->procurar();
        echo $grupo->getNome() . '<br>';
        var_dump($this->getExplode());
    }

    public function logado()
    {
        if ($this->isPost())
        {

            extract($_POST);

            if ($this->logarUsuario($login, $senha))
            {
                $this->view("logado", $this->getPost());
            } else
            {
                $this->view("nao_logado", $this->getPost());
            }
        } else
        {
            $direcionar = array("controller" => $this->getController(), "action" => "logar");
            $this->redirect($direcionar);
        }
    }

    public function deslogar()
    {
        if ($this->isLogadoUsuario())
        {
            $this->deslogarUsuario();
        }

        $direcionar = array("controller" => $this->getController(), "action" => "logar");
        $this->redirect($direcionar);
    }

    public function nao_logado()
    {
        
    }

}
