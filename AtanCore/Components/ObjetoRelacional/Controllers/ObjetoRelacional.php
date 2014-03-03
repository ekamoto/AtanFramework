<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Description of ObjetoRelacional
 *
 * @author robson
 */
abstract class ObjetoRelacional {

    private $objetoRelacionar;
    private $nomeObjeto;

    public function __construct($objeto = null) {
        if (is_object($objeto))
            $this->objetoRelacionar = $objeto;
        else {
            $this->objetoRelacionar = false;
        }
        
        $this->nomeObjeto = ($this->objetoRelacionar != false ? get_class($this->objetoRelacionar) : get_class());
    }

    public abstract function procurar($dadosImportantesCorrespondetes = false);
    public abstract function procurarObjetos($where = false, $whereDados = array());
    public abstract function inserir();
    public abstract function deletar();
    public abstract function atualizar();
    public abstract function getPersistente();
    public abstract function setPersistente($persistente);
    public abstract function setArrayObjetos($arrayObjetos);
    public abstract function getArrayObjetos();

    public function atributoObjeto() {
        return get_object_vars($this);
    }

    public function preecherObjeto($arrayCamposValores){
        if (!is_array($arrayCamposValores) || empty($arrayCamposValores))
            return false;
        
        if(count($arrayCamposValores) > 1){
            return $this->preecherObjetoArray($arrayCamposValores);
        }
        else{
            return $this->preecherObjetoUnico($arrayCamposValores);
        }
    }
    
    private function preecherObjetoArray($arrayCamposValores) {
        $arrayObjeto = array();
        
        foreach ($arrayCamposValores as $ka => $va) {
            eval('$objeto = new $this->nomeObjeto;');
            
            foreach ($va as $kb => $vb) {
                $kb = strtoupper($kb[0]) . substr($kb, 1);
                $nomeFuncao = $this->nomeObjeto . '::set' . $kb;
                if (is_callable($nomeFuncao)) {
                    eval('$objeto->' . 'set' . $kb . '($vb);');
                } else {
                    echo 'Caro, reveja os seus conceitos, pois a função (' . $this->nomeObjeto . '::set' . $kb . ') não existe.';
                    return false;
                }
            }
            
            eval('$objeto->setPersistente(true);');
            $arrayObjeto[] = $objeto;
        }
        
        eval('$this->objetoRelacionar->setArrayObjetos($arrayObjeto);');
        return true;
    }

    private function preecherObjetoUnico($arrayCamposValores) {
        
        foreach ($arrayCamposValores as $ka => $va) {
            foreach ($va as $kb => $vb) {
                $kb = strtoupper($kb[0]) . substr($kb, 1);
                $nomeFuncao = $this->nomeObjeto . '::set' . $kb;
                if (is_callable($nomeFuncao)) {
                    eval('$this->objetoRelacionar->' . 'set' . $kb . '($vb);');
                } else {
                    echo 'Caro, reveja os seus conceitos, pois a função (' . $this->nomeObjeto . '::set' . $kb . ') não existe.';
                    return false;
                }
            }
            eval('$this->objetoRelacionar->setPersistente(true);');
        }

        return true;
    }
    
    public function arrayObjetoProximo(){
        $arrayObjetos = array();
        eval('$arrayObjetos = $this->objetoRelacionar->getArrayObjetos();');
        
        foreach ($arrayObjetos as $key => $value) {
            $objeto = $value;
            unset($arrayObjetos[$key]);  
            eval('$this->objetoRelacionar->setArrayObjetos($arrayObjetos);');
            return $objeto;
        }
        return false;
    }
}
?>
