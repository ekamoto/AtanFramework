<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
   die("Acesso negado");
}

/**
 * Description of indexController
 *
 * @author Leandro Shindi Ekamoto
 */
class indexController extends ControllerAtan {

   // Nome do controlador
   public $nome_controller = 'Index';

   public function __construct() {
      parent::__construct();
   }

   public function index_action() {
      $parametros = array();
      $parametros['template'] = "atan_tecnologia";
      $parametros['pasta_view'] = "Index";
      $this->view("index", $parametros);
   }

   public function usuario_nao_conhecido() {
      if ($this->isLogadoUsuario()) {

         $this->redirect(null, "/home");
      } else if ($this->isTratamentoErro("logar")) {

         $dadoErro = $this->restaurarTratamentoErro("logar");
         $this->destruirTratamentoErro("logar");

         $this->view("login", $dadoErro);
      } else {
         $this->redirect();
      }
   }

   public function falta_de_dado() {
      if ($this->isLogadoUsuario()) {
         $this->redirect(null, "/home");
      } else if ($this->isTratamentoErro("logar")) {

         $dadoErro = $this->restaurarTratamentoErro("logar");
         $this->destruirTratamentoErro("logar");

         $this->view("login", $dadoErro);
      } else {
         $this->redirect();
      }
   }

   public function deslogar() {
      if ($this->isLogadoUsuario()) {
         $this->deslogarUsuario();
      }

      if ($this->isTratamentoErro("logar")) {
         $this->destruirTratamentoErro("logar");
      }

      $this->redirect();
   }

   protected function controleAction($proximaAction) {
      
   }

}