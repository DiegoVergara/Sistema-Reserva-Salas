<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class mod_modulos extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }

    //inserta un módulo a la tabla modulos de la BD.
    public function insertar_modulos($data)
    {
            
      if($this->db->insert('modulos',$data)){ 
        return true;
      }
      else{
        return false;
      }
    }

    //elimina un módulo a la tabla modulos de la BD.
    public function eliminar_modulos($id_mod)
    {
       $q_string = "delete from modulos where id_mod ='".$id_mod."'";
        $this->db->query($q_string);
    }

    //obtiene los módulos de la tabla modulos de la BD.
    public function obtener_modulos(){
      $q_string = "select id_mod, time_format(h_inicio, '%H:%m') as inicio, time_format(h_fin, '%H:%m') as fin from modulos";
      //select  time_format(h_inicio, '%H : %m') from modulos
      $data = $this->db->query($q_string);
      return $data;
    }

    public function obtener_modulos_x($id_mod){
      $q_string = "select time_format(h_inicio, '%H:%m') as inicio, time_format(h_fin, '%H:%m') as fin from modulos where id_mod = '".$id_mod."'";
      //select  time_format(h_inicio, '%H : %m') from modulos
      $data = $this->db->query($q_string);
      return $data;
    }

    // obtiene la cantidad de módulos de la tabla modulos de la BD.
    public function obtener_can_modulos(){
      $this->db->select('id_mod');
      $this->db->from('modulos');
      return $this->db->get();
    }

    // obtiene el numero mayor de los módulos de la tabla modulos de la BD.
    public function obtener_max_modulos(){
      $data = $this->db->query("select count(id_mod) as max from modulos");
      foreach ($data->result() as $row)
      {
          $data2= $row->max;
          return $data2;
      }
    }
    // Obtiene el último módulo de los modulos.
    public function obtener_modulo_final(){
      $data = $this->db->query("select max(id_mod) as max from modulos");
      foreach ($data->result() as $row)
      {
          $data2= $row->max;
          return $data2;
      }
    }

    public function obtener_siguiente_modulo($modulo){
      $data = $this->db->query("select id_mod as sig from modulos where id_mod >'".$modulo."' order by id_mod limit 1");
      //SELECT id_mod FROM modulos WHERE id_mod > 7 ORDER BY id_mod LIMIT 1;
        foreach ($data->result() as $row)
        {
            $data2= $row->sig;
            return $data2;
        }

    }
    // obtiene el módulo inicial y final de la tabla módulos.
    public function obtener_modulo_inicial_final(){
      $data = $this->db->query("select min(id_mod) as min, max(id_mod)as max from modulos");
      return $data;
    }

    // obtiene un módulo si existe en la Tabla.
    public function existe_modulo($modulo){
      $data = $this->db->query("select id_mod from modulos where id_mod = '".$modulo."'");
      foreach ($data->result() as $row)
        {
            $data2= $row->id_mod;
            return $data2;
        }
    }
       
}
?>
