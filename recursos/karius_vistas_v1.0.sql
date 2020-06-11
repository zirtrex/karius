/********************************************
 *******************VISTAS*******************
 ********************************************/

USE karius_db;

CREATE VIEW ks_vw_traslado
AS
  SELECT t.cod_traslado, t.fecha_traslado, t.punto_partida, t.punto_llegada,
		t.hora_llegada, t.temperatura_llegada, t.humedad_relativa_llegada,
        t.hora_salida, t.temperatura_salida, t.humedad_relativa_salida, t.total,
		c.cod_cliente, c.razon_social, c.direccion_legal,
        v.cod_vehiculo, v.marca, v.placa, v.modelo, v.color, v.soat,
        cr.cod_conductor, cr.nombres, cr.apellidos, cr.numero_licencia,        
        u.cod_usuario, u.nombres as u_nombres, u.apellidos as u_apellidos, u.correo, u.telefono
  FROM ks_traslado t
  INNER JOIN ks_cliente c ON c.cod_cliente = t.cod_cliente
  INNER JOIN ks_vehiculo v ON v.cod_vehiculo = t.cod_vehiculo  
  INNER JOIN ks_conductor cr ON cr.cod_conductor = t.cod_conductor
  INNER JOIN ks_usuario u ON u.cod_usuario = t.cod_usuario;
  


