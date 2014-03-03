<?php

/**
 * Description of Grupo
 *
 * @author robson
 */
class UsuarioGrupoPersistencia extends ObjetoRelacional {

    protected $id_grupo;
    protected $id_usuario;
    protected $id_usuario_grupo;
    private $arrayObjeto = array();
    private $model;
    private $persistente;

    public function __construct($id_usuario = false, $id_grupo = false) {
        parent::__construct($this);

        if ($id_usuario != false) {
            $this->id_usuario = $id_usuario;
        }

        if ($id_grupo != false) {
            $this->id_grupo = $id_grupo;
        }

        $this->model = new ObjetoRelacionalModel('usuario_grupo');
        $this->persistente = false;
    }

    public function inserir() {
        if ($this->persistente)
            return false;

        return $this->model->inserirObjeto($this);
    }

    public function procurar($dadosImportantesCorrespondetes = false) {
        $this->persistente = false;
        return $this->model->procurarEpreencherObjeto($this, $dadosImportantesCorrespondetes);
    }

    public function procurarObjetos($where = false, $whereDados = array()) {
        $this->persistente = false;

        return $this->model->procurarObjetos($this, $where, $whereDados);
    }

    public function atualizar() {
        if (!$this->persistente)
            return false;

        return $this->model->atualizarObjeto($this);
    }

    public function deletar() {
        if (!$this->persistente)
            return false;

        return $this->model->deletarObjeto($this);
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

    public function setId_grupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }
    public function getId_usuario_grupo() {
        return $this->id_usuario_grupo;
    }

    public function setId_usuario_grupo($id_usuario_grupo) {
        $this->id_usuario_grupo = $id_usuario_grupo;
    }


}

?>