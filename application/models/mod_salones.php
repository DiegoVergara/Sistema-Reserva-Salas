<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class mod_salones extends CI_Model
{


  // Obtiene el número de salones, almacenados en la BD.

 	public function obtener_salones() {

        $this->db->select('cant_salones');
        $data=$this->db->get('parametros');
        foreach ($data->result() as $row)
      	{

          $data2= $row->cant_salones;

          return $data2;
      	}
        
    }


// Modifica el número de salones, almacenados en la BD.
    public function actualizar_salones($dataPK, $dataNEW) {

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