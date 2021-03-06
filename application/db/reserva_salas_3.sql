use reserva_salas_3;

drop table reservas_2;
drop table reservas;
drop table modulos;
drop table alumnos;
drop table usuarios;
drop table parametros;


create table alumnos (
    id_a varchar(40) not null,
    nombre_a varchar(40) not null,
    apellidos_a varchar(40) not null,
    edad_a int not null,
    carrera varchar(30) not null,
    ingreso int not null,
    telefono int,
    email varchar(40),
    constraint id_a_pk primary key (id_a)
);

create table modulos(
	id_mod int not null,
	h_inicio time not null,
	h_fin time not null,
	constraint modulos_pk primary key (id_mod)
);


create table usuarios(
	id_e varchar (40) not null,
	loggin varchar (40) not null,
	password varchar (40) not null,
	estado int not null,
	privilegios int not null,
	constraint id_u_pk primary key (id_e)
);


create table reservas(
	fecha date not null,
	modulo int not null,
	sala int not null,
	eliminada int not null,
	id_a varchar (40) not null,
	nombre_a varchar (50) not null,
	carrera_a varchar (40) not null,
	confirmada int not null, 
	estado int not null, 
	id_e varchar(40) not null,
	observacion varchar (256),
	cant_alumnos int, 
	salida time, 
	plumon_borrador int, 
	constraint reservas_pk primary key (fecha, modulo, sala, eliminada),
	constraint reservas_fk_1 foreign key (id_a) references alumnos (id_a)
	);


create table reservas_2(
	fecha date not null,
	salon int not null,
	hora_entrada varchar (20) not null,
	--hora_entrada time not null,
	eliminada int not null,
	hora_salida varchar (20),
	--hora_salida time,
	salida time,
	--id_p varchar (40) not null,-- 
	nombre varchar (50) not null,
	cant_personas varchar (100),
	estado int not null, 
	id_e varchar(40) not null,
	observacion varchar (256),
	constraint reservas_2_pk primary key (fecha, salon, hora_entrada, eliminada)
);


create table parametros(
	cant_salas int not null,
	plazo_para_reservar int not null,
	n_reservas_diarias int not null,
	cant_salones int not null, 
	max_p_salas int not null,
	max_p_salones int not null,
	constraint parametros_pk primary key (cant_salas, plazo_para_reservar, n_reservas_diarias, cant_salones, max_p_salas, max_p_salones)	
);

INSERT INTO `alumnos` VALUES('1','Patricio Sebastian','Aravena Rojas',20,'Ped. Ed. Fisica',2012,234234,'parvena@mail.cl');
INSERT INTO `alumnos` VALUES('2','Karina Andrea','Pina Allende',21,'Enfermeria',2011,3453213,'kpina@mail.cl');
INSERT INTO `alumnos` VALUES('3','Manuel Eduardo','Wilson Hernandez',22,'Ing. Civil Inf',2011,234565,'mwilson@mail.cl');
INSERT INTO `alumnos` VALUES('4','Felipe Santiago','Jorquera Uribe',22,'Ing. Civil Inf',2011,3456789,'fjorquera@mail.cl');
INSERT INTO `alumnos` VALUES('21','Carolina Francisca','Morales Ramirez',22,'Enfermeria',2011,7777777,'cmorales@mail.cl');

INSERT INTO `usuarios` VALUES('12.435.754-2','fandres','fandres',1,1);
INSERT INTO `usuarios` VALUES('10.532.533-7','lfelipe','lfelipe',1,2);


INSERT INTO `modulos` VALUES(1,'09:00','10:00');
INSERT INTO `modulos` VALUES(2,'10:00','11:00');
INSERT INTO `modulos` VALUES(3,'11:00','12:00');
INSERT INTO `modulos` VALUES(4,'12:00','13:00');
INSERT INTO `modulos` VALUES(5,'13:00','14:00');
INSERT INTO `modulos` VALUES(6,'15:00','16:00');
INSERT INTO `modulos` VALUES(7,'16:00','17:00');
INSERT INTO `modulos` VALUES(8,'17:00','18:00');


INSERT INTO `parametros` VALUES (9,2,1,2,5,15);



insert into alumnos values (0, 'Bloqueada', 'No Disponible', 0, 'UCM', 0, 0, '@ucm.cl')
INSERT INTO usuarios VALUES('12.312.312-3','jmanuel','jmanuel',1,1);	