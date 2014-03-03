<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of funcoesController
 *
 * @author Leandro Shindi Ekamoto
 */
class funcoesController {

    /*
     * Recebe no formato d/m/Y e retorno Y-m-d
     */
    public function getDataTimeStamp($data) {
        $data = explode('/', $data);
        $dia = $data[0];
        $mes = $data[1];
        $ano = $data[2];
        return $ano . '-' . $mes . '-' . $dia;
    }
    /*
     * Recebe no formato Y-m-d e retorno d/m/Y
     */
    public function getDataBR($data) {
        $data = str_replace('-', '/', $data);
        $data = explode('/', $data);
        $ano = $data[0];
        $mes = $data[1];
        $dia = $data[2];
        return $dia . '/' . $mes . '/' . $ano;
    }

}

?>
