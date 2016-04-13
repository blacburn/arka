
--Tabla modificada logger de acceso 
--Tabla de Acceso
DROP TABLE arka_logger;	   
CREATE TABLE public.arka_logger
(
  id_logger serial NOT NULL,
  id_usuario text NOT NULL,
  codigo text UNIQUE NOT NULL,
  evento text NOT NULL,
  fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  CONSTRAINT acceso_pk PRIMARY KEY (id_logger)
  )
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_logger
  OWNER TO arka_frame;

--Tabla de permisos (esquema de usuarios)
--------------Tabla permiso
CREATE TABLE public.arka_permiso
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
ALTER TABLE public.arka_permiso
  OWNER TO arka_frame;

insert into public.arka_permiso
(permiso_id,permiso_nombre, permiso_alias, permiso_descripcion)
values
(0,'propietario','Propietario','Propietario del elemento'),
(1,'crear','Crear','Crea el elemento'),
(2,'consultar','Consultar','Consulta el elemento'),
(3,'actualizar','Actualizar','Actualiza el elemento'),
(4,'eliminar','Eliminar','Elimina el elemento'),
(5,'administrador','Administrador','Administrador'),
(6,'validar','Validar','Validar registro elemento'),
(7,'ejecutar','Ejecutar','Ejecutar accion del elemento')


;

--Tabla de usuario  (esquema de usuarios)
------------tabla usuario


CREATE TABLE public.arka_usuario
(
  id_usuario serial NOT NULL,
  nombre text NOT NULL DEFAULT '',
  apellido text NOT NULL DEFAULT '',
  alias text ,
  descripcion text ,
  ---multivaluado otra tabla--correo text NOT NULL DEFAULT '',
  ---multivaluado otra tabla---telefono text NOT NULL DEFAULT '',
  imagen text ,
  clave text NOT NULL DEFAULT '',
  tipo text NOT NULL DEFAULT '',
  estilo text NOT NULL DEFAULT '',
  idioma text NOT NULL DEFAULT 'es_es',
  estado_registro_id integer NOT NULL DEFAULT 0,
  fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  CONSTRAINT arka_usuario_pkey PRIMARY KEY (id_usuario)
)WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_usuario
  OWNER TO arka_frame;
  

CREATE TABLE public.arka_usuario_correo
(
  usuario_correo_id serial NOT NULL,
  id_usuario integer NOT NULL,
  usuario_correo_correo integer NOT NULL,
  CONSTRAINT usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.arka_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT arka_usuario_correo_pkey PRIMARY KEY (usuario_correo_id)
)WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_usuario_correo
  OWNER TO arka_frame;

CREATE TABLE public.arka_usuario_telefono
(
  usuario_telefono_id serial NOT NULL,
  id_usuario integer NOT NULL,
  usuario_telefono_telefono integer NOT NULL,
  CONSTRAINT usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.arka_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT arka_usuario_telefono_pkey PRIMARY KEY (usuario_telefono_id)
)WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_usuario_telefono
  OWNER TO arka_frame;
  

CREATE TABLE public.arka_usuario_pagina
(
  usuario_pagina_id serial NOT NULL,
  id_usuario integer NOT NULL,
  id_pagina integer NOT NULL,
  CONSTRAINT usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.arka_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT pagina_fk FOREIGN KEY (id_pagina)
      REFERENCES public.arka_pagina (id_pagina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT arka_usuario_pagina_pkey PRIMARY KEY (usuario_pagina_id)
)WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_usuario_pagina
  OWNER TO arka_frame;


--Tabla de rol  (esquema de usuarios)
------Tabla Rol
CREATE TABLE public.arka_rol
(
  rol_id serial NOT NULL,
  rol_nombre text NOT NULL,
  rol_alias text NOT NULL,
  rol_descripcion text NOT NULL,
  estado_registro_id integer NOT NULL,
  rol_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
    CONSTRAINT relaciones_estados_fk FOREIGN KEY (estado_registro_id)
      REFERENCES public.arka_estado_registro (estado_registro_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT rol_pk PRIMARY KEY (rol_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_rol
  OWNER TO arka_frame;
  

CREATE TABLE public.arka_rol_pagina
(
  rol_pagina_id serial NOT NULL,
  rol_id integer NOT NULL,
  id_pagina integer NOT NULL,
  CONSTRAINT rol_fk FOREIGN KEY (rol_id)
      REFERENCES public.arka_rol (rol_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT pagina_fk FOREIGN KEY (id_pagina)
      REFERENCES public.arka_pagina (id_pagina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT arka_rol_pagina_pkey PRIMARY KEY (rol_pagina_id)
)WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_rol_pagina
  OWNER TO arka_frame;




--Tabla de usuario_rol  (esquema de usuarios)
-----------------tabla rol_usuario

CREATE TABLE public.arka_rol_usuario
(
  rol_usuario_id serial NOT NULL,
  rol_id integer NOT NULL,
  id_usuario integer NOT NULL,
  estado_registro_id integer NOT NULL,
  rol_usuario_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  CONSTRAINT relaciones_estados_fk FOREIGN KEY (estado_registro_id)
      REFERENCES public.arka_estado_registro (estado_registro_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT rol_fk FOREIGN KEY (rol_id)
      REFERENCES public.arka_rol (rol_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.arka_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT rol_usuario_pk PRIMARY KEY (rol_usuario_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_rol_usuario
  OWNER TO arka_frame;


--Tabla de relaciones o de permisos_usuarios_objeto_registro  (esquema de usuarios)
CREATE TABLE public.arka_relaciones_usuario
(
  rel_id serial NOT NULL,
  id_usuario integer NOT NULL,
  objetos_id integer NOT NULL,
  rel_registro integer NOT NULL,
  permiso_id integer NOT NULL,
  estado_registro_id	 integer NOT NULL ,
  rel_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  
  CONSTRAINT relaciones_pk PRIMARY KEY (rel_id),
  CONSTRAINT relaciones_objetos_fk FOREIGN KEY (objetos_id)
      REFERENCES public.arka_objetos (objetos_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_estados_fk FOREIGN KEY (estado_registro_id)
      REFERENCES public.arka_estado_registro (estado_registro_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_permiso_fk FOREIGN KEY (permiso_id)
      REFERENCES public.arka_permiso (permiso_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_usuario_fk FOREIGN KEY (id_usuario)
      REFERENCES public.arka_usuario (id_usuario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL
  
  
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_relaciones_usuario
  OWNER TO arka_frame;
  
----se agrega un usuario como administrador para poder probar
----INSERT INTO reglas.relaciones (id_usuario , objetos_id ,rel_registro, rel_permiso , rel_estado)
    ----                      VALUES ( 11 , 0 , 0 , 5 , 1);
 
 
CREATE TABLE public.arka_relaciones_usuario_h
(
  
  rel_h_id serial NOT NULL,
  rel_id_h integer NOT NULL,
  id_usuario_h integer NOT NULL,
  objetos_id_h integer NOT NULL,
  rel_registro_h integer NOT NULL,
  permiso_id_h integer NOT NULL,
  estado_registro_id_h	 integer NOT NULL ,
  rel_fecha_registro_h date NOT NULL ,
  rel_h_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  rel_h_usuario text NOT NULL,
  rel_h_justificacion text NOT NULL DEFAULT 0,
  CONSTRAINT relaciones_usuario_h_pk PRIMARY KEY (rel_h_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_relaciones_usuario_h
  OWNER TO arka_frame;
  

--Tabla de relaciones o de permisos_usuarios_objeto_registro  (esquema de usuarios)
CREATE TABLE public.arka_relaciones_rol
(
  rel_id serial NOT NULL,
  rol_id integer NOT NULL,
  objetos_id integer NOT NULL,
  rel_registro integer NOT NULL,
  permiso_id integer NOT NULL,
  estado_registro_id	 integer NOT NULL ,
  rel_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  
  CONSTRAINT relaciones_rol_pk PRIMARY KEY (rel_id),
  CONSTRAINT relaciones_objetos_fk FOREIGN KEY (objetos_id)
      REFERENCES public.arka_objetos (objetos_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_estados_fk FOREIGN KEY (estado_registro_id)
      REFERENCES public.arka_estado_registro (estado_registro_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_permiso_fk FOREIGN KEY (permiso_id)
      REFERENCES public.arka_permiso (permiso_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT relaciones_rol_fk FOREIGN KEY (rol_id)
      REFERENCES public.arka_rol (rol_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL
  
  
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_relaciones_usuario
  OWNER TO arka_frame;
  
----se agrega un usuario como administrador para poder probar
----INSERT INTO reglas.relaciones (id_usuario , objetos_id ,rel_registro, rel_permiso , rel_estado)
    ----                      VALUES ( 11 , 0 , 0 , 5 , 1);
 
 
CREATE TABLE public.arka_relaciones_rol_h
(
  
  rel_h_id serial NOT NULL,
  rel_id_h integer NOT NULL,
  rol_id_h integer NOT NULL,
  objetos_id_h integer NOT NULL,
  rel_registro_h integer NOT NULL,
  permiso_id_h integer NOT NULL,
  estado_registro_id_h	 integer NOT NULL ,
  rel_fecha_registro_h date NOT NULL ,
  rel_h_fecha_registro date NOT NULL DEFAULT ('now'::text)::date,
  rel_h_usuario text NOT NULL,
  rel_h_justificacion text NOT NULL DEFAULT 0,
  CONSTRAINT relaciones_rol_h_pk PRIMARY KEY (rel_h_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.arka_relaciones_rol_h
  OWNER TO arka_frame;

---------------------



  