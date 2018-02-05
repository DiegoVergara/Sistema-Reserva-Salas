<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Disponibilidad extends CI_Controller {


  function __construct(){
       parent::__construct();
// se integran los modelos
      $this->load->model('mod_disponibilidad');
      $this->load->model('mod_salas');
      $this->load->model('mod_salones');
      $this->load->model('mod_modulos');

	}


// SALAS  
// Bandera Estado


    public function Bloqueo(){ // Bloquea módulos del sistema de reservas
      
        (int)$bandera = $this->input->post('bandera'); // Bandera en vista pasada por ajax, depende de bloqueo de día completo o no.
        $fecha = $this->input->post('fecha'); // Fecha en vista pasada por ajax.
        $fecha = str_replace('/', '-', $fecha); // modificamos el formato de fecha en caso de que esté en norteamericano, pasarlo a europeo
        $fecha = date('Y-m-d', strtotime($fecha));  // damos el formato correcto a la fecha
        log_message('debug',print_r($fecha,TRUE));


          if ($bandera == 1){ // Si no es día completo


                      $sala = $this->input->post('sala_bloq');// Sala en vista pasada por ajax.
                      (int)$modulo_inicio = $this->input->post('modulo_inicio');// Modulo inicio en vista pasada por ajax.
                      (int)$modulo_fin = $this->input->post('modulo_fin'); // Modulo fin en vista pasada por ajax.

                      

                    for ($i=$modulo_inicio; $i<= $modulo_fin; $i++) { // loop que recorre modulos seleccionados.
                        $existe = $this->mod_modulos->existe_modulo($i);
                        if ($existe != null){
                            $data= array( // se establece reserva que se alamcenará
                              'fecha'=> $fecha,
                              'modulo'=> $i,
                              'sala'=>$sala,
                              'eliminada' => 0,
                               'id_a'=> '0',
                               'nombre_a' => 'Bloqueada',
                               'carrera_a' => 'No Disponible',
                                'confirmada' => 0,
                                'estado' => 0, // bandera de bloqueo
                              //'id_e'=>$this->input->post('loggin'), // obtener del sesion
                                'id_e' => '12.312.312-3', // Administrador
                                'observacion' => 'Bloqueada'
                              );
                        
                       
                          $resultado = $this->mod_disponibilidad->bloquear($data); // se llama a funcion bloqueo con el array establecido, pasado por parámetro.
                        }
                    }

//                    $respuesta = array("error" => false,"Bloqueados" => $resultado); // mensaje de respuesta para JSON.
                    $respuesta = array("error" => true,"mensaje" => "Error"); // mensaje de respuesta para JSON.

          } else if ($bandera == 2){ // si es todo el día.

                $sala = $this->mod_salas->obtener_salas(); // cantidad de salas almacenadas en la BD.
                $modulo = $this->mod_modulos->obtener_modulo_final(); //cantidad máxima de módulos en la BD.

                for($j=1; $j<= $sala; $j++){
                  for ($i=1; $i<= $modulo; $i++) {
                     $existe = $this->mod_modulos->existe_modulo($i);
                     if ($existe != null){
                        $data= array(
                          'fecha'=> $fecha,
                          'modulo'=> $i,
                          'sala'=>$j,
                           'eliminada' => 0,
                           'id_a'=> '0',
                           'nombre_a' => 'Bloqueada',
                           'carrera_a' => 'No Disponible',
                            'confirmada' => 0,
                            'estado' => 0,
                          //'id_e'=>$this->input->post('loggin'), // obtener del sesion
                            'id_e' => '12.312.312-3', // Administrador
                            'observacion' => 'Bloqueada'
                          );

                      $resultado = $this->mod_disponibilidad->bloquear($data); // se llama a funcion bloqueo con el array establecido, pasado por parámetro.
                   }

                }
                  
                  
             }

             $respuesta = array("error" => false,"Bloqueados" => $resultado); // mensaje de respuesta para JSON.

            }

         echo json_encode($respuesta); // Se envía la respuesta de estado via tipo JSON.
      
      
      }

    public function Desbloqueo(){  // Desbloquea módulos del sistema de reservas

          (int)$bandera = $this->input->post('bandera'); // Bandera en vista pasada por ajax, depende de bloqueo de día completo o no.
          $fecha = $this->input->post('fecha'); // Fecha en vista pasada por ajax.
          $fecha = str_replace('/', '-', $fecha); // modificamos el formato de fecha en caso de que esté en norteamericano, pasarlo a europeo
          $fecha = date('Y-m-d', strtotime($fecha)); // damos el formato correcto a la fecha
          log_message('debug',print_r($fecha,TRUE));

            if ($bandera == 1){ // Si no es día completo


                    $sala = $this->input->post('sala_bloq'); // Sala en vista pasada por ajax.
                    (int)$modulo_inicio = $this->input->post('modulo_inicio'); // Modulo inicio en vista pasada por ajax.
                    (int)$modulo_fin = $this->input->post('modulo_fin'); // Modulo fin en vista pasada por ajax.

                      
                    for ($i=$modulo_inicio; $i<= $modulo_fin; $i++) {  // loop que recorre modulos seleccionados.
                          $existe = $this->mod_modulos->existe_modulo($i);
                          if ($existe != null){ 
                            $resultado = $this->mod_disponibilidad->desbloquear($fecha, $i, $sala); // se llama a funcion Desbloqueo, pasando por parámetro la fecha, la sala y el módulo i.
                          }                                                                    //desbloquear elimina los bloqueos tipo reserva en la BD
                        }

                         $respuesta = array("error" => false,"Desbloqueados" => $resultado); // mensaje de respuesta para JSON.

                   
              } else if ($bandera == 2){ // si es todo el día.

                $sala = $this->mod_salas->obtener_salas(); // cantidad de salas almacenadas en la BD.
                $modulo = $this->mod_modulos->obtener_modulo_final();  //cantidad máxima de módulos en la BD.

                    for($j=1; $j<= $sala; $j++){ // Loop para recorrer 
                      for ($i=1; $i<= $modulo; $i++) {
                           $existe = $this->mod_modulos->existe_modulo($i);
                           if ($existe != null){
                              $resultado = $this->mod_disponibilidad->desbloquear($fecha, $i, $j); // se llama a funcion Desbloqueo, pasando por parámetro la fecha, la sala j y el módulo i.
                            }                                                              //desbloquear elimina los bloqueos tipo reserva en la BD
                   		}
                	}

                    $respuesta = array("error" => false,"Desbloqueados" => $resultado); // mensaje de respuesta para JSON.
                }
                
                echo json_encode($respuesta); // Se envía la respuesta de estado via tipo JSON.
       }



// SALONES
         public function BloqueoSalones(){ // Bloquea módulos del sistema de reservas
      
        (int)$bandera = $this->input->post('bandera'); // Bandera en vista pasada por ajax, depende de bloqueo de día completo o no.
        $fecha = $this->input->post('fecha'); // Fecha en vista pasada por ajax.
        $fecha = str_replace('/', '-', $fecha); // modificamos el formato de fecha en caso de que esté en norteamericano, pasarlo a europeo
        $fecha = date('Y-m-d', strtotime($fecha));  // damos el formato correcto a la fecha
        log_message('debug',print_r($fecha,TRUE));
        log_message('debug',print_r($bandera,TRUE));
   

          if ($bandera == 1){ // Si no es día completo

              $salon = $this->input->post('sala_bloq2');// Salon en vista pasada por ajax.
              (int)$modulo_inicio2 = $this->input->post('modulo_inicio2');// Modulo inicio en vista pasada por ajax.
              (int)$modulo_fin2 = $this->input->post('modulo_fin2'); // Modulo fin en vista pasada por ajax.
              log_message('debug',print_r($salon,TRUE));
              log_message('debug',print_r($modulo_inicio2,TRUE));
              log_message('debug',print_r($modulo_fin2,TRUE));
                                             
                        $data= array(
                          'fecha'=> $fecha,
                          'salon'=>$salon,
                          'hora_entrada' => $modulo_inicio2, 
                          'eliminada' => 0,
                          'hora_salida' => $modulo_fin2, 
                           'nombre' => 'Bloqueada',
                            'cant_personas' => 0,
                            'estado' => 0,
                          //'id_e'=>$this->input->post('loggin'), // obtener del sesion
                            'id_e' => '12.312.312-3', // Administrador
                            'observacion' => 'Bloqueada'
                          );
                    
                       $resultado = $this->mod_disponibilidad->bloquear_salon($data); // se llama a funcion bloqueo con el array establecido, pasado por parámetro.
                  
                    $respuesta = array("error" => false,"Bloqueados" => $resultado); // mensaje de respuesta para JSON.

          } else if ($bandera == 2){ // si es todo el día.

                $salon = $this->mod_salones->obtener_salones(); // cantidad de salones almacenadas en la BD.
                $query = $this->mod_modulos->obtener_modulo_inicial_final();
                        foreach($query->result() as $row){
                          $min = $row->min;
                          $max = $row->max;
                        }

                for($j=1; $j<= $salon; $j++){
                                                       
                        $data= array(
                          'fecha'=> $fecha,
                          'salon'=>$j,
                          'hora_entrada' => $min, 
                          'eliminada' => 0,
                          'hora_salida' => $max, 
                           'nombre' => 'Bloqueada',
                            'cant_personas' => 0,
                            'estado' => 0,
                          //'id_e'=>$this->input->post('loggin'), // obtener del sesion
                            'id_e' => '12.312.312-3', // Administrador
                            'observacion' => 'Bloqueada'
                          );
                    
                       $resultado = $this->mod_disponibilidad->bloquear_salon($data); // se llama a funcion bloqueo con el array establecido, pasado por parámetro.
                  
             }

             $respuesta = array("error" => false,"Bloqueados" => $resultado); // mensaje de respuesta para JSON.

            }

         echo json_encode($respuesta); // Se envía la respuesta de estado via tipo JSON.
      
      
      }

    public function DesbloqueoSalones(){  // Desbloquea módulos del sistema de reservas

       (int)$bandera = $this->input->post('bandera'); // Bandera en vista pasada por ajax, depende de bloqueo de día completo o no.
        $fecha = $this->input->post('fecha'); // Fecha en vista pasada por ajax.
        $fecha = str_replace('/', '-', $fecha); // modificamos el formato de fecha en caso de que esté en norteamericano, pasarlo a europeo
        $fecha = date('Y-m-d', strtotime($fecha));  // damos el formato correcto a la fecha
        log_message('debug',print_r($fecha,TRUE));
        log_message('debug',print_r($bandera,TRUE));

            if ($bandera == 1){ // Si no es día completo


                       $salon = $this->input->post('sala_bloq2');// Salon en vista pasada por ajax.
                       (int)$modulo_inicio2 = $this->input->post('modulo_inicio2');// Modulo inicio en vista pasada por ajax.
                       (int)$modulo_fin2 = $this->input->post('modulo_fin2'); // Modulo fin en vista pasada por ajax.
                                        
                          
                         $resultado = $this->mod_disponibilidad->desbloquear_salon($fecha, $salon, $modulo_inicio2, $modulo_fin2); // se llama a funcion Desbloqueo, pasando por parámetro la fecha, el salon y el módulo i.
                                                                                                                            //desbloquear elimina los bloqueos tipo reserva en la BD
                      
                         $respuesta = array("error" => false,"Desbloqueados" => $resultado); // mensaje de respuesta para JSON.

                   
              } else if ($bandera == 2){ // si es todo el día.

                        $salon = $this->mod_salones->obtener_salones(); // cantidad de salones almacenadas en la BD.
                        $query = $this->mod_modulos->obtener_modulo_inicial_final();
                        foreach($query->result() as $row){
                          $min = $row->min;
                          $max = $row->max;
                        }

                    for($j=1; $j<= $salon; $j++){ // Loop para recorrer 
                     
                       $resultado = $this->mod_disponibilidad->desbloquear_salon($fecha, $j, $min, $max); // se llama a funcion Desbloqueo, pasando por parámetro la fecha, la sala j y el módulo i.
                                                                                            //desbloquear elimina los bloqueos tipo reserva en la BD
                     // }
                  }

                    $respuesta = array("error" => false,"Desbloqueados" => $resultado); // mensaje de respuesta para JSON.
                }
                
                echo json_encode($respuesta); // Se envía la respuesta de estado via tipo JSON.
       }

}
