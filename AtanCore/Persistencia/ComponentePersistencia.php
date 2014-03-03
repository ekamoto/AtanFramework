<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Description of Componente
 *
 * @author Leandro Shindi Ekamoto
 */
class ComponentePersistencia extends ObjetoRelacional {
    
    protected $id_componente;
    protected $nome;
    protected $data_criacao;

    private $arrayObjeto = array();
    private $model;
    private $persistente;

    public function __construct($nome = false, $id_componente = false) {
        parent::__construct($this);

        if($nome != false){
            $this->nome = $nome;
        }
        
        if($id_componente != false){
            $this->id_componente = $id_componente;
        }
        
        $this->model = new ObjetoRelacionalModel('componente');
        $this->persistente = false;
    }

    public function inserir($commit = true) {
        if ($this->persistente)
            return false;
        
        return $this->model->inserirObjeto($this, $commit);
    }

    public function procurar($dadosImportantesCorrespondetes = false) {
        $this->persistente = false;
        return $this->model->procurarEpreencherObjeto($this, $dadosImportantesCorrespondetes);
    }

    public function procurarObjetos($where = false, $whereDados = array()) {
        $this->persistente = false;
        
        return $this->model->procurarObjetos($this, $where, $whereDados);
    }

    public function atualizar($commit=true) {
        if (!$this->persistente)
            return false;

        return $this->model->atualizarObjeto($this, $commit);
    }

    public function deletar($commit=true) {
        if (!$this->persistente)
            return false;

        return $this->model->deletarObjeto($this, $commit);
    }

    public function getArrayObjetos() {
        return $this->arrayObjeto;
    }

    public function getPersistente() {
        $this->persistente;
    }

    public function setArrayObjetos($arrayObjetos) {
        $this->arrayObjeto = $arrayObjetos;
    }

    public function setPersistente($persistente) {
        $this->persistente = $persistente;
    }
    
    public function getId_componente() {
        return $this->id_componente;
    }

    public function setId_componente($id_componente) {
        $this->id_componente = $id_componente;
    }

    public function getNome() {
        return $this->nome;
    }
    public function getData_criacao() {
        return $this->data_criacao;
    }

    public function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    public function setNome($nome) {
        $this->nome = $nome;
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