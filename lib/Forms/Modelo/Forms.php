<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Forms
 *
 * @author JuanCamilo
 */
include_once '../../config/BD.php';
include_once '../../Caso/Modelo/Caso.php';

class Forms {

    public function MostrarForm($id_tipo_proceso) {



        $obj_conexion = new BD();
        $link = $obj_conexion->Conectar();

        $sql = "SELECT tipc.id_tipo_proceso_campo,tipp.id_tipo_proceso,tipp.tipo_proceso,cam.id_campo,cam.nombre_campo,tcam.tipo_campo,cam.opciones,cam.campo_identi
FROM tipo_proceso tipp
INNER JOIN tipo_proceso_campo tipc ON tipc.id_tipo_proceso = tipp.id_tipo_proceso
INNER JOIN campos cam ON cam.id_campo = tipc.id_campo
INNER JOIN tipo_campo tcam ON tcam.id_tipo_campo = cam.tipo_campo
WHERE tipp.id_tipo_proceso = '" . $id_tipo_proceso . "'
AND cam.perm = 'total'
ORDER BY tipc.id_tipo_proceso_campo ASC";

        $resul = $obj_conexion->ResultSet($sql, $link);

        return $resul;
    }

    public function AlmacenarFormulario($data) {


        $obj_conexion = new BD();
        $link = $obj_conexion->Conectar();



        $sql = "SELECT tipp.id_tipo_proceso,tipp.tipo_proceso,cam.id_campo,cam.nombre_campo,tcam.tipo_campo,cam.opciones,cam.campo_identi
FROM tipo_proceso tipp
INNER JOIN tipo_proceso_campo tipc ON tipc.id_tipo_proceso = tipp.id_tipo_proceso
INNER JOIN campos cam ON cam.id_campo = tipc.id_campo
INNER JOIN tipo_campo tcam ON tcam.id_tipo_campo = cam.tipo_campo
WHERE tipp.id_tipo_proceso = '" . $data['tipo_proceso'] . "'
AND cam.perm = 'total'";


        $arreglo_in = array();



        $resul = $obj_conexion->ResultSet($sql, $link);
        $sql_insert_1 = "INSERT INTO dato (";
        $sql_insert_2 = "(";
        foreach ($resul as $key => $value) {
            $sql_insert_1 .= $value['campo_identi'] . ",";
            $sql_insert_2 .= ":" . $value['campo_identi'] . ",";




            if ($value['tipo_campo'] == 'HORA') {

                $hora = $data['h_' . $value['campo_identi']] . ":" . $data['m_' . $value['campo_identi']];
                $arreglo_in[':' . $value['campo_identi']] = $hora;
            } else {
                $arreglo_in[':' . $value['campo_identi']] = $data['' . utf8_decode($value['campo_identi']) . ''];
            }

            if ($value['campo_identi'] == 'motivo_inasistencia' && $data['motivo_inasistencia'] == 'OTRO') {
                $sql_insert_1 .= "otro_motivo_excusa,";
                $sql_insert_2 .= ":otro_motivo_excusa,";
                $arreglo_in[':otro_motivo_excusa'] = $data['otro_motivo_excusa'];
            }
        }

        $sql_insert_1 = substr($sql_insert_1, 0, -1);
        $sql_insert_2 = substr($sql_insert_2, 0, -1);




        $sql_insert_1 .= ")";
        $sql_insert_2 .= ")";


        $sql_insert = $sql_insert_1 . " VALUES " . $sql_insert_2;
        
        //return var_dump($sql_insert);

        $result = $link->prepare($sql_insert);
        $ejecucion = $result->execute($arreglo_in);

        if ($ejecucion) {
            $ultimo_id_caso = $link->lastInsertId();

            $obj_caso = new Caso();

            $data_caso['id_tipo_proceso'] = $data['tipo_proceso'];
            $data_caso['id_dato'] = $ultimo_id_caso;

            $retorno_caso = $obj_caso->CrearCaso($data_caso);

            return $retorno_caso;
        } else {
            return 'mal';
        }

        /* return var_dump($arreglo_in);
          return $sql_insert_1 . " VALUES " . $sql_insert_2; */
    }

    public function MostrarFormAdmin($id_tipo_proceso) {



        $obj_conexion = new BD();
        $link = $obj_conexion->Conectar();

        $sql = "SELECT tipc.id_tipo_proceso_campo,tipp.id_tipo_proceso,tipp.tipo_proceso,cam.id_campo,cam.nombre_campo,tcam.tipo_campo,cam.opciones,cam.campo_identi
FROM tipo_proceso tipp
INNER JOIN tipo_proceso_campo tipc ON tipc.id_tipo_proceso = tipp.id_tipo_proceso
INNER JOIN campos cam ON cam.id_campo = tipc.id_campo
INNER JOIN tipo_campo tcam ON tcam.id_tipo_campo = cam.tipo_campo
WHERE tipp.id_tipo_proceso = '" . $id_tipo_proceso . "'
AND cam.perm = 'admin'
ORDER BY tipc.id_tipo_proceso_campo ASC";

        $resul = $obj_conexion->ResultSet($sql, $link);

        return $resul;
    }

    public function AlmacenarFormularioAdmin($data) {


        $obj_conexion = new BD();
        $link = $obj_conexion->Conectar();



        $sql = "SELECT tipp.id_tipo_proceso,tipp.tipo_proceso,cam.id_campo,cam.nombre_campo,tcam.tipo_campo,cam.opciones,cam.campo_identi
FROM tipo_proceso tipp
INNER JOIN tipo_proceso_campo tipc ON tipc.id_tipo_proceso = tipp.id_tipo_proceso
INNER JOIN campos cam ON cam.id_campo = tipc.id_campo
INNER JOIN tipo_campo tcam ON tcam.id_tipo_campo = cam.tipo_campo
WHERE tipp.id_tipo_proceso = '" . $data['tipo_proceso'] . "'
AND cam.perm = 'admin'";


        $arreglo_in = array();



        $resul = $obj_conexion->ResultSet($sql, $link);
        $sql_insert_1 = "UPDATE dato SET ";

        foreach ($resul as $key => $value) {
            $sql_insert_1 .= $value['campo_identi'] . "=:" . $value['campo_identi'] . " ,";
            if ($value['tipo_campo'] == 'HORA') {

                $hora = $data['h_' . $value['campo_identi']] . ":" . $data['m_' . $value['campo_identi']];
                $arreglo_in[':' . $value['campo_identi']] = $hora;
            } else {
                $arreglo_in[':' . $value['campo_identi']] = $data['' . utf8_decode($value['campo_identi']) . ''];
            }
        }

        $arreglo_in[':id_dato'] = $data['id_dato'];


        $sql_insert_1 = substr($sql_insert_1, 0, -1);

        $sql_insert = $sql_insert_1 . " WHERE id_dato=:id_dato";



        $result = $link->prepare($sql_insert);
        $ejecucion = $result->execute($arreglo_in);

        if ($ejecucion) {
            return 'bien';
        } else {
            return 'mal';
        }

        /* return var_dump($arreglo_in);
          return $sql_insert_1 . " VALUES " . $sql_insert_2; */
    }

}