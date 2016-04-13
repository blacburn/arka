-- Registro Pagina , bloque y relación Pagina-bloque

INSERT INTO poseidon.hipocampo_pagina(nombre, descripcion, modulo, nivel, parametro)
VALUES ('visualizador','Página que permite la visualizacion de los  datos Geograficos','Geografico','0','jquery=true')
RETURNING id_pagina;

INSERT INTO poseidon.hipocampo_bloque(nombre, descripcion, grupo)
VALUES ('visualizador','Bloque que permite la visualizacion de los datos Geograficos','logica/datosGeograficos') 
RETURNING id_bloque;

INSERT INTO poseidon.hipocampo_bloque_pagina(id_pagina, id_bloque, seccion, posicion)
    VALUES 
((SELECT id_pagina FROM poseidon.hipocampo_pagina WHERE nombre='visualizador'),(SELECT id_bloque FROM poseidon.hipocampo_bloque WHERE nombre='visualizador'),'C','1');
-- Registro de pagina 'Banner'
INSERT INTO poseidon.hipocampo_bloque_pagina(id_pagina, id_bloque, seccion, posicion)
    VALUES ((SELECT id_pagina FROM poseidon.hipocampo_pagina WHERE nombre='visualizador'),'2','A','1');

-- Registro de pagina 'Menu'
INSERT INTO poseidon.hipocampo_bloque_pagina(id_pagina, id_bloque, seccion, posicion)
    VALUES ((SELECT id_pagina FROM poseidon.hipocampo_pagina WHERE nombre='visualizador'),'3','B','1');

-- Registro de pagina 'Pie pagina'
INSERT INTO poseidon.hipocampo_bloque_pagina(id_pagina, id_bloque, seccion, posicion)
    VALUES ((SELECT id_pagina FROM poseidon.hipocampo_pagina WHERE nombre='visualizador'),'4','E','1');
    
