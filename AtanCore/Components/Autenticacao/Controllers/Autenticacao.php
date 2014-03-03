<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Class Autenticacao{
    
    private $permissao;
    private $permissaoLogin;
    
    function __construct($permissao = true, $permissaoLogin = false) {
        $this->permissao = $permissao;
    }
    
    public function isUsuario($login, $senha, $email = false){
        $usuario = new UsuarioPersistencia($login);
        if(!$usuario->procurar())
            return false;
        
        if(strcmp($usuario->getSenha(), $this->segurancaSenha($senha))  == 0 ){
            $this->ativandoSession($usuario->getLogin());
            return true;
        }
        
        return false;
    }
    
    private function ativandoSession($login){
        $_SESSION["login"] = $login;
    }
    
    public function isSessionAtiva(){
        if(isset ($_SESSION["login"]) && !empty ($_SESSION["login"]))
            return true;
        else
            return false;
    }
    
    public function sessionDestruir(){
        session_destroy();
    }
    
    public function getPermissao() {
        return $this->permissao;
    }

    public function setPermissao($permissao) {
        $this->permissao = $permissao;
    }
    
    public function getPermissaoLogin() {
        return $this->permissaoLogin;
    }
    

    public function setPermissaoLogin($permissaoLogin) {
        $this->permissaoLogin = $permissaoLogin;
    }
    
    public function segurancaSenha($senha){
        return md5($senha . $senha);
    }
}
?>
