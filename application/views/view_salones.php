<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sistema de Reserva de Salas</title>
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui-1.10.4.custom.css"/>
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables_themeroller.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/bic_calendar.css" />
</head>
<body data-url="<?php echo base_url();?>" data-controller="<?php echo site_url('reservas2');?>">
  <section id="container">
    <nav class="navbar navbar-default" role="navigation">
      <div class="">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?php echo base_url();?>"><img class="imagensibib" src="<?php echo base_url(); ?>images/sibibnegro.png"></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo base_url();?>"><span class="fa fa-building"></span> Salas</a></li>
        <!-- ########## -->    
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-file-text"></span> Reportes <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo site_url('reportes');?>"><span class="fa fa-building"></span> Salas</a></li>
                <li><a href="<?php echo site_url('reportes2');?>"><span class="fa fa-institution"></span> Salones</a></li>
              </ul>
            </li>
        <!-- ########## -->
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-cog"></span> Administrar <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo site_url('modulos');?>"><span class="fa fa-cubes"></span> Módulos</a></li>
                <li><a href="<?php echo site_url('parametros');?>"><span class="fa fa-cogs"></span> Parámetros</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user"></span> Usuario <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#"><span class="fa fa-sign-out"></span> Cerrar sesión </a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <aside>
      <div id="sidebar">
        <div id="calendar-wrap" class="calendar-wrap" data-fecha="">
          <div id="calendar"></div>
        </div>
      </div>
    </aside>
    <section id="main-content">
          <div id="leyenda" style="margin: auto; text-align: center;">&nbsp;
      <span class="fa fa-circle" style="color: #2ECC71;"></span> Disponible&nbsp;
      <span class="fa fa-circle" style="color: #95a5a6;"></span> Bloqueado&nbsp; 
      <span class="fa fa-circle" style="color: #c0392b;"></span> Reservado&nbsp; 
      <span class="fa fa-circle" style="color: #f1c40f;"></span> Concluido&nbsp; 
      </div>
      <div id="wrapper">
        <div id="myScheduler">
          <table id="tabla_horarios" border="1" style="table">
            <thead>
              <tr>
                <th style="width: 80px;text-align: center;"> Módulo </th> <!-- Carga salones en tabla-->
                <?php

                $num_salones = $this->mod_salones->obtener_salones(); 
                for($i=1;$i<=$num_salones;$i++){

                  echo '<th style="text-align: center;" data-num-sala="'.$i.'"> Salón '.$i.' </th>';
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
                  // Ciclo para maquetar, queda pendiente la integración con los verdaderos módulos
                   $query = $this->mod_modulos->obtener_modulos(); // Carga modulos en tabla.
                   foreach($query->result() as $row){
                   echo '<tr>';
                   echo '<td data-id-modulo="'.$row->id_mod.'" style="text-align: center;"> '.$row->inicio.' - '.$row->fin.' </td>';
                    for($j=1;$j<=$num_salones;$j++){
                    echo '<td class="horario" data-reservado="0"></td>';
                    }
                    echo '</tr>';

                  }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <footer>

    </footer>
  </section>
  <div id="modal-reserva" class="modal fade">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Reservar Salón</h4>
        </div>
        <div class="modal-body">
        <!--##########################################################-->
          <form id="form-reserva" action="<?php echo site_url('reservas2/NuevaReserva');?>">

            <input type="text" id="nombre" name="nombre" placeholder="Ej: Guillermo Becerra" required>
            <input type="hidden" id="fecha" name="fecha">
            <input type="hidden" id="sala" name="sala">
            <input type="hidden" id="modulo" name="modulo">
            <br>
            <label for="">Duración</label><br>
            <select name="modulo_inicio" id="modulo_inicio"> <!--##########-->
            <?php
              echo '<option value="">Inicio</option>'; //Carga Módulo de inicio en ComboBox modal de reserva.
              $query = $this->mod_modulos->obtener_modulos();
              foreach($query->result() as $row){
                echo '<option value="'.$row->id_mod.'">'.$row->inicio.'</option>'; // dejar sólo inicio
              }
            ?>
            </select>
            <select name="modulo_fin" id="modulo_fin"> <!--##########-->
            <?php
              echo '<option value="">Fin</option>'; //Carga Módulo fin en ComboBox modal de reserva.
              $query = $this->mod_modulos->obtener_modulos();
              foreach($query->result() as $row){
                echo '<option value="'.$row->id_mod.'">'.$row->fin.'</option>'; // dejar sólo fin
              }
            ?>
          </select><br><br>
            <label for=""> Cantidad de personas</label>

            <select name="cant_p" id="cant_p">  <!--##########--> 
              <?php
              $max_p_salones = $this->mod_parametros->obtener_p_salones(); //Carga Salones en ComboBox modal de reserva.
              for($i=1;$i<=$max_p_salones;$i++){

                echo '<option value="'.$i.'">'.$i.'</option>';
              }
              ?>
            </select>
          <br>
          <label for="">Observación</label>
          <textarea class="form-control" style="resize: none;" name="observacion" id="observacion" cols="89" rows="10"></textarea> <!--##########-->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-agregar">Reservar</button>
         </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div id="modalEliminar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h3>¿Desea eliminar esta reserva?</h3>
          <div class="input-group">
            <textarea class="form-control" style="resize: none;" name="observacion_salon" id="observacion_salon" cols="89" rows="10"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn-eliminar-reserva-salon" class="btn btn-primary">Eliminar</button>
          <button id="btn-cancelar-reserva-salon" class="btn btn-danger">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
  var salones = true;
  </script>
  <script src="<?php echo base_url(); ?>js/jquery.js"></script>
  <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>js/modernizr.custom.63321.js"></script>
  <script src="<?php echo base_url(); ?>js/bic_calendar.js"></script>
  <script src="<?php echo base_url(); ?>js/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>js/jquery.noty.packaged.min.js"></script>
  <script src="<?php echo base_url(); ?>js/custom.js"></script>
</body>
</html>
