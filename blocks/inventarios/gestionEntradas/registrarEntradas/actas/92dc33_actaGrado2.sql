SELECT to_char(current_timestamp,'yyyymmdd') hoy,
to_char(egr_fecha_grado,'yyyymmdd') grado,
CASE WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') THEN to_char(egr_fecha_grado,'dd') ELSE P[DIA_EXP] END DIA_EXPEDIDO,
(CASE WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='01' THEN 'ENERO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='02' THEN 'FEBRERO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='03' THEN 'MARZO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='04' THEN 'ABRIL' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='05' THEN 'MAYO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='06' THEN 'JUNIO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='07' THEN 'JULIO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='08' THEN 'AGOSTO' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='09' THEN 'SEPTIEMBRE' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='10' THEN 'OCTUBRE' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='11' THEN 'NOVIEMBRE' 
WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') AND to_char(egr_fecha_grado,'mm') ='12' THEN 'DICIEMBRE' 
ELSE P[MES_EXP] END ) MES_EXPEDIDO,
CASE WHEN to_char(egr_fecha_grado,'yyyymmdd')>to_char(current_timestamp,'yyyymmdd') THEN to_char(egr_fecha_grado,'yyyy') ELSE P[ANIO_EXP] END ANIO_EXPEDIDO 
from acest 
inner join acegresado ON egr_est_cod=est_cod
where est_cod=P[COD_ESTUDIANTE]
