<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Description of ObjetoRelacionalModel
 *
 * @author robson
 */

class ObjetoRelacionalModel extends ModelAtan{
    
    public function __construct($tabela) {
        parent::__construct($tabela);
    }
    
    private function buscarCamposDaTabela() {
        $this->conectar(); 
        
        $buscaCampo = $this->db->prepare("SHOW COLUMNS FROM {$this->_tabela} FROM atan");
        $buscaCampo->execute();
        return $buscaCampo->fetchAll(PDO::FETCH_ASSOC);
    }

    private function retornarIndicesTabela($tipo=false) {
        $this->conectar();
        
        $camposTabela = $this->buscarCamposDaTabela();
        
        if (count($camposTabela) <= 0)
            return false;

        $indice = array();
        foreach ($camposTabela as $key => $value) {
            if(!empty($value['Key'])) {
                if ($tipo) {
                    if($value['Key'] == $tipo) {
                        $indice[$value['Field']] = '';
                    }
                } else {
                    $indice[$value['Field']] = '';
                }   
            }
        }
        return (empty($indice) ? false : $indice);
    }

    public function procurarEpreencherObjeto(ObjetoRelacional $objeto, $procurarTodosIndices = false) {
        $this->conectar();
        
        $arrayCamposValores = $this->procurarObjeto($objeto, $procurarTodosIndices);

        if ($arrayCamposValores == false)
            return;

        return $objeto->preecherObjeto($arrayCamposValores);
    }
    
    public function procurarObjetos(ObjetoRelacional $objeto, $where = false, $whereDados = array()){
        if(is_bool($where) || empty($whereDados)){
            return $objeto->preecherObjeto($this->read());
        }
        else{
            return $objeto->preecherObjeto($this->read($where, $whereDados));
        }
    }

    private function procurarObjeto(ObjetoRelacional $objeto, $procurarTodosIndices = false) {
        $this->conectar();
        $arrayAtributos = $objeto->atributoObjeto();

        $indice = $this->retornarIndicesTabela();
        if (is_bool($indice))
            return false;
        
        $where = '';
        $whereDados = array();
        foreach ($indice as $ka => $va) {
            if (isset($arrayAtributos[$ka]) && !empty($arrayAtributos[$ka])) {
                if (empty($where)) {
                    $where = $ka . ' = ?';
                } else {
                    $where .= ' AND ' . $ka . ' = ?';
                }

                $whereDados[] = $arrayAtributos[$ka];
                if (!$procurarTodosIndices) {
                    break;
                }
            }
        }

        if (empty($where) || empty($whereDados))
            return;

        $buscarObjeto = $this->read($where, $whereDados);

        if (!is_array($buscarObjeto) && count($buscarObjeto) <= 0)
            return false;

        return $buscarObjeto;
    }

    public function inserirObjeto(ObjetoRelacional $objeto, $commit=true) {
        $this->conectar();
        
        $camposTabela = $this->buscarCamposDaTabela();
        $atributosObjeto = $objeto->atributoObjeto();

        $camposNullNo = array();
        foreach ($camposTabela as $ka => $va) {
            $camposNullNo[$va['Field']] = (strcmp($va['Null'], 'NO') != 0 ? '' : $va['Null']);
            if (strcmp($va['Extra'], 'auto_increment') == 0) {
                $camposNullNo[$va['Field']] = '';
            }
        }

        $dados = array();
        foreach ($camposNullNo as $key => $value) {
            if (!empty($value)) {
                if (is_null($atributosObjeto[$key])) {
                    echo 'Caro, o campo ' . $key . ' é do tipo Not Null, por favor, preecha-o e seja feliz!';
                    return false;
                } else {
                    $dados[$key] = $atributosObjeto[$key];
                }
            } else {
                if (!empty($atributosObjeto[$key])) {
                    $dados[$key] = $atributosObjeto[$key];
                }
            }
        }

        return $this->insert($dados, $commit);
    }

    public function atualizarObjeto(ObjetoRelacional $objeto, $commit=true) {
        $this->conectar();
        
        $arrayCamposValores = $this->procurarObjeto($objeto);
        if (!is_array($arrayCamposValores))
            return false;

        $arrayAtributos = $objeto->atributoObjeto();

        $indice = $this->retornarIndicesTabela('PRI');
        if (is_bool($indice))
            return false;

        $dados = array();
        $whereDados = array();
        $where = '';
        foreach ($arrayCamposValores as $ka => $va) {
            foreach ($va as $kb => $vb) {
                if (strcmp($va[$kb], $arrayAtributos[$kb]) != 0) {
                    $dados[$kb] = $arrayAtributos[$kb];
                }

                if (isset($indice[$kb])) {
                    if (empty($where)) {
                        $where = $kb . ' = ?';
                    } else {
                        $where .= ' AND ' . $kb . ' = ?';
                    }

                    $whereDados[] = $arrayAtributos[$kb];
                }
            }
        }
        if (empty($dados) && empty($whereDados) && empty($where)){
            return false;   
        }

        return $this->update($dados, $where, $whereDados, $commit);
    }

    public function deletarObjeto(ObjetoRelacional $objeto, $commit=true) {
        $this->conectar();
        
        
        $arrayAtributos = $objeto->atributoObjeto();
        $indice = $this->retornarIndicesTabela('PRI');
        if (is_bool($indice))
            return false;

        $whereDados = array();
        $where = '';
        foreach ($indice as $key => $value) {
            if(isset($arrayAtributos[$key]) && !empty($arrayAtributos[$key])){
                if(empty($where)){
                    $where = $key . ' = ?';
                }
                else{
                    $where .= ' AND ' . $key . ' = ?';
                }
                $whereDados[] = $arrayAtributos[$key];
            }
        }
        
        if(empty($whereDados) || empty($where))
            return false;
        
        return $this->delete($where, $whereDados, $commit);
    }    
}
?>