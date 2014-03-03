<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Description of Grupo
 *
 * @author robson
 */
class GrupoPersistencia extends ObjetoRelacional {
    
    protected $id_grupo;
    protected $nome;

    private $arrayObjeto = array();
    private $model;
    private $persistente;

    public function __construct($nome = false, $id_grupo = false) {
        parent::__construct($this);

        if($nome != false){
            $this->nome = $nome;
        }
        
        if($id_grupo != false){
            $this->id_grupo = $id_grupo;
        }
        
        $this->model = new ObjetoRelacionalModel('grupo');
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
    
    public function getId_grupo() {
        return $this->id_grupo;
    }

    protected function setId_grupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
}

?>
