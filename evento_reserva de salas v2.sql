SET GLOBAL event_scheduler = ON;
SET SQL_SAFE_UPDATES = 0;
SHOW EVENTS FROM reserva_salas_3;
SHOW PROCESSLIST;

DROP EVENT eliminar_no_confirmada;
delimiter |
CREATE EVENT eliminar_no_confirmada
ON SCHEDULE every 1 hour
STARTS '2014-11-11 15:15:00'
ON COMPLETION PRESERVE
DO
BEGIN
	DECLARE aux_fecha date;
	DECLARE aux_modulo int;
	DECLARE aux_sala int;
	DECLARE aux_eliminada int;
	DECLARE cursor_reserva CURSOR for
	Select  fecha, modulo, sala, eliminada
	from reservas
	where fecha = date_format(now(), '%Y-%m-%d') and
		modulo = (select id_mod from modulos where h_inicio = concat(substring(now(),12,2), ':00:00')) and
		curtime() > concat(substring((select h_inicio from modulos where h_inicio = concat(substring(now(),12,2), ':00:00')),1,2), ':15:00') and
		eliminada = 0  and
		confirmada = 0 and
		estado = 1 ;
	OPEN cursor_reserva;
	loop_cursor: LOOP
	FETCH cursor_reserva INTO aux_fecha, aux_modulo, aux_sala, aux_eliminada;
		if (aux_fecha is not null) then
			update reservas set eliminada = 1 where fecha = aux_fecha and modulo = aux_modulo and sala = aux_sala and eliminada = aux_eliminada;
		else
			LEAVE loop_cursor;
		end if;
	end LOOP loop_cursor;
	CLOSE cursor_reserva;
END |
delimiter ;