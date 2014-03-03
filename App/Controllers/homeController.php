<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Controlador da página home
 *
 * @author Leandro Shindi Ekamoto
 */
class homeController extends ControllerAtan {

    // Nome do controlador
    public $nome_controller = 'Home';

    public function index_action() {
        if ($this->isPost()) {
            if (!isset($this->post['login']) || !isset($this->post['senha']) || empty($this->post['login']) || empty($this->post['senha'])) {
                $dadoErro = array("problema" => "falta de dados.", "mensagem" => "preencha todos os campos, por favor.");
                $this->criarTratamentoErro("logar", $dadoErro);
                $this->redirect(null, "/index/falta-de-dado");
            } else if ($this->logarUsuario($this->post['login'], $this->post['senha'])) {
                // Injetando view home no template1
                $this->view("home", array("template" => "template1"));
            } else {
                $dadoErro = array("problema" => "Usuário não Encontrado", "mensagem" => "Tente Novamente");
                $this->criarTratamentoErro("logar", $dadoErro);
                $this->redirect(null, "/index/usuario-nao-conhecido");
            }
        } else if ($this->isLogadoUsuario()) {
            // Injetando view home no template1
            $this->view("home", array("template" => "template1"));
        } else {
            $this->redirect();
        }
    }
}