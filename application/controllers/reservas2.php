<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reservas2 extends CI_Controller {

  function __construct(){
       parent::__construct();

      $this->load->model('mod_alumno');
      $this->load->model('mod_usuario');
      $this->load->model('mod_reserva2');
      $this->load->model('mod_reserva');
      $this->load->model('mod_parametros');
      $this->load->model('mod_reportes');

      $this->load->helper('form','html');
  }


  public function index(){
    /*
    -Al presionar el dia en el calendario, se deben cargar desde la tabla "reserva_2" en la BD  las
    reservas (realizadas: ["eliminada =0(default)" y "salida = null (default)"]) y las reservas
    (asistidas/: ["eliminada =0(default)" y "salida != null"]),de manera que en las reservas realizadas
    aparezcan los datos del alumno "nombre" y "carrera" como modificables (botón de eliminar, confirmar)
    y las reservas (asistidas/confirmadas) aparezcan como bloquedas, con los datos del alumno "nombre" y
    "carrera".
    En otras palabras, los modulos/salas que serán cargados para el caso de las reservas (asistidas/confirmadas)
    son aquellos con los datos "fecha", "modulo", "sala", "eliminar=0" y "confirmada=1", para el caso de
    las reservas (realizadas) se cargaran los modulos/salas  con los datos "fecha", "modulo", "sala",
    "eliminar=0(default)" y "confirmada =0(default)".

    -El botón reservar o + debe aparecer solo en el dia "hoy" y "el dia siguiente", por RF2, para los
    modulos/salas no reservados y con hora vigente, esto será controlado por página no por BD y se prodrá
    editar en un mantenedor por el ADMINISTRADOR.

    Aclaracion:
    -Reservas Realizadas son las reservas Activas.
    -Reservas Asistidas/Confirmadas son las reservas Inactivas.
    */
  }

  public function NuevaReserva(){


    // Datos por POST

    $fecha = $this->input->post('fecha');

    $salon = $this->input->post('sala');
    $nombre = $this->input->post('nombre');
    $hora_entrada = $this->input->post('modulo_inicio');
    $hora_salida = $this->input->post('modulo_fin');
    $cant_personas = $this->input->post('cant_p');
    $observacion = $this->input->post('observacion');


    $band = '0'; // Bandera para cruce de horarios.
    $query = $this->mod_reserva2->horarios_salones($fecha, $salon); // retorna los horarios que están ocupados para una sala.
    if ($hora_entrada<= $hora_salida){ // Controla intervalo de ingreso.
      if ($hora_entrada < 1 or $hora_salida < 1){
        $band = '3';
      }
      else if ($query != null){ // si hay módulos ocupados, entra.
        foreach($query->result() as $row){ // rescata módulos de la consulta.
          $h_e = $row->hora_entrada;
          $h_s = $row->hora_salida;

          // Control Cruce de horarios(módulos).
          if ($hora_entrada<= $h_e && $hora_salida >= $h_s){
            $band = '1';
          }
          else if ($hora_entrada>= $h_e && $hora_salida<= $h_s){
            $band = '1';
          }
          else if ($hora_entrada<= $h_e && $hora_salida >= $h_e){
            $band = '1';
          }
          else if ($hora_entrada<= $h_s && $hora_salida >= $h_s){
            $band = '1';
          }
          else{
            $band = '0';
          }
        }
      }
    }
    else{
      $band = '2';
    }

    
    if ($band == '0'){
        // Array con datos para la nueva reserva
            $data= array(
                  'fecha'=> $fecha,
                  'salon'=>$salon,
                  'hora_entrada' => (int)$hora_entrada,
                  'eliminada' => 0, // No eliminada
                  'hora_salida' => (int)$hora_salida,
                   'nombre' => $nombre,
                   'cant_personas' => $cant_personas,
                    'estado' => 1,
                    'id_e' => '12.312.312-3', // Obtener loggin
                    'observacion' => 'Reservada: '.$observacion
                  );
            //Nueva reserva.
            $resultado = $this->mod_reserva2->agregar_reserva($data);

            $respuesta = array("error" => false,"insertado" => $resultado);
        }  
    else if($band == '2'){
          $respuesta = array("error"=> true,"mensaje"=>"Intervalo Incorrecto"); //Modulos mal elegidos.
    }    
    else if($band == '3'){
          $respuesta = array("error"=> true,"mensaje"=>"Seleccione Módulos"); //Modulos mal elegidos.
    }    
    else{  
          $respuesta = array("error"=> true,"mensaje"=>"Cruce de Módulos"); //Modulos pisados (cruce de módulos).
        
    }

    echo json_encode($respuesta);


  }

  public function EliminarReserva(){
   /* -El (botón eliminar) que sólo aparece en las reservas (realizadas) y en cualquier hora, debe permitir
    agregar "observación" con la concatenacion de texto ["Eliminada por usuario:"."  ".textoingresado], una
    vez presionado confirmar se debe leer en la BD el máximo_valor de "eliminada" con parametros
    "fecha", "modulo_inicio" y "salón", y actualizar la reserva "fecha", "modulo", "salón" y "eliminada=0",
    a "eliminada = máximo_valor +1" y "observacion" con concatenación. Si el máximo_valor = 0 (no se ha
    eliminado ninguna reserva), el maximo_valor comienza en 2.  maximo_valor>=1 para eliminar.

    -Este procedimiento sirve para la emiminación lógica. el maximo valor aumentado nos permite borrar la misma
    reserva "fecha", "modulo", "salón" las veces que queramos, siendo maximo_valor >=1 un historial de eliminaciones
    para la reserva.


    Reglas de "Eliminación":
    - = 0, no eliminada
    - >1, elimanada por alumno

    */

 // Datos por POST
    $fecha = $this->input->post('fecha');
    $salon = $this->input->post('sala');
    $modulo = $this->input->post('modulo');
                 log_message('debug',print_r($fecha,TRUE));
         log_message('debug',print_r($salon,TRUE));
                  log_message('debug',print_r($modulo,TRUE));
         

    $query = $this->mod_reserva2->obtener_modulos($fecha, $salon, $modulo);

    // obtiene el intevalo de la reserva con el módulo seleccionado y la observación.
  
    if ($query != null){
        foreach($query->result() as $row){
          $h_e = $row->hora_entrada;
          $h_s = $row->hora_salida;
         log_message('debug',print_r($h_e,TRUE));
         log_message('debug',print_r($h_s,TRUE));
        }
         $max = $this->mod_reserva2->obtener_max_eliminada($fecha, $salon, $h_e, $h_s);
          $text = $this->mod_reserva2->obtener_observacion($fecha, $salon, $h_e, $h_s);
    }
    else{
        $max = 0;  
    }
    log_message('debug',print_r($max,TRUE));
    // aumenta "eliminada" para eliminación lógica.
    if ($max < 1){
      $max =1;
    }

    log_message('debug',print_r($max,TRUE));
    // Array para eliminar la reserva.
     $data= array(
      'eliminada' =>  $max +1,
      'estado' => 1,
      'observacion' => 'Eliminada:   '.$this->input->post('observacion').' '
      );
     // se Elimina la reserva lógicamente, actualizando el valor de "eliminada".
    $resultado = $this->mod_reserva2->actualizar_reserva($fecha, $salon, $h_e, $h_s, $data);

    $respuesta = array("error" => false,"borrado" => $resultado);
    echo json_encode($respuesta);

  }


  public function AgregarObservacion(){

    // Datos por POST
    $fecha = $this->input->post('fecha');
    $salon = $this->input->post('sala');
    $modulo = $this->input->post('modulo');

 //   log_message('debug',print_r($fecha,TRUE));

    $query = $this->mod_reserva2->obtener_modulos($fecha, $salon, $modulo);
  // obtiene la reserva con el módulo seleccionado y la observación.
     if ($query != null){
        foreach($query->result() as $row){
          $h_e = $row->hora_entrada;
          $h_s = $row->hora_salida;
          $text = $this->mod_reserva2->obtener_observacion($fecha, $salon, $h_e, $h_s);
        }
    }
 

    //log_message('debug',print_r($text,TRUE));
    // rescata la observación y la concatena con la existente.
    $data= array(
      'observacion' => $text.', |Observación: '.$this->input->post('observacion').'| '
      );
  // Almacena la observación.
    $resultado = $this->mod_reserva2->actualizar_reserva($fecha, $salon, $h_e, $h_s, $data);

    $respuesta = array("error" => false,"Observación Agregada" => $resultado);
    echo json_encode($respuesta);
  }


  public function ObtenerReservas(){
    // obtiene todas las reservas no eliminadas de la BD.
    $query = $this->mod_reserva2->obtener_reservas($this->input->post('fecha'));
    $reservas = array();
    $reserva = array();
    foreach($query->result() as $row){
      $reserva['hora_entrada'] = $row->hora_entrada;
      $reserva['hora_salida'] = $row->hora_salida;
      $reserva['salon'] = $row->salon;
      $reserva['eliminada'] = $row->eliminada;
      $reserva['estado'] = $row->estado;
      $reserva['nombre'] = $row->nombre;
      if($row->estado==0){
        $reserva['reservado'] = 3;  // Gris
      }
      else if($row->salida !=  null){ // (Concluido)
        $reserva['reservado'] = 2;  // Amarillo
      }
      else {
        $reserva['reservado'] = 1;  // Rojo
      }
      $reservas[] = $reserva;
    }
    echo json_encode($reservas);
  }

    public function Salida(){

      //Datos por POST
    $fecha = $this->input->post('fecha');
    $salon = $this->input->post('sala');
    $modulo = $this->input->post('modulo');

    
    $h_salida = $this->mod_reserva->hora_salida();

    
    $query = $this->mod_reserva2->obtener_modulos($fecha, $salon, $modulo);
  // obtiene el intervalo de la reserva con el módulo seleccionado.
     if ($query != null){
        foreach($query->result() as $row){
          $h_e = $row->hora_entrada;
          $h_s = $row->hora_salida;
         // $text = $this->mod_reserva2->obtener_observacion($fecha, $salon, $h_e, $h_s);
        }
    }

    // Array con la Hora de Salida (Marca Concluido).
      $data= array(
      //'eliminada' => 0, // opcional
      'salida' => $h_salida
     // 'observacion' => $text.', |Salida: '.$h_salida.'| '
      );
    // Actualiza la hora de salida.
    $resultado = $this->mod_reserva2->actualizar_reserva($fecha, $salon, $h_e, $h_s, $data);

    $respuesta = array("error" => false,"Hora Ingresada" => $resultado);
    echo json_encode($respuesta);

  }
}
