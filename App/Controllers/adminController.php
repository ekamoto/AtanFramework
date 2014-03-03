<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5'])) {
    die("Acesso negado");
}

/**
 * Classe para gerenciar o administrativo do sistema ou site
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2012 Atan tecnologia
 * 
 */
class adminController extends ControllerAtan {

    public $nome_controller = 'Admin';
    public static $model;

    function __construct() {
        parent::__construct();
        $this->funcoes_controller = new funcoesController();
        $this->model = $this->getModel();
        $this->db = $this->model->getDb();
    }

    protected function controleAction($proximaAction){
        if($this->isLogadoUsuario()){
            $this->$proximaAction();
        }
        else{
           $this->redirect(array('destino' => '/admin')); 
        }
    }

    public function index_action() {
        $parametros = array();
        $parametros['template'] = "admin_login";

        if ($this->isPost()) {
            if (!isset($this->post['login']) || !isset($this->post['senha']) || empty($this->post['login']) || empty($this->post['senha'])) {
                $dadoErro = array("problema" => "falta de dados.", "mensagem" => "preencha todos os campos, por favor.");
                $this->criarTratamentoErro("logar", $dadoErro);
                $this->redirect(array('destino' => '/index/falta_de_dado'));
            } else if ($this->logarUsuario($this->post['login'], $this->post['senha'])) {
                // Injetando view home no template1
                $this->view("admin", array("template" => "admin_home"));
            } else {
                $dadoErro = array("problema" => "Usuário não Encontrado", "mensagem" => "Tente Novamente");
                $this->criarTratamentoErro("logar", $dadoErro);
                $this->redirect(array('destino' => '/admin'));
            }
        } else if ($this->isLogadoUsuario()) {
            // Injetando view home no template1
            $this->view("admin", array("template" => "admin_home"));
        } else {
            $this->view('login', $parametros);
        }
    }

    public function deslogar() {
        if ($this->isLogadoUsuario()) {
            $this->deslogarUsuario();
        }

        if ($this->isTratamentoErro("logar")) {
            $this->destruirTratamentoErro("logar");
        }

        $this->redirect(array('destino' => '/admin'));
    }

    /**
     * Gerador de código
     * 
     * Observação: dar permissão para as pastas
     * Controller, Model e Views da aplicação
     * 
     */
    public function getModulo() {
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {

                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);

                $gerador = new GeradorCodigo();
                $gerador->setApp($dados['app']);
                $gerador->setNomeController($dados['controller']);
                $gerador->setAutor($dados['autor']);
                $gerador->setDescricaoController($dados['descricao_controller']);
                $gerador->setDescricaoModel($dados['descricao_model']);

                // Por padrão view está ok, porque eu posso gerar módulo sem view
                $view_ok = true;
                if (isset($dados['pasta_view'])) {
                    $gerador->setPastaView($dados['pasta_view']);
                    $gerador->addPastaView();
                    $gerador->setNomeView($dados['nome_view']);
                    $gerador->addView();
                    $caminho_view = VIEWS . ucfirst($dados['controller']) . '/' . $dados['nome_view'] . '.phtml';
                    $view_ok = file_exists($caminho_view);
                }

                $gerador->addController();
                $gerador->setTabela($dados['tabela']);
                $gerador->addModel();

                $caminho_controller = CONTROLLERS . $dados['controller'] . 'Controller.php';
                $caminho_model = MODELS . ucfirst($dados['controller']) . 'Model.php';

                // Verifica se criou corretamente
                if (file_exists($caminho_controller) && file_exists($caminho_model) && $view_ok) {
                    echo json_encode(array('rs' => true, 'msg' => 'Módulo gerado com sucesso!'));
                } else {
                    echo json_encode(array('rs' => true, 'msg' => 'Falha ao gerar módulo!'));
                }
            } else {
                echo json_encode(array('rs' => false, 'msg' => 'Falha ao gerar módulo'));
            }
        } else {
            echo json_encode(array('rs' => false, 'msg' => 'Se vc tentou fazer isso é porque vc é um programador viadinho tentando ferrar o sistema!'));
        }
    }

    public function formCadastroModulo() {
        $this->view("form_cadastro_modulo", array("template" => "admin_home"));
    }

    public function listar_grupos() {
        $this->model->_tabela = 'grupo';
        $dados = $this->model->read();
        $this->view("grupo", array('pasta_view' => 'Grupo', 'template' => 'admin_home', 'dados' => $dados));
    }

    private function form_novo_grupo() {
        $this->view("form_novo_grupo", array('pasta_view' => 'Grupo', 'template' => 'admin_home'));
    }

    private function cadastrarGrupo() {
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $grupo_persistencia = new GrupoPersistencia();
                $grupo_persistencia->setNome($dados['nome']);
                $grupo_persistencia->inserir(true);
            }
            echo json_encode(array('msg' => 'Grupo cadastrado com sucesso', 'rs' => true));
        }
    }

    public function editar_grupo() {
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $this->model->_tabela = 'grupo';
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $grupo_persistencia = new GrupoPersistencia($dados['nome'], $dados['id_grupo']);
                $grupo_persistencia->setPersistente(true);
                $grupo_persistencia->atualizar(false);
            }
            echo json_encode(array('msg' => 'Grupo alterado com sucesso', 'rs' => true));
        } else {
            $dados = array();
            $dados = $this->getParams();
            $this->model->_tabela = 'grupo';
            $where = " id_grupo={$dados['id']}";
            $dados_grupo = array();
            $dados_grupo = $this->model->read($where);
            $this->view("form_alterar_grupo", array('pasta_view' => 'Grupo', 'template' => 'admin_home', 'dados' => $dados_grupo[0]));
        }
    }

    public function deletar_grupo() {
        $dados = array();
        $dados = $this->getParams();
        if (!empty($dados['id'])) {
            $grupo_persistencia = new GrupoPersistencia(false, $dados['id']);
            $grupo_persistencia->setPersistente(true);
            $grupo_persistencia->deletar();
        }
        $this->listar_grupos();
    }

    public function listar_usuarios() {
        
        if(!$this->isLogadoUsuario()){
           $this->redirect(array('destino' => '/admin'));
        }
        
        $this->model->_tabela = 'usuario';
        $dados_usuario = array();
        $dados_usuario = $this->model->read();

        if (!empty($dados_usuario)) {
            foreach ($dados_usuario as $key => $usuario) {
                $this->model->_tabela = 'usuario_grupo';
                $grupo_usuario = array();
                $grupo_usuario = $this->model->read(" id_usuario={$usuario['id_usuario']} ");
                if (!empty($grupo_usuario)) {
                    $this->model->_tabela = 'grupo';
                    $grupo = array();
                    $grupo = $this->model->read(" id_grupo={$grupo_usuario[0]['id_grupo']} ");
                    if (!empty($grupo)) {
                        $dados_usuario[$key]['nome_grupo'] = $grupo[0]['nome'];
                    } else {
                        $dados_usuario[$key]['nome_grupo'] = '';
                    }
                } else {
                    $dados_usuario[$key]['nome_grupo'] = '';
                }
            }
        }

        // Ajusta campos data
        if (!empty($dados_usuario) && is_array($dados_usuario)) {
            foreach ($dados_usuario as $key => $dados) {
                $dados_usuario[$key]['data_nascimento'] = $this->funcoes_controller->getDataBR($dados['data_nascimento']);
                $dados_usuario[$key]['data_ativacao'] = $this->funcoes_controller->getDataBR($dados['data_ativacao']);
            }
        }
        $this->view("usuario", array('pasta_view' => 'Usuario', 'template' => 'admin_home', 'dados' => $dados_usuario));
    }

    public function form_novo_usuario() {
        $this->model->_tabela = 'grupo';
        $dados_grupo['grupos'] = $this->model->read();
        $this->view("form_novo_usuario", array('pasta_view' => 'Usuario', 'template' => 'admin_home', 'dados' => $dados_grupo));
    }

    public function deletar_usuario() {
        $dados = array();
        $dados = $this->getParams();
        if (!empty($dados['id'])) {
            $usuario_persistencia = new UsuarioPersistencia();
            $usuario_persistencia->setPersistente(true);
            $usuario_persistencia->setId_usuario($dados['id']);
            $usuario_persistencia->deletar();
        }
        $this->listar_usuarios();
    }

    public function cadastrarUsuario() {
        $retorno = json_encode(array('msg' => 'Falha ao cadastrar usuário!', 'rs' => false));
        $ok = false;
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $dados['data_nascimento'] = $this->funcoes_controller->getDataTimeStamp($dados['data_nascimento']);
                if ($dados['ativado']) {
                    $dados['data_ativacao'] = date('Y-m-d');
                }
                $usuario_persistencia = new UsuarioPersistencia();
                
                $usuario_persistencia->setDados($dados);
                $ok = $usuario_persistencia->inserir();
                /* Vincular Grupo ao Usuário
                $usuario_grupo_persistencia = new UsuarioGrupoPersistencia($usuario_persistencia->getId_usuario(), $dados['id_grupo']);
                
                if($usuario_persistencia->procurar()) {
                    $usuario_grupo_persistencia->atualizar();
                } else {
                    $usuario_grupo_persistencia->inserir();
                }
                */
            }
            if($ok) {
                $retorno = json_encode(array('msg' => 'Usuário cadastrado com sucesso', 'rs' => true));
            }
            echo $retorno;
        }
    }

    public function editar_usuario() { 
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $this->model->_tabela = 'usuario';
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $usuario_persistencia = new UsuarioPersistencia();
                $usuario_persistencia->setId_usuario($dados['id_usuario']);
                if ($usuario_persistencia->procurar()) {
                    $dados['senha'] = md5($dados['senha'] . $dados['senha']);
                    $dados['data_nascimento'] = $this->funcoes_controller->getDataTimeStamp($dados['data_nascimento']);
                    $usuario_persistencia->setDados($dados);
                    $usuario_persistencia->setPersistente(true);
                    $usuario_persistencia->atualizar();
                    
                    /* Vincular Grupo ao Usuário
                    $usuario_grupo_persistencia = new UsuarioGrupoPersistencia($dados['id_usuario']);

                    if($usuario_grupo_persistencia->procurar()) {
                        $usuario_grupo_persistencia->atualizar();
                    } else {
                        $usuario_grupo_persistencia->inserir();
                    }
                    **/
                     
                }
            }
            echo json_encode(array('msg' => 'Usuário alterado com sucesso', 'rs' => true));
        } else {
            
            $dados = array();
            $dados = $this->getParams();
            $this->model->_tabela = 'usuario';
            $where = " id_usuario={$dados['id']}";
            $dados_usuario = array();
            $dados_usuario = $this->model->read($where);

            $this->model->_tabela = 'usuario_grupo';
            $grupo = array();
            $grupo = $this->model->read(" id_usuario={$dados['id']} ");
            
            if(!empty($grupo)) {
                $dados_usuario[0]['id_grupo'] = $grupo[0]['id_grupo'];
            }
            
            $this->model->_tabela = 'grupo';
            $grupos = array();
            $grupos = $this->model->read();
            
            $dados_usuario['grupos'] = $grupos;
            $dados_usuario[0]['data_nascimento'] = $this->funcoes_controller->getDataBR($dados_usuario[0]['data_nascimento']);
            $this->view("form_alterar_usuario", array('pasta_view' => 'Usuario', 'template' => 'admin_home', 'dados' => $dados_usuario));
        }
    }

    public function listar_componentes() {
        $this->model->_tabela = 'componente';
        $dados = $this->model->read();
        $this->view("componente", array('pasta_view' => 'Componente', 'template' => 'admin_home', 'dados' => $dados));
    }

    public function deletar_componente() {
        $dados = array();
        $dados = $this->getParams();
        if (!empty($dados['id'])) {
            $componente_persistencia = new ComponentePersistencia('', $dados['id']);
            $componente_persistencia->setPersistente(true);
            $componente_persistencia->deletar();
        }
        $this->listar_componentes();
    }

    public function form_novo_componente() {
        $this->view("form_novo_componente", array('pasta_view' => 'Componente', 'template' => 'admin_home'));
    }

    public function cadastrarComponente() {
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $componente_persistencia = new ComponentePersistencia();
                $dados['data_criacao'] = $this->funcoes_controller->getDataBR($dados['data_criacao']);
                $componente_persistencia->setDados($dados);
                $componente_persistencia->inserir();
            }
            echo json_encode(array('msg' => 'Componente cadastrado com sucesso', 'rs' => true));
        }
    }

    public function editar_componente() {
        if ($this->isAjax()) {
            if (isset($this->post['dados_form']) && !empty($this->post['dados_form'])) {
                $this->model->_tabela = 'componente';
                $dados = array();
                $dados = $this->ajustaDadosSerializeArray($this->post['dados_form']);
                $componente_persistencia = new ComponentePersistencia();
                $componente_persistencia->setId_componente($dados['id_componente']);
                if ($componente_persistencia->procurar()) {
                    $dados['data_criacao'] = $this->funcoes_controller->getDataTimeStamp($dados['data_criacao']);
                    $componente_persistencia->setDados($dados);
                    $componente_persistencia->setPersistente(true);
                    $componente_persistencia->atualizar();
                }
            }
            echo json_encode(array('msg' => 'Componente alterado com sucesso', 'rs' => true));
        } else {
            $dados = array();
            $dados = $this->getParams();
            $this->model->_tabela = 'componente';
            $where = " id_componente={$dados['id']}";
            $dados_componente = array();
            $dados_componente = $this->model->read($where);
            $dados_componente[0]['data_criacao'] = $this->funcoes_controller->getDataBR($dados_componente[0]['data_criacao']);
            $this->view("form_alterar_componente", array('pasta_view' => 'Componente', 'template' => 'admin_home', 'dados' => $dados_componente[0]));
        }
    }
}

// Fim classe