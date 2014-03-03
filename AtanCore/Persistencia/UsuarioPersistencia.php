<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Classe para gerenciar o usuario
 *
 * @author Robson Lopes de Oliveira
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
Class UsuarioPersistencia extends ObjetoRelacional {
    
    protected $id_usuario;
    protected $login;
    protected $senha;
    protected $nome;
    protected $sobrenome;
    protected $email;
    protected $ativado;
    protected $data_nascimento;
    protected $data_ativacao;

    private $arrayObjeto = array();
    private $model;
    private $persistente;
    
    public function __construct($login = false, $email = false, $senha = false) {
        parent::__construct($this);
        
        if($login)
            $this->login = $login;
        
        if($email)
            $this->email = $email;
        
        if($senha)
            $this->senha = $senha;
        
        $this->model = new ObjetoRelacionalModel('usuario');
        $this->persistente = false;
    }
    
    public function alterarSenha($senha){
        $this->senha = $senha;
    }
    
    public function procurar($dadosImportantesCorrespondetes = false){
        $this->persistente = false;
        return $this->model->procurarEpreencherObjeto($this, $dadosImportantesCorrespondetes);
    }
    
    public function procurarObjetos($where = false, $whereDados = array()) {
        $this->persistente = false;
        return $this->model->procurarObjetos($this, $where, $whereDados);
    }
    
    public function inserir(){
        if($this->persistente){
            return false;
        }
        $id_inserido = $this->model->inserirObjeto($this);
        if($id_inserido){
            $this->setId_usuario($id_inserido);
            return true;
        } else {
            return false;
        }
    }
    
    public function atualizar(){
        if(!$this->persistente)
            return false;
        
        return $this->model->atualizarObjeto($this);
    }
    
    public function deletar() {
        if(!$this->persistente)
            return false;
        
        if($this->model->deletarObjeto($this)){
            $this->persistente = false;
            return true;
        }

        return false;
    }
    
    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSobrenome() {
        return $this->sobrenome;
    }

    public function setSobrenome($sobrenome) {
        $this->sobrenome = $sobrenome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getAtivado() {
        return $this->ativado;
    }

    public function setAtivado($ativado) {
        $this->ativado = $ativado;
    }
    
    public function getData_nascimento() {
        return $this->data_nascimento;
    }

    public function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }
    
    public function getData_ativacao() {
        return $this->data_ativacao;
    }

    public function setData_ativacao($data_ativacao) {
        $this->data_ativacao = $data_ativacao;
    }

    public function getArrayObjetos() {
        return $this->arrayObjeto;
    }

    public function setArrayObjetos($arrayObjetos) {
        $this->arrayObjeto = $arrayObjetos;
    }

    public function getPersistente() {
        return $this->persistente;
    }

    public function setPersistente($persistente) {
        $this->persistente = $persistente;
    }
    
    public function setDados($dados) {
        if(is_array($dados) && !empty($dados)) {
            foreach ($dados as $key => $dado) {
                $this->$key = $dados[$key];
            }
        }
    }
}
?>
