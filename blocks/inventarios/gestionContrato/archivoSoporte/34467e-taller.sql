-- Quien es el estudiante que perdio mas materias


select nom_e from inscribe natural join estudiantes
	where n1*0.35+n2*0.35+n3*0.3 < 3
	group by cod_e, nom_e
	having count(*)=
(select count(*) c from inscribe natural join estudiantes
	where n1*0.35+n2*0.35+n3*0.3 < 3
	group by cod_e, nom_e
	order by c desc limit 1)
	
-- Nombre del profesor con mayor porcentaje de pérdida
create view v1 as
	select *,tot_p*100/tot porc from 
		(select cod_a, id_p, count(*) tot_p from inscribe natural join imparte
			where n1*0.35+n2*0.35+n3*0.3 < 3
			group by cod_a, id_p) t1
		natural join
		(select cod_a, id_p, count(*) tot from inscribe natural join imparte
			group by cod_a, id_p) t2
select nom_p from v1 natural join profesores
	where porc=
	(select max(porc) from v1)
	
-- Autor referenciado por mas asignaturas diferentes

select id_a, nom_a  from referencia natural join escribe natural join autor
	group by id_a,nom_a
	having count(*)=
	(select count(*) c  from referencia natural join escribe natural join autor
	group by id_a,nom_a order by c desc limit 1)
	
	


