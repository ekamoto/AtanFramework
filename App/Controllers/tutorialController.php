<?php

if (!isset($GLOBALS['hashIdentificaIndexAtan']) || $GLOBALS['hashIdentificaIndexAtan'] != md5($GLOBALS['chave_md5']))
{
    die("Acesso negado");
}

/**
 * Description of tutorial
 *
 * @author Leandro Shindi Ekamoto
 * 
 */
class tutorialController extends ControllerAtan
{

    public $nome_controller = 'Tutorial';

    public function index_action()
    {
        $this->view('index');
    }

    public function addNbsp($n)
    {
        $nbsp = '';
        for ($i = 0; $i < $n; $i++)
        {
            $nbsp .= '&nbsp;';
        }
        return $nbsp;
    }

    public function htaccess()
    {
        $this->view('htaccess');
    }

    public function indexAtan()
    {
        $this->view('indexAtan');
    }

    public function classSystem()
    {
        $this->view('classSystem');
    }

    public function classController()
    {
        $this->view('classController');
    }

    public function classModel()
    {
        $this->view('classModel');
    }

    public function helpers()
    {
        $this->view('helpers');
    }

    public function elements()
    {
        $this->view('elements');
    }

    public function components()
    {
        $this->view('components');
    }

}