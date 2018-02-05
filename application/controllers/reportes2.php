<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes2 extends CI_Controller {
  function __construct(){
       parent::__construct();
      $this->load->model('mod_reportes2');
      $this->load->model('mod_modulos');
      $this->load->helper("excel_helper");
  }

  public function index(){
    $this->load->view('favicon');
    $this->load->view('view_reportes2');
  }

 public function ReporteDiario(){ // Genera el Gráfico para las Reservas Diarias.
   $query = $this->mod_reportes2->total_reservas_salon_dia($this->input->post('fecha'));
   $series = array();
   $total = 0;
   foreach($query->result() as $row){
       $name = 'Salon '.$row->salon;
       $point = ['name'=> $name,'data'=>[(int)$row->cant]];
       $total+=$row->cant;
       array_push($series,$point);
   }
   $data['type'] = 'column';
   $data['title'] = 'Nº de Reservas';
   $data['series'] = $series;
   $data['total'] = $total;
   $data['xls_path'] = site_url('reportes2/ReporteDiario_toExcel');
   echo json_encode($data);
 }

 public function ReporteDiario_toExcel(){ // Genera el Excell para las Reservas Diarias .
   $query = $this->mod_reportes2->total_reservas_salon_dia($this->input->post('fecha_excel'));
   $header = ['Salón','Reservas'];
   $i=0;
   $content = "";
   foreach($query->result() as $row){
       $datos[] = [$row->salon,(int)$row->cant];
       $content .= "<tr><td>".$row->salon."</td><td>".$row->cant."</td></tr>";
   }
   $data['titulo'] = "Reporte_diario_salon"."[".$this->input->post('fecha_excel')."]";
   $data['header'] = $header;
   $data['contenido'] = $content;
   $this->load->view('view_excel',$data);
 }

public function ReporteSemanal(){ // Genera el Gráfico para las Reservas Semanales.
    $query = $this->mod_reportes2->total_reservas_salon_semana($this->input->post('fecha'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Salon '.$row->salon;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes2/ReporteSemanal_toExcel');
    echo json_encode($data);
  }

public function ReporteSemanal_toExcel(){ // Genera el Excell para las Reservas Semanales.
    $query = $this->mod_reportes2->total_reservas_salon_semana($this->input->post('fecha_excel'));
    $data['header'] = ['Salon','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $salon = $row->salon;
      $con = $row->con;
      $content .= "<tr><td>$salon</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_semanal_salones'."[".$this->input->post('fecha_excel')."]";
    $this->load->view('view_excel',$data);
  }

public function ReporteMensual(){ // Genera el Gráfico para las Reservas Mensuales.
    $query = $this->mod_reportes2->total_reservas_salon_mes($this->input->post('fecha'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Salon '.$row->salon;
      $point = ['name'=>$name,'data'=>[(int)$row->cant]];
      $total+=$row->cant;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes2/ReporteMensual_toExcel');
    echo json_encode($data);
  }
public function ReporteMensual_toExcel(){ // Genera el Excell para las Reservas Mensuales.
    $query = $this->mod_reportes2->total_reservas_salon_mes($this->input->post('fecha_excel'));
    $data['header'] = ['Salón','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $salon = $row->salon;
      $cant = $row->cant;
      $content .= "<tr><td>$salon</td><td>$cant</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_mensual_salon'."[".$this->input->post('fecha_excel')."]";
    $this->load->view('view_excel',$data);
  }


public function ReporteOcupacion(){ // Genera el Gráfico para las Reservas  por Ocupación.
    $query = $this->mod_reportes2->ocupacion($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Salon '.$row->salon;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
     $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes2/ReporteOcupacion_toExcel');
    echo json_encode($data);
  }

  public function ReporteOcupacion_toExcel(){ // Genera el Excell para las Reservas por Ocupación.
    $query = $this->mod_reportes2->ocupacion($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Salón','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $salon = $row->salon;
      $con = $row->con;
      $content .= "<tr><td>$salon</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_ocupacion_salon'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
  }



  public function ReporteObservaciones_toExcel(){ // Genera el Excell para las Observaciones de reservas Concluidas.
  $query = $this->mod_reportes2->observaciones_confirmadas($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Salón', 'Hora Entrada', 'Hora Salida', 'Salida', 'Nombre', 'Asistente', 'N Personas', 'Observación'];
  $content = '';
 foreach($query->result() as $row){
      $fecha = $row->fecha;
      $salon = $row->salon;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->hora_entrada);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
      }
      $hora_entrada = $h_i;
      $query3 = $this->mod_modulos->obtener_modulos_x($row->hora_salida);
      foreach($query3->result() as $row3){
        $h_f = $row3->fin; 
      }
      $hora_salida = $h_f;
      $salida = $row->salida;
      $nombre = $row->nombre;
      $id_e = $row->id_e;
      $cant_personas = $row->cant_personas;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$salon</td><td>$hora_entrada</td><td>$hora_salida</td><td>$salida</td><td>$nombre</td><td>$id_e</td><td>$cant_personas</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_concluidas_salones'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}

public function ReporteObservacionesE_toExcel(){ // Genera el Excell para las Observaciones de reservas Eliminadas.
  $query = $this->mod_reportes2->observaciones_eliminadas($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Salón', 'Hora Entrada', 'Hora Salida', 'Nombre', 'Asistente', 'N Personas', 'Observación'];
  $content = '';
  foreach($query->result() as $row){
      $fecha = $row->fecha;
      $salon = $row->salon;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->hora_entrada);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
      }
      $hora_entrada = $h_i;
      $query3 = $this->mod_modulos->obtener_modulos_x($row->hora_salida);
      foreach($query3->result() as $row3){
        $h_f = $row3->fin; 
      }
      $hora_salida = $h_f;
      $nombre = $row->nombre;
      $id_e = $row->id_e;
      $cant_personas = $row->cant_personas;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$salon</td><td>$hora_entrada</td><td>$hora_salida</td><td>$nombre</td><td>$id_e</td><td>$cant_personas</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_eliminadas_salones'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}


public function ReporteObservacionesG_toExcel(){ // Genera el Excell para las Observaciones de reservas.
  $query = $this->mod_reportes2->observaciones_generales($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
   $data['header'] = ['Fecha', 'Salón', 'Hora Entrada', 'Hora Salida', 'Nombre', 'Asistente', 'N Personas', 'Observación'];
  $content = '';
foreach($query->result() as $row){
      $fecha = $row->fecha;
      $salon = $row->salon;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->hora_entrada);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
      }
      $hora_entrada = $h_i;
      $query3 = $this->mod_modulos->obtener_modulos_x($row->hora_salida);
      foreach($query3->result() as $row3){
        $h_f = $row3->fin; 
      }
      $hora_salida = $h_f;
      $nombre = $row->nombre;
      $id_e = $row->id_e;
      $cant_personas = $row->cant_personas;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$salon</td><td>$hora_entrada</td><td>$hora_salida</td><td>$nombre</td><td>$id_e</td><td>$cant_personas</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_generales_salones'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}

}
