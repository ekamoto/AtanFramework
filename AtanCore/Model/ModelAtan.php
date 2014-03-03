<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Classe genérica para os models
 * Padrão table data gateway
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
abstract class ModelAtan {

    /**
     * Instância PDO
     * @access private
     * @name $db
     * 
     */
    protected $db;

    /**
     * Nome da tabela no banco de dados que o modelo representará
     * @access private
     * @name $_tabela
     * 
     */
    protected $_tabela;
    
    /**
     * Variável para controle de conexão
     * @access private
     * @name $conectado
     * 
     */
    protected $conectado;

    /**
     * Construtor, cria uma instânci PDO
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function __construct($tabela) {
        $this->conectado = false;
        try {
            $this->_tabela = $tabela;
            // Aqui vai a classe de conexões
            //$this->db = new PDO('mysql:host=localhost;dbname=atan', 'root', "root");
        } catch (PDOException $exc) {
            //Lembrar: Tirar isso aqui, pois mostra todos os dados de conexao ao banco caso haja falha
            //echo 'Falha: ' . $exc->getTraceAsString();
            //echo 'Erro: ' . $exc->getMessage();
        }
    }
    
    /**
     * Destrutor,
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function __destruct() {
        if($this->conectado) {
            $this->desconectar();    
        }
    }

    /**
     * Conecta com o banco de dados
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function conectar() {
        if (!$this->conectado) {
            try {
                $this->db = new PDO('mysql:host=127.0.0.1;dbname=atan', 'root', "10203040");
                $this->conectado = true;
            } catch (PDOException $exc) {
                die('Problemas ao conectar o banco');
            }
        }
    }
    
    /**
     * Desconecta com o banco de dados
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function desconectar() {
        if ($this->conectado) {
            $this->db = NULL;
            $this->conectado = false;
        }
    }
    
    /**
     * Retorno a conexão do banco
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getDb() {
        if (!empty($this->db)) {
            return $this->db;
        }
        return false;
    }

    /**
     * Seta conexão do banco
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function setDb($db) {
        $this->db = $db;
    }

    /**
     * Gerar string com interrogações separados por vírgula
     * 
     * @access public
     * @param n $n int quantidade de pontos de interrogação
     * @return string com interrogações separadas por vírgula
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    function gerarInterrogacao($n = false) {
        if ($n && ($n > 0) && !empty($n) && is_int($n)) {
            $array = array();
            for ($i = 0; $i < $n; $i++) {
                $array[] = '?';
            }
            return implode(',', $array);
        }
        return false;
    }

    /**
     * Gerar sql para insert
     * 
     * @access public
     * @param array $dados array com dados para inserir
     * @return sql preparado
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function getInsertPrepareStatement($campos = false) {
        if ($campos) {
            $qtd_campos = count($campos);
            if ($qtd_campos > 0) {
                $keys = array();
                $keys = array_keys($campos);
                $nome_campos = implode(',', $keys);

                $interrogacoes = $this->gerarInterrogacao($qtd_campos);

                return "INSERT INTO {$this->_tabela} ({$nome_campos}) VALUES ({$interrogacoes})";
            }
            return false;
        }
        return false;
    }

    public function getUpdatePrepareStatement($campos = false, $where = false, $whereDados = false) {
        if ((!is_array($campos) && empty($campos)) || (empty($where) && !is_string($where)) || (!is_array($whereDados) && empty($whereDados))){
            return false;
        }
            
        $qtd_campos = count($campos);
        if ($qtd_campos <= 0) {
            return false;
        }
            
        $keys = array();
        $keys = array_keys($campos);
        $nomes_campos = implode('= ?, ', $keys);
        $nomes_campos .= ' = ?';

        $sql = "UPDATE {$this->_tabela} SET $nomes_campos WHERE {$where}";
        
        return $sql;
    }

    public function getSelectPrepareStatement($where = false) {
        if ($where == false)
            return "SELECT * FROM {$this->_tabela}";
        else
            return "SELECT * FROM {$this->_tabela} WHERE {$where}";
    }

    public function getDeletePrepareStatement($where = false) {
        if ($where == false)
            return "DELETE FROM {$this->_tabela}";
        else
            return "DELETE FROM {$this->_tabela} WHERE {$where}";
    }

    /**
     * Ajusta o array de dados
     * 
     * @access public
     * @param array $dados array com dados para inserir
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function valoresArray($dados = null) {
        $arrRetorno = array();

        if (is_array($dados) && !empty($dados)) {
            foreach ($dados as $value) {
                $arrRetorno[] = $value;
            }
        } else {
            return false;
        }

        return $arrRetorno;
    }

    /**
     * Insert genérico
     * 
     * @access public
     * @param array $dados array com dados para inserir
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function insert($dados = array(), $commit=true) {
        if (is_array($dados) && !empty($dados)) {
            // Gera sql insert
            $sql = $this->getInsertPrepareStatement($dados);
            if ($sql) {
                try {
                    if(!$this->conectado) {
                        $this->conectar();    
                    }
                    $this->db->beginTransaction();
                    $statemente = $this->db->prepare($sql);
                    $array_valores = $this->valoresArray($dados);
                    $statemente->execute($array_valores);
                    
                    if($commit) {
                        $this->db->commit();
                    } else {
                        return print_r($this->db->errorInfo());
                        $this->db->rollBack();
                    }
                    
                    return $this->db->lastInsertId();
                } catch (PDOException $exc) {
                    echo 'Erro: ' . $exc->getMessage();
                }
            }
        }

        return false;
    }

    /**
     * Update genérico
     * 
     * @access public
     * @param array $dados dados para atualizar
     * @param string $where opção where do select
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function update($dados, $where, $whereDados, $commit=true) {
        
        if ((!is_array($dados) && empty($dados)) || (empty($where) && !is_string($where)) || (!is_array($whereDados) && empty($whereDados))){
            return false;   
        }
            
        $sql = $this->getUpdatePrepareStatement($dados, $where, $whereDados);
       
        if (!$sql)
            return false;

        try {
            if(!$this->conectado) {
                $this->conectar();    
            }
            $statemente = $this->db->prepare($sql);
            $array_valores = array_merge($this->valoresArray($dados), $whereDados);
            $this->db->beginTransaction();
            $statemente->execute($array_valores);
            if($commit) {
                $this->db->commit();
            } else {
                return print_r($this->db->errorInfo());
                $this->db->rollBack();
            }
            
        } catch (Exception $exc) {
            echo 'Erro: ' . $exc->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Read genérico
     * 
     * @access public
     * @param string $where opção where do select
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function read($where = false, $whereDados = array()) {
        $sql = $this->getSelectPrepareStatement($where);

        try {
            if(!$this->conectado) {
                $this->conectar();    
            }
            $statemente = $this->db->prepare($sql);
            if (empty($whereDados)) {
                $statemente->execute();
            } else {
                $statemente->execute($whereDados);
            }

            return $statemente->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            echo 'Erro: ' . $exc->getMessage();
        }

        return false;
    }

    /**
     * Read genérico
     * 
     * @access public
     * @param string $where opção where do select
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function delete($where = false, $whereDados = array(), $commit=true) {
        $sql = $this->getDeletePrepareStatement($where);

        try {
            $statemente = $this->db->prepare($sql);
            if(!$this->conectado) {
                $this->conectar();    
            }
            $this->db->beginTransaction();
            if (empty($whereDados)) {
                $statemente->execute();
            } else {
                $statemente->execute($whereDados);
            }
            if($commit) {
                $this->db->commit();
            } else {
                $this->db->rollBack();
                return print_r($this->db->errorInfo());
            }

            return true;
        } catch (Exception $exc) {
            echo 'Erro: ' . $exc->getMessage();
        }

        return false;
    }
}
