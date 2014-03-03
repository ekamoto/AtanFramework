<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Classe principal que faz o controle dos controladores e actions
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
class System
{

    /**
     * Variável contém a url
     * @access private
     * @name $_url
     * 
     */
    private $_url;
    
    /**
     * Variável contém a url na forma de array
     * @access private
     * @name $_explode
     * 
     */
    private $_explode = array();
    
    /**
     * Variável contém o controller
     * @access private
     * @name $_controller
     * 
     */
    private $_controller;
    
    /**
     * Variável contém a action
     * @access private
     * @name $_action
     * 
     */
    private $_action;
    
    /**
     * Variável contém os parâmetros
     * @access private
     * @name $_action
     *  
     */
    private $_params = array();
    
    /**
     * Array, rotas
     * 
     */
    private $routes = array(
        "abacaxi" => "index/index"
    );
    
    /**
     * Construtor que ativa todos os processos para transformar
     * url em controller, action e parâmetros
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function __construct()
    {
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    /**
     * Pega o conteúdo da url e seta na variável do objeto
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function setUrl()
    {
        $_GET['conteudo_url'] = isset($_GET['conteudo_url']) ? $_GET['conteudo_url'] : "index/index";
        if(isset($this->routes[$_GET['conteudo_url']])) {
           $this->_url = $this->routes[$_GET['conteudo_url']];
        } else {
           $this->_url = $_GET['conteudo_url'];
        }
    }

    /**
     * Retorna a url
     * 
     * @access public
     * @return array $this->url
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getUrl()
    {
        return $this->_url;
    }
    
    public function getPost(){
        return $_POST;
    }

    /**
     * Se a ultima posição do array for vazia remove. 
     * Observação: o array é recebido pela função por referência
     * 
     * @access private
     * @param array $array
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function removeUltPosVazArr(&$explode)
    {
        if (end($explode) == null)
        {
            array_pop($explode);
        }
    }

    /**
     * Converte a url em array atravéz das '/' e remove posição
     * vazia caso tiver.
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function setExplode()
    {
        $this->_explode = explode('/', $this->_url);

        // Se a última posição do array for vazio remove, isso ocorre quando usuário digita / no final da url
        $this->removeUltPosVazArr($this->_explode);
    }

    /**
     * Seta o controller
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function setController()
    {
        $this->_controller = $this->_explode[0];
    }

    /**
     * Retorna o controller
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Seta a action
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function setAction()
    {
        $ac = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == 'index' ? "index_action" : $this->_explode[1]);
        if(!empty($ac)) {
            $ac = str_replace('-', '_', $ac);
        }
        $this->_action = $ac;
    }

    /**
     * Retorna a action
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Processa os parâmetros da url e seta na variável 
     * $this->_params
     * 
     * @access private
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function setParams()
    {
        // Removo o controller e a action do array porque já peguei
        unset($this->_explode[0], $this->_explode[1]);

        $i = 0;
        $ind = array();
        $value = array();

        if (!empty($this->_explode))
        {
            foreach ($this->_explode as $val)
            {
                if ($i % 2 == 0)
                {
                    $ind[] = $val;
                } else
                {
                    $value[] = $val;
                }

                $i++;
            }
        }

        if ((count($ind) == count($value)) && !empty($ind) && !empty($value))
        {
            $this->_params = array_combine($ind, $value);
        }
    }
    
    /**
     * Retorna parâmetros
     * 
     * @param boolean $name se true retorna string pelo índice senão retorno array com todos os dados
     * @return array com os parâmetros
     * @return string parâmetro
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getParams($name = false)
    {
        if ($name)
        {
            return $this->_params[$name];
        } else
        {
            return $this->_params;
        }
    }

    /**
     * Retorna parâmetros
     * 
     * @return string com os parâmetros separados por vírgula
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getParamsImplode()
    {
        if (isset($this->_params) && !empty($this->_params))
        {
            return implode(',', $this->params);
        } else
        {
            return '';
        }
    }
    
    public function getExplode(){
        return $this->_explode;
    }

    /**
     * Instância o objeto controller e chama o método action
     * 
     * @return string com os parâmetros separados por vírgula
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function run(){
        $controller_path = CONTROLLERS . $this->_controller . "Controller.php";
        if (!file_exists($controller_path)){
            die("O controller '{$this->_controller}' não existe!");
        } 
        else{
            require_once ($controller_path);

            $this->_controller = $this->_controller . "Controller";
            $app = new $this->_controller;
            $action = $this->_action;
            $controle = 'controleAction';

            if (!method_exists($app, $action)){
                die("A action '{$this->_action}' não existe!");
            } 
            else{
                $reflexao = new ReflectionClass($app);
                $metodo = $reflexao->getMethod($action);                
                
                // Verifico se a função está acessível private/public/protected
                if($metodo->isPublic()){
                   $app->$action(); 
                }
                else if($metodo->isProtected() && (strcmp($action, $controle) != 0)){
                    $app->$controle($action); 
                }
                else{
                    echo utf8_decode("<h1>Método não acessível</h1>");
                }		
            }
        }
    }
}