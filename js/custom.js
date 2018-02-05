(function() {
	$(document).ready(function() {
		var hoy = new moment(new Date()).format('D/M/YYYY'); // Variable utilizada para setear la fecha actual en el plugin de calendario
		$('#calendar-wrap').data('fecha', new moment(new Date()).format('YYYY-MM-DD')); // Se añade la fecha actual al wrapper del calendario
		var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
		var dayNames = ["L", "M", "M", "J", "V", "S", "D"];
		var events = [{
			"date": hoy,
			"link": "#",
			"color": "green"
		}];
		var $cell = null;
		var $th = null;
		var $html = '';
		var $sala = null;
		var $modulo = null;
		var $content_obs = '<div class="input-group"><textarea id="obs_text" style="resize: none;" rows="3" cols="20" class="form-control pull-left"></textarea><span class="input-group-btn"><button class="btn btn-primary btn-add-obs" type="button"><span class="fa fa-check"></span></button></span></div>';
		var $content_obs_s = '<div class="input-group"><textarea id="obs_text_s" style="resize: none;" rows="3" cols="20" class="form-control pull-left"></textarea><span class="input-group-btn"><button class="btn btn-primary btn-add-obs-salon" type="button"><span class="fa fa-check"></span></button></span></div>';

		$('#calendar').bic_calendar({
			//list of events in array
			events: events,
			//enable select
			enableSelect: true,
			//enable multi-select
			multiSelect: false,
			//set day names
			dayNames: dayNames,
			//set month names
			monthNames: monthNames,
			//show dayNames
			showDays: true,
			//show month controller
			displayMonthController: true,
			//show year controller
			displayYearController: true,
		});
		// Modifica los colores en la tabla horarios
		var actualizarTabla = function($tabla) {
			$tabla.each(function(i, elemento) {
				switch ($(this).data('reservado')) {

					case 1:
						$(this).css({
							'background': '#c0392b'
						});
						break;
					case 2:
						$(this).css({
							'background': '#f1c40f'
						});
						break;
					case 3:
						$(this).css({
							'background': '#95a5a6'
						});
						break;
					default:
						if ($(this).hasClass('horario')) {
							$(this).css({
								'background': '#2ecc71'
							});
						}
						break;
				}
			});
		};
		// inicializa la tabla horarios.
		var inicializarTabla = function($tabla) {
			$tabla.each(function(i, elemento) {
				$(this).data('reservado', 0);
				if ($(this).hasClass('horario')) {
					$(this).html('');
				}
			});
		};
		// Rellena con reservas, las tablas de reservas.
		var obtenerReservas = function($fecha) {
			inicializarTabla($('#tabla_horarios td'));
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $fecha
				},
				url: $('body').data('controller') + '/ObtenerReservas',
				success: function(respuesta) {
					console.log(respuesta);
					$.each(respuesta, function(key, value) {
						if(value.modulo===undefined){
							if(parseInt(value.hora_entrada) == parseInt(value.hora_salida)){
								$('td[data-id-modulo=' + value.hora_entrada + ']').siblings().eq(value.salon - 1).data('reservado', value.reservado);
								$('td[data-id-modulo=' + value.hora_entrada + ']').siblings().eq(value.salon - 1).html(value.nombre + '<br><span class="carrera"></span>');
							}
							else{
								if(parseInt(value.hora_entrada) < parseInt(value.hora_salida)){
									for(i = parseInt(value.hora_entrada); i <= parseInt(value.hora_salida); i++){
										$('td[data-id-modulo=' + i + ']').siblings().eq(value.salon - 1).data('reservado', value.reservado);
										$('td[data-id-modulo=' + i + ']').siblings().eq(value.salon - 1).html(value.nombre + '<br><span class="carrera"></span>');
									}
								}
							}
						}
						else{
							plumon = '';
							if(value.plumon_borrador=="1"){
								plumon = '<a href="#" class="plumon"><i class="fa fa-pencil"></i> </a> ';
							}
							$('td[data-id-modulo=' + value.modulo + ']').siblings().eq(value.sala - 1).data('reservado', value.reservado);
							$('td[data-id-modulo=' + value.modulo + ']').siblings().eq(value.sala - 1).html(plumon + value.nombre_a + '<br><span class="carrera">' + value.carrera_a + '</span>');
						}
					});
					actualizarTabla($('#tabla_horarios td'));
				}
			});
		};

		obtenerReservas($('#calendar-wrap').data('fecha'));

		document.addEventListener('bicCalendarSelect', function(e) {
			moment.lang('es');
			$('#calendar-wrap').data('fecha', new moment(e.detail.date).format('YYYY-MM-DD'));
			$('.popover').hide();
			obtenerReservas($('#calendar-wrap').data('fecha'));
		});
		// Controla el botón reserva.
		var validarFecha = function(el){
			hora_intervalo = el.parent().children(':first-child').text().split('-');
			hora_inicial = hora_intervalo[0].trim().split(':');
			hora_actual = moment().format('HH');
			dia_actual = new moment(new Date()).format('YYYY-MM-DD');
			if($('#calendar-wrap').data('fecha')>dia_actual){
				return true;
			}
			else{
				if($('#calendar-wrap').data('fecha')==dia_actual){
					if(hora_inicial[0]>=hora_actual){
						return true;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
		};
		// Controla los botones en las reservas.
		var validarFecha2 = function(el){
			hora_intervalo = el.parent().children(':first-child').text().split('-');
			hora_inicial = hora_intervalo[0].trim().split(':');
			hora_actual = moment().add(2, '').format('HH');
			dia_actual = new moment(new Date()).format('YYYY-MM-DD');
			if($('#calendar-wrap').data('fecha')>dia_actual){
				return true;
			}
			else{
				if($('#calendar-wrap').data('fecha')==dia_actual){
					if(hora_inicial[0]>=hora_actual-1){
						return true;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
		};
		// Coloca las reservas en los Módulos con sus respectivos botones al pasar el mouse.
		$(document).on('mouseenter', '#tabla_horarios tr td', function(e) {
			if ($(this).hasClass('horario')) {
				switch($(this).data('reservado')){
					case 0:
						if(validarFecha($(this))){
							$(this).html('<button class="btn btn-primary btn-reserva">Reservar</button>');
						}
						break;
					case 1:
						$html = $(this).find('.carrera').html();
						if(salones){
							$(this).find('.carrera').html('<a href="#" class="eliminar_salon"><i class="fa fa-trash-o"></i></a> <a href="#" class="salida_salon"><i class="fa fa-sign-out"></i></a> <a href="#" class="observacion_salon"><i class="fa fa-comment"></i></a>');
						}
						else{
							$(this).find('.carrera').html('<a href="#" class="validar"><i class="fa fa-thumbs-up"></i></a> <a href="#" class="observacion"><i class="fa fa-comment"></i></a> <a href="#" class="eliminar"><i class="fa fa-trash-o"></i></a>');
						}
						break;
					case 2:
						$html = $(this).find('.carrera').html();
						if(salones){
						}
						else{
							cellIndex = $(this).index();
							if(validarFecha2($(this))){
								if($(this).closest('tr').next().children().eq(cellIndex).data('reservado')==0){
									$(this).find('.carrera').html('<a href="#" class="renovar"><i class="fa fa-refresh"></i></a> <a href="#" class="devolver"><i class="fa fa-pencil"></i></a> <a href="#" class="salida"><i class="fa fa-sign-out"></i></a>');
								}
								else{
									$(this).find('.carrera').html('<a href="#" class="devolver"><i class="fa fa-pencil"></i></a> <a href="#" class="salida"><i class="fa fa-sign-out"></i></a>');
								}
							}
						}
						break;
				}
			}
		});

		$(document).on('mouseleave', '#tabla_horarios tr td', function(e) {
			if ($(this).hasClass('horario') && $(this).html() != "") { // Si el elemento no está vacío (tiene un botón), se elimina el contenido
				switch($(this).data('reservado')){
					case 0:
						$(this).html('');
						break;
					case 1:
						$(this).find('.carrera').html($html);
						break;
					case 2:
						$(this).find('.carrera').html($html);
						break;
				}
			}
		});
		//Botón Reserva en reserva de salas.
		$(document).on('click', '.btn-reserva', function(e) {
			$cell = $(this).parent();
			$th = $(this).closest('table')[0].rows[0].cells[$(this).parent()[0].cellIndex];
			$sala = $($th).data('num-sala');
			$modulo = $(this).parent().parent().children(':first-child').data('id-modulo');
			$('#modal-reserva').modal('show');
			$('#barra-progreso').show();
			$('#rut').val('').focus();
			$('#nombre').val('');
			$('#carrera').val('');
			$('#fecha').val($('#calendar-wrap').data('fecha'));
			$('#sala').val($sala);
			$('#modulo').val($modulo);
			$('.popover').hide();
		});
		
		//Botón reserva, modal Salones.
		$(document).on('click', '.btn-agregar', function(e) {
			e.preventDefault();
			e.stopPropagation();
			console.log($('#form-reserva').serialize());
			if($('#nombre').val()==''){
				alert('Debe ingresar un nombre');
			}
			else{
				$.ajax({
					type: 'POST',
					url: $('#form-reserva').attr('action'),
					data: $('#form-reserva').serialize(),
					dataType: 'JSON',
					success: function(data) {
						console.log(data);
						if (data.error) {
							var n = noty({
								text: data.mensaje,
								type: 'error',
								layout: 'bottomRight',
								timeout: '800'
							});
						} else {
							var n = noty({
								text: '<b>Reservado:</b> ' + data.insertado,
								type: 'alert',
								layout: 'bottomLeft',
								timeout: '800'
							});
							$('#modal-reserva').modal('hide');
							obtenerReservas($('#calendar-wrap').data('fecha'));
						}

					}
				});
			}
		});

		var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
		  clearTimeout (timer);
		  timer = setTimeout(callback, ms);
		 };
		})();

		//validación del Rut.
		$(document).on('input', '#rut', function(e) {
			delay(function(){
				$('#barra-progreso').hide();
				if ($('#rut').val() != "") {
					$('#nombre').val('');
					$('#carrera').val('');
					$.ajax({
						type: 'POST',
						data: {
							rut: $('#rut').val()
						},
						url: $('body').data('url') + 'index.php/reservas/ValidarAlumno',
						dataType: 'JSON',
						success: function(data) {
							console.log(data);
							if (data.error) {
								var n = noty({
									text: 'RUT inválido',
									type: 'error',
									layout: 'bottomRight',
									timeout: '800'
								});
							} else {
								var nombre_alumno = data.alumno.nombre.split(" ");
								var apellido_alumno = data.alumno.apellido.split(" ");
								$('#nombre').val(nombre_alumno[0] + " " + apellido_alumno[0]);
								$('#carrera').val(data.alumno.carrera);
							}
						}
					});
				}
			  }, 1000 );
		});
		//Popover para agregar observacion en salas.
		$(document).on('click', '.observacion', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$('.popover').hide();
			$(this).popover({
				placement: 'bottom',
				//title: 'Añadir Observación'+'<span class="close">&times;</span>',
				title: 'Añadir Observación',
				content: $content_obs,
				html: 'true',
				container: 'body'
			});
			$(this).popover('show');
		});
		//popover para agregar observación en salones.
		$(document).on('click', '.observacion_salon', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$('.popover').hide();
			$(this).popover({
				placement: 'bottom',
				title: 'Añadir Observación',
				content: $content_obs_s,
				html: 'true',
				container: 'body'
			});
			$(this).popover('show');
		});

		/*
		// Cerrar popover de observación.
		$(document).on('click','.close',function(e){
			$('.popover').hide();
		});
		*/

		$(document).on('click','span.carrera>a',function(e){
			$th = $(this).closest('table')[0].rows[0].cells[$(this).parent().parent()[0].cellIndex];
			$sala = $($th).data('num-sala');
			$modulo = $(this).parent().parent().parent().children(':first-child').data('id-modulo');
		});

		$(document).on('click','.plumon',function(e){
			$th = $(this).closest('table')[0].rows[0].cells[$(this).parent()[0].cellIndex];
			$sala = $($th).data('num-sala');
			$modulo = $(this).parent().parent().children(':first-child').data('id-modulo');
		});


		$(document).on('click', '.eliminar', function(e) {
			$('.popover').hide();
			$('#observacion').val('');
			$('#modalEliminar').modal('show');
		});

		/////////////////////////////////
		// Boton cerrar en eliminar salon
	$(document).on('click', '.eliminar_salon', function(e) {
			$('.popover').hide();
			$('#observacion_salon').val('');
			$('#modalEliminar').modal('show');
		});



		/////////////////////////////////
		//Botón confirmar reserva salas.
		$(document).on('click', '.validar', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas/ConfirmarReserva',
				success: function(data) {
					console.log(data);
					/////////////////////////////

					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '1000'
						});
					} else {
						var n = noty({
							text: '<b>Confirmado</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1500'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
///////////////////////////////////////////////////
		//Botón renovar reserva salas.
		$(document).on('click', '.renovar', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas/Renovar',
				success: function(data) {
					console.log(data);
					/////////////////////////////

					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '2000'
						});
					} else {
						var n = noty({
							text: '<b>Renovado</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1000'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
///////////////////////////////////////////////////
///////////////////////////////////////////////////
		//Botón devolver plumon en reserva de salas.
		$(document).on('click', '.devolver', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas/DevolverPlumonBorrador',
				success: function(data) {
					console.log(data);
					/////////////////////////////

					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Modificado</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1000'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
		
		//Botón entregar plumon en reserva de salas.
		$(document).on('click', '.plumon', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas/DevolverPlumonBorrador',
				success: function(data) {
					console.log(data);
					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Modificado</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1000'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
///////////////////////////////////////////////////
///////////////////////////////////////////////////
	//Botón salida en reserva de salas.
		$(document).on('click', '.salida', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas/Salida',
				success: function(data) {
					console.log(data);
					/////////////////////////////

					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Hora Ingresada</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1500'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
///////////////////////////////////////////////////
///////////////////////////////////////////////////
	//Botón salida en reserva de salones.
		$(document).on('click', '.salida_salon', function(e) {
			$('.popover').hide();
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo
				},
				url: $('body').data('url') + 'index.php/reservas2/Salida',
				success: function(data) {
					console.log(data);
					/////////////////////////////

					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Hora Ingresada</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '1500'
						});
					}


					obtenerReservas($('#calendar-wrap').data('fecha'));
				},
				error: function(data){
					console.log(data);
					alert('Ocurrió un error');
				}
			});
		});
///////////////////////////////////////////////////
		//Botón eliminar en reserva de salas.
		$(document).on('click', '#btn-eliminar-reserva', function(e) {
			if($('#observacion').val()==""){
				alert('Debe ingresar una observación');
			}
			else{
				$.ajax({
					type: 'POST',
					dataType: 'JSON',
					data: {
						fecha: $('#calendar-wrap').data('fecha'),
						sala: $sala,
						modulo: $modulo,
						observacion: $('#observacion').val()
					},
					url: $('body').data('url') + 'index.php/reservas/EliminarReserva',
					success: function(data) {
						$('#modalEliminar').modal('hide');

						//////////////////
						if (data.error) {
							var n = noty({
								text: data.mensaje,
								type: 'error',
								layout: 'bottomRight',
								timeout: '800'
							});
						} else {
							var n = noty({
								text: '<b>Eliminado</b>',
								type: 'alert',
								layout: 'bottomLeft',
								timeout: '800'
							});
						}

						obtenerReservas($('#calendar-wrap').data('fecha'));
					},
					error: function(data){
						alert("Ocurrió un error");
					}
				});
			}
		});
		
		//Botón cancelar reserva en reserva en salas.
		$(document).on('click', '#btn-cancelar-reserva', function(e) {
			$('#modalEliminar').modal('hide');
		});

		///////////////////////////////////////////////////

		//Botón eliminar reserva en salones.

		$(document).on('click', '#btn-eliminar-reserva-salon', function(e) {
			if($('#observacion_salon').val()==""){
				alert('Debe ingresar una observación');
			}
			else{
				$.ajax({
					type: 'POST',
					dataType: 'JSON',
					data: {
						fecha: $('#calendar-wrap').data('fecha'),
						sala: $sala,
						modulo: $modulo,
						observacion: $('#observacion_salon').val()
					},
					url: $('body').data('url') + 'index.php/reservas2/EliminarReserva',
					success: function(data) {
						$('#modalEliminar').modal('hide');

						//////////////////
						if (data.error) {
							var n = noty({
								text: data.mensaje,
								type: 'error',
								layout: 'bottomRight',
								timeout: '800'
							});
						} else {
							var n = noty({
								text: '<b>Eliminado</b>',
								type: 'alert',
								layout: 'bottomLeft',
								timeout: '800'
							});
						}

						obtenerReservas($('#calendar-wrap').data('fecha'));
					},
					error: function(data){
						alert("Ocurrió un error");
					}
				});
			}
		});
	//Botón cancelar reserva en reserva en salones.
		$(document).on('click', '#btn-cancelar-reserva-salon', function(e) {
			$('#modalEliminar').modal('hide');
		});
	//Botón observación en reserva de salas.
		$(document).on('click','.btn-add-obs',function(e){
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo,
					observacion: $('#obs_text').val()
				},
				url: $('body').data('url') + 'index.php/reservas/AgregarObservacion',
				success: function(data) {
					console.log(data);

					//////////////////
					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Observación Ingresada</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '800'
						});

						obtenerReservas($('#calendar-wrap').data('fecha'));
					}

					$('.popover').hide();
				},
				error: function(data){
					console.log("error");
					$('.popover').hide();
				}
			}).done(function(){
				$('.popover').hide();
			});
		});
	//Botón observación en reserva de salones.
		$(document).on('click','.btn-add-obs-salon',function(e){
			$.ajax({
				type: 'POST',
				dataType: 'JSON',
				data: {
					fecha: $('#calendar-wrap').data('fecha'),
					sala: $sala,
					modulo: $modulo,
					observacion: $('#obs_text_s').val()
				},
				url: $('body').data('url') + 'index.php/reservas2/AgregarObservacion',
				success: function(data) {
					console.log(data);

					//////////////////
					if (data.error) {
						var n = noty({
							text: data.mensaje,
							type: 'error',
							layout: 'bottomRight',
							timeout: '800'
						});
					} else {
						var n = noty({
							text: '<b>Observación Ingresada</b>',
							type: 'alert',
							layout: 'bottomLeft',
							timeout: '800'
						});
						obtenerReservas($('#calendar-wrap').data('fecha'));
					}

					$('.popover').hide();
				},
				error: function(data){
					console.log("error");
					$('.popover').hide();
				}
			}).done(function(){
				$('.popover').hide();
			});
		});

		$('#tabla_horarios').dataTable({
			"bJQueryUI": false,
			"sDom": '<><t><>',
			"bSort": false,
			"paging": false
		});
	});
})();
