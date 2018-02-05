<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Parametros extends CI_Controller {

  function __construct(){
       parent::__construct();

      $this->load->model('mod_parametros');

  }

// N_reservas
	// Plazo
public function index(){
  $this->load->model('mod_parametros');
  $this->load->model('mod_salas');
  $this->load->model('mod_salones');
  $this->load->model('mod_modulos');
  $this->load->view('favicon');
  $this->load->view('view_parametros');

  //$this->load->view('view_salones');
}


public function ModificarAlumxdia()  // reservas sin eliminar //
    {
      // Consulta de los datos de los parámetros en la BD
     $datos = $this->mod_parametros->obtener_parametros(); 
     
     $alumxdia = $this->input->post('reservas');
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
          'n_reservas_diarias'=> $alumxdia,
          'cant_salones'=> $datos['cant_salones'],
          'max_p_salas'=> $datos['max_p_salas'],
          'max_p_salones'=> $datos['max_p_salones']
          );
     // Actualización de datos en la BD.
     $this->mod_parametros->actualizar_alumxdia($dataPK, $dataNEW);

         redirect('parametros');


	}

public function ModificarPlazo()  // reservas sin eliminar //
    {

      // Consulta de los datos de los parámetros en la BD
     $datos = $this->mod_parametros->obtener_parametros(); 
     
     $plazo = $this->input->post('plazo');
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
          'plazo_para_reservar'=> $plazo,
           'n_reservas_diarias'=> $datos['n_reservas_diarias'],
           'cant_salones'=> $datos['cant_salones'],
           'max_p_salas'=> $datos['max_p_salas'],
          'max_p_salones'=> $datos['max_p_salones']
          );
      // Actualización de datos en la BD.
     $this->mod_parametros->actualizar_plazo($dataPK, $dataNEW);

         redirect('parametros');

	}

  public function ModificarPersonas()
    {

      // Consulta de los datos de los parámetros en la BD
     $datos = $this->mod_parametros->obtener_parametros(); 
     
     $max_p_salas = $this->input->post('max_p_salas');
     $max_p_salones = $this->input->post('max_p_salones');
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
           'cant_salones'=> $datos['cant_salones'],
           'max_p_salas'=> $max_p_salas,
           'max_p_salones'=> $max_p_salones
          );
     // Actualización de datos en la BD.
     $this->mod_parametros->actualizar_personas($dataPK, $dataNEW);

         redirect('parametros');

  }

}
