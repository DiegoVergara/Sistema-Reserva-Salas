<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {
  function __construct(){
       parent::__construct();
      $this->load->model('mod_reportes');
      $this->load->model('mod_modulos');
      $this->load->helper("excel_helper");
  }

  public function index(){
    $this->load->view('favicon');
    $this->load->view('view_reportes');
  }

 public function ReporteDiario(){ // Genera el Gráfico para las Reservas Diarias.
   $query = $this->mod_reportes->total_reservas_sala_dia($this->input->post('fecha'));
   $series = array();
   $total = 0;
   foreach($query->result() as $row){
       $name = 'Sala '.$row->sala;
       $point = ['name'=> $name,'data'=>[(int)$row->cant]];
       $total+=$row->cant;
       array_push($series,$point);
   }
   $data['type'] = 'column';
   $data['title'] = 'Nº de Reservas';
   $data['series'] = $series;
   $data['total'] = $total;
   $data['xls_path'] = site_url('reportes/ReporteDiario_toExcel');
   echo json_encode($data);
 }

 public function ReporteDiario_toExcel(){ // Genera el Excel para las Reservas Diarias.
   $query = $this->mod_reportes->total_reservas_sala_dia($this->input->post('fecha_excel'));
   $header = ['Sala','Reservas'];
   $i=0;
   $content = "";
   foreach($query->result() as $row){
       $datos[] = [$row->sala,(int)$row->cant];
       $content .= "<tr><td>".$row->sala."</td><td>".$row->cant."</td></tr>";
   }
   $data['titulo'] = "Reporte_diario"."[".$this->input->post('fecha_excel')."]";
   $data['header'] = $header;
   $data['contenido'] = $content;
   $this->load->view('view_excel',$data);
 }

public function ReporteSemanal(){ // Genera el Gráfico para las Reservas Semanales.
    $query = $this->mod_reportes->total_reservas_sala_semana($this->input->post('fecha'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Sala '.$row->sala;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteSemanal_toExcel');
    echo json_encode($data);
  }

public function ReporteSemanal_toExcel(){ // Genera el Excel para las Reservas Semanales.
    $query = $this->mod_reportes->total_reservas_sala_semana($this->input->post('fecha_excel'));
    $data['header'] = ['Sala','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $sala = $row->sala;
      $con = $row->con;
      $content .= "<tr><td>$sala</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_semanal'."[".$this->input->post('fecha_excel')."]";
    $this->load->view('view_excel',$data);
  }

public function ReporteMensual(){ // Genera el Gráfico para las Reservas Mensuales.
    $query = $this->mod_reportes->total_reservas_sala_mes($this->input->post('fecha'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Sala '.$row->sala;
      $point = ['name'=>$name,'data'=>[(int)$row->cant]];
      $total+=$row->cant;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteMensual_toExcel');
    echo json_encode($data);
  }

public function ReporteMensual_toExcel(){ // Genera el Excel para las Reservas Mensuales.
    $query = $this->mod_reportes->total_reservas_sala_semana($this->input->post('fecha_excel'));
    $data['header'] = ['Sala','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $sala = $row->sala;
      $con = $row->con;
      $content .= "<tr><td>$sala</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_mensual'."[".$this->input->post('fecha_excel')."]";
    $this->load->view('view_excel',$data);
  }


public function ReporteCarrera(){ // Genera el Gráfico para las Reservas Por Carrera.
    $query = $this->mod_reportes->total_reservas_carrera($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;

    foreach($query->result() as $row){
      $name = $row->carrera_a;
      $point = ['name' => $name, 'data' => [(int)$row->max]];
      $total+=$row->max;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteCarrera_toExcel');
    echo json_encode($data);

  }

public function ReporteCarrera_toExcel(){ // Genera el Excel para las Reservas Por Carrera.
    $query = $this->mod_reportes->total_reservas_carrera($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Carrera','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $name = $row->carrera_a;
      $res = $row->max;
      $content .= "<tr><td>$name</td><td>$res</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_carrera'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
  }


public function ReporteInasistencias(){ // Genera el Gráfico para las Reservas por Inasistencia.
    $query = $this->mod_reportes->inasistencia($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = $row->carrera_a;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Inasistencias';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteInasistencias_toExcel');
    echo json_encode($data);
  }

public function ReporteInasistencias_toExcel(){ // Genera el Excel para las Reservas por Inasistencia.
    $query = $this->mod_reportes->inasistencia($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Carrera','Inasistencias'];
    $content = '';
    foreach($query->result() as $row){
      $carrera = $row->carrera_a;
      $con = $row->con;
      $content .= "<tr><td>$carrera</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_inasistencias'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
  }


public function ReporteHorariosPunta(){ // Genera el Gráfico para las Reservas por horarios Punta.
    $query = $this->mod_reportes->horarios_punta($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Módulo '.$row->modulo;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
    $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteHorariosPunta_toExcel');
    echo json_encode($data);

  }

public function ReporteHorariosPunta_toExcel(){ // Genera el Excel para las Reservas por horarios Punta.
    $query = $this->mod_reportes->horarios_punta($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Módulo','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $query2 = $this->mod_modulos->obtener_modulos_x($row->modulo);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
        $h_f = $row2->fin; 
      }
      $modulo = $h_i."-".$h_f;
      $con = $row->con;
      $content .= "<tr><td>$modulo</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_horarios_punta'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
  }

public function ReporteOcupacion(){ // Genera el Gráfico para las Reservas por ocupación.
    $query = $this->mod_reportes->ocupacion($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name = 'Sala '.$row->sala;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
     $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteOcupacion_toExcel');
    echo json_encode($data);
  }

public function ReporteOcupacion_toExcel(){ // Genera el Excel para las Reservas por ocupación.
    $query = $this->mod_reportes->ocupacion($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Sala','Reservas'];
    $content = '';
    foreach($query->result() as $row){
      $sala = $row->sala;
      $con = $row->con;
      $content .= "<tr><td>$sala</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_ocupacion'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
  }

public function ReporteEliminaciones(){ // Genera el Gráfico para las Reservas por Eliminaciones.
    $query = $this->mod_reportes->eliminaciones($this->input->post('fecha'),$this->input->post('fecha_fin'));
    $series = array();
    $total = 0;
    foreach($query->result() as $row){
      $name =$row->carrera_a;
      $point = ['name'=>$name,'data'=>[(int)$row->con]];
      $total+=$row->con;
      array_push($series,$point);
    }
     $data['type'] = 'column';
    $data['title'] = 'Nº de Reservas';
    $data['series'] = $series;
    $data['total'] = $total;
    $data['xls_path'] = site_url('reportes/ReporteEliminaciones_toExcel');
    echo json_encode($data);
}

public function ReporteEliminaciones_toExcel(){ // Genera el Excel para las Reservas por Eliminaciones.
    $query = $this->mod_reportes->eliminaciones($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
    $data['header'] = ['Carrera','Eliminaciones'];
    $content = '';
    foreach($query->result() as $row){
      $carrera_a = $row->carrera_a;
      $con = $row->con;
      $content .= "<tr><td>$carrera_a</td><td>$con</td></tr>";
    }
    $data['contenido'] = $content;
    $data['titulo'] = 'Reporte_eliminaciones'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
    $this->load->view('view_excel',$data);
}

public function ReporteObservaciones_toExcel(){ // Genera el Excell para las Observaciones de reservas Confirmadas.
  $query = $this->mod_reportes->observaciones_confirmadas($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Sala', 'Módulo', 'Rut', 'Alumno', 'Carrera', 'Asistente', 'Cant Alumnos', 'Salida', 'Observación'];
  $content = '';
  foreach($query->result() as $row){
      $fecha = $row->fecha;
      $sala = $row->sala;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->modulo);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
        $h_f = $row2->fin; 
      }
      $modulo = $h_i."-".$h_f;
      $id_a = $row->id_a;
      $alumno = $row->nombre_a;
      $carrera = $row->carrera_a;
      $id_e = $row->id_e;
      $cant_alumnos = $row->cant_alumnos;
      $salida = $row->salida;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$sala</td><td>$modulo</td><td>$id_a</td><td>$alumno</td><td>$carrera</td><td>$id_e</td><td>$cant_alumnos</td><td>$salida</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_confirmadas'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}

public function ReporteObservacionesE_toExcel(){ // Genera el Excell para las Observaciones de reservas Eliminadas.
  $query = $this->mod_reportes->observaciones_eliminadas($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Sala', 'Módulo', 'Rut', 'Alumno', 'Carrera', 'Asistente', 'Cant Alumnos', 'Observación'];
  $content = '';
  foreach($query->result() as $row){
      $fecha = $row->fecha;
      $sala = $row->sala;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->modulo);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
        $h_f = $row2->fin; 
      }
      $modulo = $h_i."-".$h_f;
      $id_a = $row->id_a;
      $alumno = $row->nombre_a;
      $carrera = $row->carrera_a;
      $id_e = $row->id_e;
      $cant_alumnos = $row->cant_alumnos;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$sala</td><td>$modulo</td><td>$id_a</td><td>$alumno</td><td>$carrera</td><td>$id_e</td><td>$cant_alumnos</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_eliminadas'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}

public function ReporteObservacionesI_toExcel(){ // Genera el Excell para las Observaciones de reservas Eliminadas por Inasistencia.
  $query = $this->mod_reportes->observaciones_inasistencia($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Sala', 'Módulo', 'Rut', 'Alumno', 'Carrera','Asistente', 'Cant Alumnos', 'Observación'];
  $content = '';
  foreach($query->result() as $row){
      $fecha = $row->fecha;
      $sala = $row->sala;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->modulo);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
        $h_f = $row2->fin; 
      }
      $modulo = $h_i."-".$h_f;
      $id_a = $row->id_a;
      $alumno = $row->nombre_a;
      $carrera = $row->carrera_a;
      $id_e = $row->id_e;
      $cant_alumnos = $row->cant_alumnos;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$sala</td><td>$modulo</td><td>$id_a</td><td>$alumno</td><td>$carrera</td><td>$id_e</td><td>$cant_alumnos</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_inasistencias'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}

public function ReporteObservacionesG_toExcel(){ // Genera el Excell para las Observaciones de reservas.
  $query = $this->mod_reportes->observaciones_generales($this->input->post('fecha_excel'),$this->input->post('fecha_excel_fin'));
  $data['header'] = ['Fecha', 'Sala', 'Módulo', 'Rut', 'Alumno', 'Carrera','Asistente', 'Cant Alumnos', 'Observación'];
  $content = '';
  foreach($query->result() as $row){
      $fecha = $row->fecha;
      $sala = $row->sala;
      $query2 = $this->mod_modulos->obtener_modulos_x($row->modulo);
      foreach($query2->result() as $row2){
        $h_i = $row2->inicio; 
        $h_f = $row2->fin; 
      }
      $modulo = $h_i."-".$h_f;
      $id_a = $row->id_a;
      $alumno = $row->nombre_a;
      $carrera = $row->carrera_a;
      $id_e = $row->id_e;
      $cant_alumnos = $row->cant_alumnos;
      $obs = $row->observacion;
      $content .= "<tr><td>$fecha</td><td>$sala</td><td>$modulo</td><td>$id_a</td><td>$alumno</td><td>$carrera</td><td>$id_e</td><td>$cant_alumnos</td><td>$obs</td></tr>";
  }
  $data['contenido'] = $content;
  $data['titulo'] = 'Reporte_observaciones_generales'."[".$this->input->post('fecha_excel')." al ".$this->input->post('fecha_excel_fin')."]";
  $this->load->view('view_excel',$data);
}




}