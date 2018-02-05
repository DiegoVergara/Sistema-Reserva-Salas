<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class mod_parametros extends CI_Model
{

    //Obtiene todos los valores de la tabla parámetros.
    public function obtener_parametros() {

        $this->db->select('*');
        $data=$this->db->get('parametros');
        foreach ($data->result() as $row)
        {
            $data2['cant_salas']= $row->cant_salas;
            $data2['n_reservas_diarias']= $row->n_reservas_diarias;
            $data2['plazo_para_reservar']= $row->plazo_para_reservar;
            $data2['cant_salones']= $row->cant_salones;
            $data2['max_p_salas']= $row->max_p_salas;
            $data2['max_p_salones']= $row->max_p_salones;
            return $data2;
        }


    }
    //Obtiene sólo el número de reservas diarias de la tabla parámetros.
    public function obtener_alumxdia() {

        $this->db->select('n_reservas_diarias');
        $data=$this->db->get('parametros');

       foreach ($data->result() as $row)
        {
            $data2= $row->n_reservas_diarias;
            return $data2;
        }

    }


    //Obtiene sólo el plazo máximo de la tabla parámetros.
    public function obtener_plazo() {

        $this->db->select('plazo_para_reservar');
        $data=$this->db->get('parametros');

        foreach ($data->result() as $row)
        {
            $data2= $row->plazo_para_reservar;
            return $data2;
        }


    }

    
    //Actualiza sólo el plazo máximo de la tabla parámetros.
    public function actualizar_plazo($dataPK, $dataNEW) {

        $this->db->where('cant_salas', $dataPK['cant_salas']);
        $this->db->where('plazo_para_reservar', $dataPK['plazo_para_reservar']);
        $this->db->where('n_reservas_diarias', $dataPK['n_reservas_diarias']);    
        $this->db->where('cant_salones', $dataPK['cant_salones']);    
        $this->db->where('max_p_salas', $dataPK['max_p_salas']); 
        $this->db->where('max_p_salones', $dataPK['max_p_salones']); 
        $this->db->update('parametros', $dataNEW);  
      

    }
    //Actualiza sólo el la cantidad de reservas diarias de la tabla parámetros.
    public function actualizar_alumxdia($dataPK, $dataNEW) {

        $this->db->where('cant_salas', $dataPK['cant_salas']);
        $this->db->where('plazo_para_reservar', $dataPK['plazo_para_reservar']);
        $this->db->where('n_reservas_diarias', $dataPK['n_reservas_diarias']);    
        $this->db->where('cant_salones', $dataPK['cant_salones']); 
        $this->db->where('max_p_salas', $dataPK['max_p_salas']); 
        $this->db->where('max_p_salones', $dataPK['max_p_salones']); 
        $this->db->update('parametros', $dataNEW);  
      
    }
    //Obtiene sólo la cantidad máxima de personas, para reservar en una sala.
    public function obtener_p_salas() {

        $this->db->select('max_p_salas');
        $data=$this->db->get('parametros');

       foreach ($data->result() as $row)
        {
            $data2= $row->max_p_salas;
            return $data2;
        }

    }

    //Obtiene sólo la cantidad máxima de personas, para reservar en una salón.

    public function obtener_p_salones() {

        $this->db->select('max_p_salones');
        $data=$this->db->get('parametros');

       foreach ($data->result() as $row)
        {
            $data2= $row->max_p_salones;
            return $data2;
        }

    }
    //Actualiza la cantidad máxima de personas en la sección de parámetros.
    public function actualizar_personas($dataPK, $dataNEW) {

        $this->db->where('cant_salas', $dataPK['cant_salas']);
        $this->db->where('plazo_para_reservar', $dataPK['plazo_para_reservar']);
        $this->db->where('n_reservas_diarias', $dataPK['n_reservas_diarias']);    
        $this->db->where('cant_salones', $dataPK['cant_salones']); 
        $this->db->where('max_p_salas', $dataPK['max_p_salas']); 
        $this->db->where('max_p_salones', $dataPK['max_p_salones']); 
        $this->db->update('parametros', $dataNEW);  
      
    }




}
?>
