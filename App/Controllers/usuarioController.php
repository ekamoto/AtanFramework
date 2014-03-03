<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
* Classe para cadastro de usuário
*
* @author Leandro Shindi Ekamoto
* @version 1.0
* @copyright 2012 Atan tecnologia
* 
*/
class usuarioController extends ControllerAtan 
{
    public $nome_controller = 'Usuario';

    public function index_action() 
    {
        $this->view('index');
    }

    protected function controleAction($proximaAction) {
        
    }

}