<?php

class TratamentoDeErro{
    private $sessionDadoErro = array();
    private $nomeSession;
    
    function __construct($nomeSession = null, Array $sessionDadoErro = null) {
        $this->criarSessionErro($nomeSession, $sessionDadoErro);
    }
    
    public function criarSessionErro($nomeSession = null, Array $sessionDadoErro = null){
        if($nomeSession == null && $sessionDadoErro == null)
            return;
        
        $this->nomeSession = $nomeSession;
        $this->sessionDadoErro = $sessionDadoErro;
        
        $_SESSION[$nomeSession] = $sessionDadoErro;        
    }
    
    public function isSessionErro($nomeSession){
        if(isset ($_SESSION[$nomeSession])){
            return true;
        }
        
        return false;
    }
    
    public function restaurarSessionErro($nomeSession){
        if($this->isSessionErro($nomeSession)){
            $this->nomeSession = $nomeSession;
            $this->sessionDadoErro = $_SESSION[$this->nomeSession];
            return $this->sessionDadoErro;
        }
        
        return null;
    }
    
    public function destruirSessionErro($nomeSession){
        if(isset ($_SESSION[$nomeSession])){
            unset ($_SESSION[$nomeSession]);
        }
    }
    
    public function getSessionDadoErro() {
        return $this->sessionDadoErro;
    }

    public function setSessionDadoErro($sessionDadoErro) {
        $this->sessionDadoErro = $sessionDadoErro;
    }

    public function getNomeSession() {
        return $this->nomeSession;
    }

    public function setNomeSession($nomeSession) {
        $this->nomeSession = $nomeSession;
    }
}
?>
