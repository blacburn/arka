 --Crea esquema y asigna permisos
CREATE SCHEMA usuarios
  AUTHORIZATION ecosiis;

--Tabla de acceso (esquema de usuarios)
--Tabla de Acceso	   
CREATE TABLE usuarios.acceso
(
  acc_id serial NOT NULL,
  acc_codigo text UNIQUE NOT NULL,
  acc_usuario text NOT NULL,
  acc_detalle text NOT NULL,
  acc_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  CONSTRAINT acceso_pk PRIMARY KEY (acc_id)
  )
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.acceso
  OWNER TO ecosiis;

--Tabla de permisos (esquema de usuarios)
--------------Tabla permiso
CREATE TABLE usuarios.permiso
(
  permiso_id serial NOT NULL,
  permiso_nombre text NOT NULL,
  permiso_alias text NOT NULL,
  permiso_descripcion text NOT NULL,
  CONSTRAINT permiso_pk PRIMARY KEY (permiso_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.permiso
  OWNER TO ecosiis;

insert into usuarios.permiso
(permiso_id,permiso_nombre, permiso_alias, permiso_descripcion)
values
(0,'propietario','Propietario','Propietario del elemento'),
(1,'crear','Crear','Crea el elemento'),
(2,'consultar','Consultar','Consulta el elemento'),
(3,'actualizar','Actualizar','Actualiza el elemento'),
(4,'eliminar','Eliminar','Elimina el elemento'),
(5,'administrador','Administrador','Administrador'),
(6,'ejecutar','Ejecutar','Ejecutar accion del elemento');

--Tabla de usuario  (esquema de usuarios)
------------tabla usuario

CREATE TABLE usuarios.usuario
(
  usuario_id serial NOT NULL,
  usuario_nombre text NOT NULL,
  usuario_alias text NOT NULL,
  usuario_descripcion text NOT NULL,
  CONSTRAINT usuario_pk PRIMARY KEY (usuario_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.usuario
  OWNER TO ecosiis;
--Tabla de rol  (esquema de usuarios)
------Tabla Rol
CREATE TABLE usuarios.rol
(
  rol_id serial NOT NULL,
  rol_nombre text NOT NULL,
  rol_alias text NOT NULL,
  rol_descripcion text NOT NULL,
  CONSTRAINT rol_pk PRIMARY KEY (rol_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.rol
  OWNER TO ecosiis;
  
--Tabla de usuario_rol  (esquema de usuarios)
-----------------tabla rol_usuario

CREATE TABLE usuarios.rol_usuario
(
  rol_usuario_id serial NOT NULL,
  rol_id integer NOT NULL,
  usuario_id integer NOT NULL,
  estado_paso_id integer NOT NULL,
  CONSTRAINT rol_fk FOREIGN KEY (rol_id)
      REFERENCES usuarios.rol (rol_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT usuario_fk FOREIGN KEY (usuario_id)
      REFERENCES usuarios.usuario (usuario_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT rol_usuario_pk PRIMARY KEY (rol_usuario_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.rol_usuario
  OWNER TO ecosiis;


--Tabla de relaciones o de permisos_usuarios_objeto_registro  (esquema de usuarios)
CREATE TABLE usuarios.relaciones
(
  rel_id serial NOT NULL,
  usuario_id integer NOT NULL,
  objetos_id integer NOT NULL,
  rel_registro integer NOT NULL,
  permiso_id integer NOT NULL,
  estado_registro_id	 integer NOT NULL ,
  rel_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  
  CONSTRAINT relaciones_pk PRIMARY KEY (rel_id),
  CONSTRAINT relaciones_objetos_fk FOREIGN KEY (objetos_id)
      REFERENCES core.core_objetos (objetos_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_estados_fk FOREIGN KEY (estado_registro_id)
      REFERENCES core.core_estado_registro (estado_registro_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_permiso_fk FOREIGN KEY (permiso_id)
      REFERENCES usuarios.permiso (permiso_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_usuario_fk FOREIGN KEY (usuario_id)
      REFERENCES usuarios.usuario (usuario_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL
  
  
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.relaciones
  OWNER TO ecosiis;
  
  --se agrega un usuario como administrador para poder probar
----INSERT INTO reglas.relaciones (usuario_id , objetos_id ,rel_registro, rel_permiso , rel_estado)
    ----                      VALUES ( 11 , 0 , 0 , 5 , 1);
 
 
CREATE TABLE usuarios.relaciones_h
(
  
  rel_h_id serial NOT NULL,
  rel_id_h integer NOT NULL,
  usuario_id_h integer NOT NULL,
  objetos_id_h integer NOT NULL,
  rel_registro_h integer NOT NULL,
  permiso_id_h integer NOT NULL,
  estado_registro_id_h	 integer NOT NULL ,
  rel_fecha_registro_h date NOT NULL ,
  rel_h_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  rel_h_usuario text NOT NULL,
  rel_h_justificacion text NOT NULL DEFAULT 0,
  CONSTRAINT relaciones_h_pk PRIMARY KEY (rel_h_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuarios.relaciones_h
  OWNER TO ecosiis;
  

---------------------



  