<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

session_start();

/**
 * Classe genéria para os controladores
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
abstract class ControllerAtan extends System {

    /**
     * Variável que contém o nome do controlador, ela é setada quando
     * criamos um controller que extende essa classe. É obrigatório porque
     * é atravéz dela que busco a pasta view correspondente
     * @access public
     * @name $nome_controller
     * 
     */
    public $nome_controller;

    /**
     * Obj Model
     * @access private
     * @name $model
     * 
     */
    private $model;

    /**
     * Atributo db
     * @access public
     * @name $db
     * 
     */
    public $db;

    /**
     * POST
     * @access public
     * @name $post
     * 
     */
    public $post;

    /**
     * Local da view .phtml
     * @access public
     * @name $local_view_phtml
     * 
     */
    public $local_view_phtml;

    /**
     * Local da view .phtml
     * @access public
     * @name $local_view_php
     * 
     */
    public $local_view_php;

    /**
     * Exemplo Simples de permissao de autenticacao
     * 
     * @access private
     * @author Robson Oliveira
     * @version 1.0
     * @copyright 2012 Ata tecnologia
     * 
     */
    protected $controlleAutenticacao;
    protected $controlleTratamentoErro;

    /**
     * Plasta para template
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Ata tecnologia
     * 
     */
    private $pasta_template;
    
    /**
     * Plasta da view
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Ata tecnologia
     * 
     */
    private $pasta_view;

    /**
     * Dados para poder usar nas views
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Ata tecnologia
     * 
     */
    public $dados;
    
    /**
     * Caminho para webfiles
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Ata tecnologia
     * 
     */
    public $caminho_pasta_web_files;

    /**
     * Construtor
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function __construct() {
        parent::__construct();

        // Adicionando o model correspondente
        $arquivo_model = MODELS . $this->nome_controller . 'Model.php';

        // Se o modelo existir
        if (file_exists($arquivo_model)) {
            $model = $this->nome_controller . 'Model';
            $this->model = new $model;

            if (is_object($this->model)) {
                $this->db = $this->model->getDb();
            }
        }

        // Se tiver requisição post
        if ($this->isPost()) {
            $this->post = $_POST;
        }

        $this->controlleAutenticacao = new Autenticacao();
        $this->controlleTratamentoErro = new TratamentoDeErro();
    }

    public function getModel() {
        return $this->model;
    }

    /**
     * Verifica se tem requisição POST
     * 
     * @access public
     * @return boolean
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function isPost() {
        // Se tiver requisição post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se ajax
     * 
     * @access public
     * @return boolean
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
            return true;
        }

        return false;
    }
    
    /**
     * Ajusta dados de uma formulário que foi serializado pela função SerializeArray
     * 
     * @access public
     * @param array $dados_serializados dados serializados de um forma
     * @return boolean false se erro
     * @return array $dados dados ajustados
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function ajustaDadosSerializeArray($dados) {
        if(is_array($dados) && !empty ($dados)) {
            $dados_retorno = array();
            foreach ($dados as $reg) {
                $dados_retorno[$reg['name']] = $reg['value'];
            }    
            return $dados_retorno;
        }
        return false;
    }

    /**
     * Controla acesso de usuario
     * 
     * @access protected
     * @param boolean $acesso true para acesso livre e false para nao acesso
     * @param string $usuario nome de usuario que pode acessar
     * @author Robson Oliveira
     * @version 1.0
     * @copyright 2012 Atan tecnologia 
     * 
     */
    
    protected abstract function controleAction($proximaAction);
    
    protected function permissaoAcesso($acesso = false) {
        $this->controlleAutenticacao->setPermissao($acesso);
    }

    protected function permissaoLogado($acesso = false) {
        $this->controlleAutenticacao->setPermissaoLogin($acesso);
    }

    protected function logarUsuario($login, $senha) {
        return $this->controlleAutenticacao->isUsuario($login, $senha);
    }

    protected function deslogarUsuario() {
        $this->controlleAutenticacao->sessionDestruir();
    }

    protected function isLogadoUsuario() {
        return $this->controlleAutenticacao->isSessionAtiva();
    }

    protected function criarTratamentoErro($nomeErro, Array $dadoErro) {
        $this->controlleTratamentoErro->criarSessionErro($nomeErro, $dadoErro);
    }

    protected function isTratamentoErro($nomeErro) {
        return $this->controlleTratamentoErro->isSessionErro($nomeErro);
    }

    protected function restaurarTratamentoErro($nomeErro) {
        return $this->controlleTratamentoErro->restaurarSessionErro($nomeErro);
    }

    protected function destruirTratamentoErro($nomeErro) {
        $this->controlleTratamentoErro->destruirSessionErro($nomeErro);
    }

    public function getPastaTemplate() {
        return $this->pasta_template;
    }

    public function setPastaTemplate($pasta_template) {
        $this->pasta_template = $pasta_template;
    }

    private function getView($view = false) {
        if ($view) {
            if (file_exists($this->local_view_phtml)) {
                require_once($this->local_view_phtml);
            } else if (file_exists($this->local_view_php)) {
                require_once($this->local_view_php);
            } else {
                die("View {$view} não existe. ");
            }
        }
    }
    public function getPasta_view() {
        return $this->pasta_view;
    }

    public function setPasta_view($pasta_view) {
        $this->pasta_view = $pasta_view;
    }

    public function includeJs($arquivo_js) {
        $caminho = VIEWS . $this->pasta_view . '/web-files/js/' . $arquivo_js . '.js';
        if(file_exists($caminho)) {
            $src = URL . '/' . $caminho;
            echo "<script type='text/javascript' src='{$src}'></script>";
        }
    }
    
    public function includeCss($arquivo_css) {
        $caminho = VIEWS . $this->pasta_view . '/web-files/css/' . $arquivo_css . '.css';
        if(file_exists($caminho)) { 
            $href = URL . '/' . $caminho;
            echo "<link rel='stylesheet' href='$href'/>";
        }
    }
    
    public function includeLibrariesJS($libs) {
       $libraries = "";
       foreach ($libs as $lib) {
          $libraries .= '<script type="text/javascript" src="' . $this->caminho_pasta_web_files . '/js/' . $lib . '"></script>';
       }
       echo $libraries;
    }
    public function includeLibrariesCSS($libs) {
       $libraries = "";
       foreach ($libs as $lib) {
          $libraries .= '<link rel="stylesheet"  href="' . $this->caminho_pasta_web_files . '/css/' . $lib . '" />';
       }
       echo $libraries;
    }
    /**
     * Inclui a view
     * 
     * @access protected
     * @param string $view view que está sendo chamada
     * @param array  $dados dados passados para view
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia 
     * 
     */
    protected function view($view, Array $dados = null) {
        /* Barrando usuario que nao tem permissao */
        if (!$this->controlleAutenticacao->getPermissao())
            die("Opa meu rapaz, você não tem permissao para essa sessao.");

        if ($this->controlleAutenticacao->getPermissaoLogin()) {
            if (!$this->controlleAutenticacao->isSessionAtiva()) {
                die("somente Logado");
            }
        }

        // O interessante disso é que não perco o array, posso usar se eu quiser nas views
        if (is_array($dados) && count($dados)) {
            // Setando prefixo das variáveis disponíveis na view
            extract($dados, EXTR_PREFIX_ALL, "view");
            if(isset($dados['dados']) && !empty ($dados['dados'])) {
                $this->dados = $dados['dados'];
            }
        }

        if (isset($dados['pasta_view']) && !empty($dados['pasta_view'])) {
            $this->pasta_view = $dados['pasta_view'];
            $this->local_view_phtml = VIEWS . $dados['pasta_view'] . '/' . $view . '.phtml';
            $this->local_view_php = VIEWS . $dados['pasta_view'] . '/' . $view . '.php';
        } else {
            $this->pasta_view = ucwords($view);
            $this->local_view_phtml = VIEWS . $this->nome_controller . '/' . $view . '.phtml';
            $this->local_view_php = VIEWS . $this->nome_controller . '/' . $view . '.php';
        }
        
        // Se tiver template
        if (is_array($dados) && !empty($dados) && isset($dados['template'])) {
            
            // Seta a nome da pasta do template
            $this->setPastaTemplate(ucwords($dados['template']));

            // Caminho para a pasta web-files
            $caminho_pasta_web_files = TEMPLATE . "/" . $this->getPastaTemplate() . "/web-files";
            $this->caminho_pasta_web_files = $caminho_pasta_web_files;

            require_once VIEWS . '/Template/' . $this->getPastaTemplate() . '/' . $dados['template'] . '.phtml';
            
        }
    }
    
    

    /**
     * Redireciona a página
     * 
     * @access protected
     * @param array  $dados dados['controller']['action']
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    protected function redirect(Array $dados = array()) {
        if (isset($dados['destino']) && !empty($dados['destino'])) {
            $url = URL . $dados['destino'];
            header("Location: {$url}");
            return;
        }

        if (empty($dados)) {
            $url = URL;
            header("Location: {$url}/");
            return;
        }

        if (isset($dados['controller']) && !empty($dados['controller'])) {
            $controller = $dados['controller'];
        }

        if (isset($dados['action']) && !empty($dados['action'])) {
            $action = $dados['action'];
        } else {
            $action = "";
        }

        $url = URL;

        header("Location: {$url}/{$controller}/{$action}");
    }
}