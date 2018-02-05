<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class mod_reserva2 extends CI_Model
{
    // obtiene la cantidad de veces que un alumno ha reservado, se ocupara para las reservas por dia.
  
    public function hora_salida($fecha, $salon, $hora_entrada, $data){

      $this->db->where('fecha', $fecha);
      $this->db->where('hora_entrada', $hora_entrada);
      $this->db->where('sala', $sala);
      $this->db->where('eliminada', "0");
      $this->db->update('reservas_2', $data);

    }

    //Obtiene los todos los intervalos de salones ocupados.
    public function horarios_salones($fecha, $salon) {

      $this->db->select('hora_entrada, hora_salida');
      $this->db->from('reservas_2');
      $this->db->where('fecha',$fecha);
      $this->db->where('salon',$salon);
      $this->db->where('eliminada', "0");
      return $query = $this->db->get();
    }
  
    // obtiene el mayor valor de la bandera eliminada.
    public function obtener_max_eliminada($fecha, $salon, $h_e, $h_s) {

      $this->db->select_max('eliminada');
    	$this->db->where('fecha',$fecha);
    	$this->db->where('salon',$salon);
    	$this->db->where('hora_entrada',$h_e);
      $this->db->where('hora_salida',$h_s);
      $data=$this->db->get('reservas_2');

      foreach ($data->result() as $row)
      {
      $data2= $row->eliminada;
      return $data2;
      }

    }

    // Obtiene el intevalo de modulos para un modulo que se encuentre en el interior de ese intervalo.
     public function obtener_modulos($fecha, $salon, $modulo) {
     $q_string = "select hora_entrada, hora_salida from reservas_2 where fecha ='".$fecha."' and salon ='".$salon."' and  hora_entrada <= '".$modulo."' and  hora_salida >= '".$modulo."' and eliminada = '0' and estado = '1'";
     //select hora_entrada, hora_salida from reservas_2 where hora_entrada<= '2' and salon = '1' and hora_salida>= '2' and eliminada = '0' and estado = '1';
     return $data = $this->db->query($q_string);

    }
    

    
    //inserta una reserva en la base de datos.
    public function agregar_reserva($data){

      if($this->db->insert('reservas_2',$data)){ // hacer lo mismo con update
        return "Reservado";
      }
      else{
        return false;
      }
    }
    // esta se utiliza para confirmar, eliminar, agregar observación.
    public function actualizar_reserva($fecha, $salon, $h_e, $h_s, $data){

    	$this->db->where('fecha', $fecha);
    	$this->db->where('hora_entrada', $h_e);
      $this->db->where('hora_salida', $h_s);
    	$this->db->where('salon', $salon);
      $this->db->where('eliminada', "0");
    	$this->db->update('reservas_2', $data);

    }

    // obtienes todas las reservas no eliminadas, de la base de datos.
    public function obtener_reservas($fecha)
    {
      $this->db->select('hora_entrada,hora_salida,salon,salida,eliminada,estado,nombre');
      $this->db->from('reservas_2');
      $this->db->where('fecha',$fecha);
      $this->db->where('eliminada',"0");
      return $query = $this->db->get();
    } // PENDIENTE LOS BLOQUEOS TANTO POR HORA, COMO POR GESTION


    // obtiene solo las observaciones de las reservas.
    public function obtener_observacion($fecha, $salon, $h_e, $h_s)
    {
      $this->db->select('observacion');
      $this->db->from('reservas_2');
      $this->db->where('fecha',$fecha);
      $this->db->where('salon',$salon);
      $this->db->where('hora_entrada',$h_e);
      $this->db->where('hora_salida',$h_s);
      $this->db->where('eliminada',"0");
      
      $data = $this->db->get();
    foreach ($data->result() as $row)
    {
    $data2= $row->observacion;
    return $data2;
    }
    }

}

?>
