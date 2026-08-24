## Promt
### Modulo de perfil en sami_hebreo

Este modulo ya está existente en la versión de sami_royal, la idea es traer ese modulo a sami_hebreo.

Necesito que traigas todo lo correspondiente de la versión de sami_royal, modeo, controlladores, vistas, incluso los archivos de estilos actualizados con nuevos colores. 

Sigue el mismo patrón de diseño, de estructura de archivos, funciones, metodos, controladores, vistas, etc. 

Las tablas de las bases de datos son las siguientes, no olvides añadirlas: 
```sql
CREATE TABLE `info_adicional_usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tipo_documento` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_expedicion` DATE DEFAULT NULL,
  `departamento_nacimiento` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` DATE DEFAULT NULL,
  `direccion_vivienda` VARCHAR(260) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genero` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ultimo_nivel_educativo` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo_personal` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estrato` INT DEFAULT NULL,
  `id_user` INT NOT NULL,
  `fecha_reg` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cedula_doc` VARCHAR(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `certificado_formacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_formacion` INT NOT NULL,
  `id_user` INT NOT NULL,
  `nombre_archivo` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fechareg` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `formacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NOT NULL,
  `tipo_formacion` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `programa` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `institucion` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_grado` DATE NOT NULL,
  `fecha_expedicion_certi` DATE NOT NULL,
  `duracion` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `experiencia_laboral` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_empresa` VARCHAR(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cargo` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_ingreso` DATE DEFAULT NULL,
  `fecha_retiro` DATE DEFAULT NULL,
  `id_user` INT NOT NULL,
  `certificado_trabajo` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fechareg` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `fecha_certificado` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `documentos_varios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tipo_doc` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` INT DEFAULT NULL,
  `nombre_doc` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fechareg` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `produccion_intelectual` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NOT NULL,
  `tipo_produccion` VARCHAR(100) NOT NULL,
  `denominacion` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(200) NOT NULL,
  `objetivo` VARCHAR(200) DEFAULT NULL,
  `descripcion_actividades` VARCHAR(500) NOT NULL,
  `duracion` VARCHAR(100) NOT NULL,
  `lugar` VARCHAR(250) NOT NULL,
  `observacion` VARCHAR(300) NOT NULL,
  `evidencia_pdf` VARCHAR(250) NOT NULL,
  `fechareg` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_user` (`id_user`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8mb3;

```

En el chat de la terminal te diré las ubicaciones en la cual están los archivos que debes traer y agregar a sami_hebreo.
Primero se ejecuta el plan, luego lo implementamos. 