<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Classe para gerar log . . .
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
class Log
{

    /**
     * Variável que contém a data atual
     * @access private
     * @name $nome_controller
     * 
     */
    private $data;

    /**
     * Dados do componente
     * @access private
     * @name $nome_controller
     * 
     */
    private $dados_log;

    /**
     * Variável que contém a hora atual
     * @access private
     * @name $nome_controller
     * 
     */
    private $hora;

    /**
     * Construtor, define a função que vai processar o log
     * 
     * @access public
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function __construct($dados_log)
    {
        $this->data = date('d-m-Y');
        $this->hora = date('H:i:s');
        $this->dados_log = $dados_log;
        set_error_handler(array($this, 'processaErroPHP'));
    }

    /**
     * Função que processa Log
     * 
     * @access public
     * @param string $errno código do erro
     * @param string $msg mensagem do erro
     * @param string $errfile arquivo
     * @param string $errline linha onde ocorreu o erro
     * @param array $errstr dados da página POST/GET ...
     * @param array $erro dados do erro
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function processaErroPHP($errno, $msg, $errfile, $errline, $errstr)
    {
        $niveis_erro = array(
            1 => array('nivel' => 'E_ERROR', 'descricao' => 'Erros em tempo de execução fatais. Estes indicam erros que não podem ser recuperados, como problemas de alocação de memória. A execução do script é interrompida.'),
            2 => array('nivel' => 'E_WARNING', 'descricao' => 'Avisos em tempo de execução (erros não fatais). A execução do script não é interrompida.'),
            4 => array('nivel' => 'E_PARSE', 'descricao' => 'Avisos em tempo de execução (erros não fatais). A execução do script não é interrompida.'),
            8 => array('nivel' => 'E_NOTICE', 'descricao' => 'Notícia em tempo de execução. Indica que o script encontrou alguma coisa que pode indicar um erro, mas que também possa acontecer durante a execução normal do script.'),
            16 => array('nivel' => 'E_CORE_ERROR', 'descricao' => 'Erro fatal que acontece durante a inicialização do PHP. Este é parecido com E_ERROR, exceto que é gerado pelo núcleo do PHP.'),
            32 => array('nivel' => 'E_CORE_WARNING', 'descricao' => 'Avisos (erros não fatais) que aconteçam durante a inicialização do PHP. Este é parecido com E_WARNING, exceto que é gerado pelo núcleo do PHP.'),
            64 => array('nivel' => 'E_COMPILE_ERROR', 'descricao' => 'Erro fatal em tempo de compilação. Este é parecido com E_ERROR, exceto que é gerado pelo Zend Scripting Engine.'),
            128 => array('nivel' => 'E_COMPILE_WARNING', 'descricao' => 'Aviso em tempo de compilação. Este é parecido com E_WARNING, exceto que é geredo pelo Zend Scripting Engine.'),
            256 => array('nivel' => 'E_USER_ERROR', 'descricao' => 'Erro gerado pelo usuário. Este é parecido com E_ERROR, exceto que é gerado pelo código PHP usando a função trigger_error().'),
            512 => array('nivel' => 'E_USER_WARNING', 'descricao' => 'Aviso gerado pelo usuário. Este é parecido com E_WARNING, exceto que é gerado pelo código PHP usando a função trigger_error().'),
            1024 => array('nivel' => 'E_USER_NOTICE', 'descricao' => 'Notícia gerada pelo usuário. Este é parecido com E_NOTICE, exceto que é gerado pelo código PHP usando a função trigger_error().'),
            2047 => array('nivel' => 'E_ALL', 'descricao' => 'Todos os erros e avisos, como suportado, exceto do nível E_STRICT.'),
            2048 => array('nivel' => 'E_STRICT', 'descricao' => 'Nóticias em tempo de execução. Permite ao PHP sugerir modificações em seu código para segurar melhor interoperabilidade e compatibilidade futura do seu código.')
        );

        $erro = array();
        $erro = $niveis_erro[$errno];
        $erro['nivel'] = $erro['nivel'];
        $erro['descricao'] = $erro['descricao'];
        $erro['errno'] = $errno;
        $erro['msg'] = $msg;
        $erro['errfile'] = $errfile;
        $erro['errline'] = $errline;
        $erro['errstr'] = $errstr;
        $this->montaMsg($erro);
    }

    /**
     * Função que mosta a mensagem que vai ser gravada no arquivo
     * 
     * @access public
     * @param array $erro dados do erro
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    private function montaMsg($erro)
    {
        $msg = "Erro: {$erro['errno']}\nNível: {$erro['nivel']}\nDescrição: {$erro['descricao']}\nData: {$this->data} {$this->hora}\nDescrição: {$erro['msg']}\nLinha: {$erro['errline']}\nArquivo: {$erro['errfile']} \n\n";

        if (!$this->dados_log['gravar'])
        {
            echo $msg;
            die;
        } else
        {
            $this->escreveLog($msg);
        }
    }

    /**
     * Função que abre/escreve no arquivo
     * 
     * @access public
     * @param string $msg mensagem de erro
     * @author Leandro Shindi Ekamoto
     * @version 1.0
     * @copyright 2012 Atan tecnologia
     * 
     */
    public function escreveLog($msg)
    {
        $arquivo = LOG . "/log[{$this->data}].txt";
        
        // Abre arquivo
        $fp = fopen($arquivo, 'a');

        // Escreve
        $escreve = fwrite($fp, $msg);

        // Fecha
        fclose($fp);
        
        chmod($arquivo, 0777);
    }

}