<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Salones extends CI_Controller {

function __construct()
  {	//se integran los modelos
       parent::__construct();
       $this->load->model('mod_alumno');
       $this->load->model('mod_usuario');
       $this->load->model('mod_reserva');
       $this->load->model('mod_parametros');
       $this->load->model('mod_salones');
     }
  public function index()
  {
    
    $this->load->model('mod_modulos');
    $this->load->view('favicon');
    $this->load->view('view_salones');
  }


public function ModificarSalones(){
    // Consulta de los datos de los parámetros en la BD
     $datos = $this->mod_parametros->obtener_parametros(); 
     
     $salones = $this->input->post('salones');
      //log_message('debug',print_r($salas,TRUE));
      // Se obtienen los datos antiguos para hacer update.
     $dataPK= array(
          'cant_salas'=> $datos['cant_salas'],
          'plazo_para_reservar'=> $datos['plazo_para_reservar'],
          'n_reservas_diarias'=> $datos['n_reservas_diarias'],
          'cant_salones'=> $datos['cant_salones'],
          'max_p_salas'=> $datos['max_p_salas'],
          'max_p_salones'=> $datos['max_p_salones']
          );
     //Se crea el array con los datos nuevos para el update.
     $dataNEW= array(
          'cant_salas'=> $datos['cant_salas'],
          'plazo_para_reservar'=> $datos['plazo_para_reservar'],
          'n_reservas_diarias'=> $datos['n_reservas_diarias'],
          'cant_salones'=> $salones,
          'max_p_salas'=> $datos['max_p_salas'],
          'max_p_salones'=> $datos['max_p_salones']
          );
     // Actualización de datos en la BD.
     $this->mod_salones->actualizar_salones($dataPK, $dataNEW);

         redirect('parametros');

  }
}
