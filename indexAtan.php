<?php
/**
 * Desenvolvido por Atan tecnologia 06-09-2012 
 * 
 *  
 */

mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");
ob_start("mb_output_handler");

$chave_md5 = (date('d')+12)."AtantecnologiaRf587%".(date('m')+9).(date('Y')+1);
$hashIdentificaIndexAtan = md5($chave_md5);

// Multi-aplicação
$aplicacoes = array('1' => 'App',
    '2' => 'App2',
    '3' => 'App3');

$pasta_projeto = 'atan';

$app = $aplicacoes['2'];

define('CONTROLLERS', "{$app}/Controllers/");
define('VIEWS', "{$app}/Views/");
define('MODELS', "{$app}/Models/");
define('HELPERS_CORE', 'AtanCore/Helpers/');
define('HELPERS_APP', "{$app}/Views/Helpers/");
define('MODULO_CORE', 'AtanCore/Components/');
define('PERSISTENCIA_CORE', 'AtanCore/Persistencia/');
define('PERSISTENCIA_APP', "{$app}/Persistencia/");
define('INCLUDES_CORE', 'AtanCore/Includes/');
define('COMPONENTS_APP', "{$app}/Controllers/Components/");
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('MODEDEBUG', true);

// Observação eliminar essa linha, só está sendo utilizado no login.
define('LAYOUT', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pasta_projeto . '/' . $app . '/Views/Layout');
define('TEMPLATE', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pasta_projeto . '/' . $app . '/Views/Template');
define('CSS', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pasta_projeto . '/' . $app . '/web-files/css');
define('LIB', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pasta_projeto . '/' . $app . '/web-files/lib');
define('LOG', "{$app}/Log");

// Caminho absoluto do projeto
define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $pasta_projeto);

// Adicionando System
require_once 'AtanCore/System.php';

// Adicionando Controller do coreAtan
require_once 'AtanCore/Controller/ControllerAtan.php';

// Adicionando Model do coreAtan
require_once 'AtanCore/Model/ModelAtan.php';

spl_autoload_register(function($file) {
    // Pastas dos controllers do componente do core
    $arquivo_comp_contr_core = MODULO_CORE . $file . "/Controllers/" . $file . ".php";

    // Pastas dos controllers do componente da aplicação
    $arquivo_comp_contr_app = COMPONENTS_APP . $file . "/Controllers/" . $file . ".php";
    
    // Pasta Model
    $pasta_model = str_replace('Model', '', $file);
    
    // Pastas dos models do componente do core
    $arquivo_comp_model_core = MODULO_CORE . $pasta_model . "/Models/" . $file . ".php";

    // Pastas dos models do componente da aplicação
    $arquivo_comp_model_app = COMPONENTS_APP . $pasta_model . "/Models/" . $file . ".php";

    if (file_exists($arquivo_comp_contr_app)){
        require_once ($arquivo_comp_contr_app);
    } 
    else if (file_exists($arquivo_comp_contr_core)){
        require_once ($arquivo_comp_contr_core);
    } 
    else if (file_exists($arquivo_comp_model_app)){
        require_once ($arquivo_comp_model_app);
    } 
    else if (file_exists($arquivo_comp_model_core)){
        require_once ($arquivo_comp_model_core);
    } 
    else if (file_exists(MODELS . $file . '.php')){
        require_once (MODELS . $file . '.php');
    } 
    else if (file_exists(HELPERS_CORE . $file . '.php')){
        require_once (HELPERS_CORE . $file . '.php');
    } 
    else if (file_exists(HELPERS_APP . $file . '.php')){
        require_once (HELPERS_APP . $file . '.php');
    } 
    else if (file_exists(COMPONENTS_APP . $file . '.php')){
        require_once (COMPONENTS_APP . $file . '.php');
    } 
    else if(file_exists(PERSISTENCIA_CORE . $file . '.php')){
        require_once (PERSISTENCIA_CORE . $file . '.php');
    } 
    else if(file_exists(PERSISTENCIA_APP . $file . '.php')){
        require_once (PERSISTENCIA_APP . $file . '.php');
    } 
    else if(file_exists (INCLUDES_CORE . $file . '.php')){
        require_once (INCLUDES_CORE . $file . '.php');
    } 
    else {
        die("Model ou Helper não encontrado!" . MODEDEBUG ? $file : "");
    }
});

// Antes de dar erro ao instanciar objetos ele procura se está aqui
//function __autoload($file)
//{
//    // Pastas dos controllers do componente do core
//    $arquivo_comp_contr_core = MODULO_CORE . $file . "/Controllers/" . $file . ".php";
//
//    // Pastas dos controllers do componente da aplicação
//    $arquivo_comp_contr_app = COMPONENTS_APP . $file . "/Controllers/" . $file . ".php";
//    
//    // Pasta Model
//    $pasta_model = str_replace('Model', '', $file);
//    
//    // Pastas dos models do componente do core
//    $arquivo_comp_model_core = MODULO_CORE . $pasta_model . "/Models/" . $file . ".php";
//
//    // Pastas dos models do componente da aplicação
//    $arquivo_comp_model_app = COMPONENTS_APP . $pasta_model . "/Models/" . $file . ".php";
//
//    if (file_exists($arquivo_comp_contr_app)){
//        require_once ($arquivo_comp_contr_app);
//    } 
//    else if (file_exists($arquivo_comp_contr_core)){
//        require_once ($arquivo_comp_contr_core);
//    } 
//    else if (file_exists($arquivo_comp_model_app)){
//        require_once ($arquivo_comp_model_app);
//    } 
//    else if (file_exists($arquivo_comp_model_core)){
//        require_once ($arquivo_comp_model_core);
//    } 
//    else if (file_exists(MODELS . $file . '.php')){
//        require_once (MODELS . $file . '.php');
//    } 
//    else if (file_exists(HELPERS_CORE . $file . '.php')){
//        require_once (HELPERS_CORE . $file . '.php');
//    } 
//    else if (file_exists(HELPERS_APP . $file . '.php')){
//        require_once (HELPERS_APP . $file . '.php');
//    } 
//    else if (file_exists(COMPONENTS_APP . $file . '.php')){
//        require_once (COMPONENTS_APP . $file . '.php');
//    } 
//    else if(file_exists(PERSISTENCIA_CORE . $file . '.php')){
//        require_once (PERSISTENCIA_CORE . $file . '.php');
//    } 
//    else if(file_exists(PERSISTENCIA_APP . $file . '.php')){
//        require_once (PERSISTENCIA_APP . $file . '.php');
//    } 
//    else if(file_exists (INCLUDES_CORE . $file . '.php')){
//        require_once (INCLUDES_CORE . $file . '.php');
//    } 
//    else {
//        die("Model ou Helper não encontrado!" . MODEDEBUG ? $file : "");
//    }
//}

// Classe para logs
$dados_log = array('gravar' => false);
$log = new Log($dados_log);

$start = new System;
$start->run();
