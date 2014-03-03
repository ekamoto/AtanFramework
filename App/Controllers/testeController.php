<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

class testeController extends ControllerAtan 
{
    public $nome_controller = 'Teste';

    public function index_action() 
    {
        echo utf8_encode('Locura locura');
    }
    
    // Método action que pode ser acessado pelo cliente
    public function getNome() {
        echo "leandro";
    }
    
    // Método que não pode ser acessado pelo usuário pela url
    private function getEmail() {
        echo "ekamoto.leandro@gmail.com";
    }
}