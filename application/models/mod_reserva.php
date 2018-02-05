<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class mod_reserva extends CI_Model
{
    // obtiene la cantidad de veces que un alumno ha reservado, se ocupara para las reservas por dia.
    public function obtener_alum_fecha($fecha, $id) {
     $q_string = "select count(id_a) as max from reservas where fecha ='".$fecha."' and  id_a = '".$id."' and eliminada = '0' and confirmada = '0' and estado = '1'";
     $data = $this->db->query($q_string);
      foreach ($data->result() as $row)
      {
          $data2= $row->max;
          return $data2;
      }

    }
    // Obtiene la cantidad de reservas realizadas por un alumno.
        public function obtener_alum_fecha_confirmada($fecha, $id) {
     $q_string = "select count(id_a) as max from reservas where fecha ='".$fecha."' and  id_a = '".$id."' and eliminada = '0' and estado = '1'";
     $data = $this->db->query($q_string);
      foreach ($data->result() as $row)
      {
          $data2= $row->max;
          return $data2;
      }

    }

    // obtiene el mayor valor de la bandera eliminada.
    public function obtener_max_eliminada($fecha, $modulo, $sala) {

      $this->db->select_max('eliminada');
    	$this->db->where('fecha',$fecha);
    	$this->db->where('modulo',$modulo);
    	$this->db->where('sala',$sala);
      $data=$this->db->get('reservas');

    foreach ($data->result() as $row)
    {
    $data2= $row->eliminada;
    return $data2;
    }

    }

    //inserta una reserva en la base de datos.
    public function agregar_reserva($data){

      if($this->db->insert('reservas',$data)){ // hacer lo mismo con update
        return "Reservado";
      }
      else{
        return false;
      }
    }
    // esta se utiliza para confirmar, eliminar, agregar observación.
    public function actualizar_reserva($fecha, $modulo, $sala, $data){

    	$this->db->where('fecha', $fecha);
    	$this->db->where('modulo', $modulo);
    	$this->db->where('sala', $sala);
      $this->db->where('eliminada', "0");
    	$this->db->update('reservas', $data);

    }

    // obtienes todas las reservas no eliminadas, de la base de datos.
    public function obtener_reservas($fecha)
    {
      $this->db->select('*');
      $this->db->from('reservas');
      $this->db->where('fecha',$fecha);
      $this->db->where('eliminada',"0");
      return $query = $this->db->get();
    } 
    //Obtiene las reservas de salas
        public function obtener_reserva($fecha, $modulo, $sala)
    {
      $this->db->select('id_a,nombre_a,carrera_a,id_e,cant_alumnos,plumon_borrador');
      $this->db->from('reservas');
      $this->db->where('fecha',$fecha);
      $this->db->where('modulo',$modulo);
      $this->db->where('sala',$sala);
      $this->db->where('eliminada',"0");
      return $query = $this->db->get();
    }


    // obtiene solo las observaciones de las reservas.
    public function obtener_observacion($fecha, $modulo, $sala)
    {
      $this->db->select('observacion');
      $this->db->from('reservas');
      $this->db->where('fecha',$fecha);
      $this->db->where('modulo',$modulo);
      $this->db->where('sala',$sala);
      $this->db->where('eliminada',"0");
      $this->db->where('estado',"1");
      $data = $this->db->get();
      foreach ($data->result() as $row)
      {
      $data2= $row->observacion;
      return $data2;
      }
    }

    // Obtiene la hora del servidor.
    public function hora_salida()
    {
    $q_string = "select curtime() as hora";
     $data = $this->db->query($q_string);
        foreach ($data->result() as $row)
        {
            $data2= $row->hora;
            return $data2;
        }
    }
    // Obtiene el estado de plumon/borrador.
    public function obtener_plumon_borrador($fecha, $modulo, $sala)
    {
      $this->db->select('plumon_borrador');
      $this->db->from('reservas');
      $this->db->where('fecha',$fecha);
      $this->db->where('modulo',$modulo);
      $this->db->where('sala',$sala);
      $this->db->where('eliminada',"0");
      $this->db->where('estado',"1");
      $data = $this->db->get();
      foreach ($data->result() as $row)
      {
      $data2= $row->plumon_borrador;
      return $data2;
      }
    }
    // obtiene la siguiente reserva.
    public function siguiente_reserva($fecha, $modulo, $sala){
    $q_string = "select modulo from reservas where fecha = '".$fecha."' and modulo = '".$modulo."' and sala = '".$sala."' and eliminada = '0'";
     $data = $this->db->query($q_string);
      foreach ($data->result() as $row)
      {
          $data2= $row->modulo;
          return $data2;
      }


    }

}

?>
