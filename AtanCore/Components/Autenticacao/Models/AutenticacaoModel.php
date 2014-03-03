<?php

Class AutenticacaoModel extends ModelAtan{
    
    private $login;
    private $senha;
    
    function __construct() {
        parent::__construct('usuario');
        $this->login = "robson";
        $this->senha = "123";
    }
    
    public function tratarArrayInsertDados(&$dados = null){
        /* transformar senha em md5  Depois garantir outra protecao em cima do md5 para protecao em banco */
        if(!is_array($dados) || empty ($dados) || !isset ($dados['senha']))
            return false;
        
        $dados['senha'] = md5($dados['senha']);
        return true;
    }
    
    public function insert($dados = array()) {
        if(!$this->tratarArrayInsertDados($dados))
            return false;
        
        return parent::insert($dados);
    }
    
    public function validaLoginBanco($login, $senha){
        /* Migue do batman, pois ainda nao temos banco */
        if($this->login == $login && $this->senha == $senha){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }
}

?>
