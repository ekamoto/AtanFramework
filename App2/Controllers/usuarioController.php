<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarioController
 *
 * @author leandro.ekamoto
 */
class usuarioController extends ControllerAtan {

    public function index_action() {
        echo "Quando não é passado a action ele entra aqui";
    }

    public function listagemTeste() {
        $this->view("viewTeste", array("pasta_view" => "Usuario", "template" => "Template1"));
    }

    protected function controleAction($proximaAction) {
        
    }

}