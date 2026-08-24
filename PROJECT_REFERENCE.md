# S.A.M.I. - Referencia Completa del Proyecto

> **Sistema Administrativo de Manejo de Inventario**
> Institución: Colegio Hebreo Unión / Royal School
> Repositorio: https://github.com/johaoRamirezRoyal/sami_hebreo
> Copyright: 2018
> Última revisión del documento: Julio 2026

---

## 1. IDENTIDAD DEL PROYECTO

S.A.M.I. es un **sistema de gestión institucional escolar** construido como aplicación PHP MVC personalizada (SIN frameworks). Cubre: inventario, mantenimientos, biblioteca, admisiones, matrícula, recursos humanos, contabilidad, enfermería, salones, compras, proveedores, reportes, zonas comunes, asistencia, citaciones, prom, eventos, renovaciones, carnet y más.

---

## 2. ARQUITECTURA GENERAL

```
Tipo: PHP MVC custom (sin framework)
Framework: Ninguno vanilla PHP
PHP target: 7.4 (desplegado en cPanel)
Desarrollo local: XAMPP (Windows)
Base de datos: MySQL (PDO, sin ORM)
Frontend: Bootstrap 4 (SB Admin 2) + jQuery
Autoload: NO hay. Todos los archivos se cargan con require_once manual
```

### Flujo de Request

```
Browser → .htaccess (mod_rewrite) → index.php
  → confi/Config.php (define constantes de ruta)
  → EnlacesControl::CargarPlantilla()
    → vistas/template.php
      → EnlacesControl::EnlacesPaginas()
        → $_GET['url'] se sanea con FILTER_SANITIZE_URL
        → EnlacesModelo::DevolverVistaAdmin($url)
          → Mapea URL a vistas/modulos/{$url}.php
          → Si no existe → vistas/modulos/404.php
      → Se incluye la vista dentro del template:
        cabeza.php → navegacion.php → [vista_del_modulo] → script_and_final.php
```

### Flujo de Autenticación

```
Cada vista incluye navegacion.php al inicio
  → navigation.php verifica $_SESSION['rol']
  → Si NO existe sesión → destruye sesión → redirige a login?er=2
  → Si existe → carga sidebar con menú basado en permisos
  → Cada módulo verifica permisos individuales vía permisosUsuarioControl()
```

### Flujo de Formularios

```
<form method="POST" action=""> (misma URL)
  → Al enviar, la vista procesa con isset($_POST['campo'])
  → Llama al método del controlador correspondiente
  → El controlador valida, ejecuta SQL vía el modelo
  → Respuesta: ohSnap() toast o window.location.replace()
```

### Flujo AJAX

```
JavaScript (funciones*.js) → $.ajax() → vistas/ajax/modulo/accion.php
  → El archivo AJAX incluye el controlador y modelo
  → Ejecuta la lógica
  → Retorna JSON o HTML
  → JavaScript procesa la respuesta
```

---

## 3. ESTRUCTURA DE DIRECTORIOS

```
sami_hebreo/
├── index.php                          # Front controller / punto de entrada
├── .htaccess                          # Apache mod_rewrite
├── confi/
│   └── Config.php                     # Constantes: BASE_URL, rutas
│
├── app/
│   ├── controlador/                   # CONTROLADORES
│   │   ├── EnlacesControl.php         # Router de URLs
│   │   ├── ControlSession.php         # Login/sesión (singleton)
│   │   ├── Session.php                # Wrapper de sesiones PHP
│   │   ├── hash.php                   # Hashing de contraseñas (bcrypt)
│   │   ├── numeros.php                # Utilidades (subida archivos, fechas, números)
│   │   ├── admisiones/ControlAdmisiones.php
│   │   ├── areas/ControlAreas.php
│   │   ├── asistencia/ControlAsistencia.php
│   │   ├── biblioteca/ControlBiblioteca.php
│   │   ├── categorias/ControlCategorias.php
│   │   ├── citaciones/ControlCitaciones.php
│   │   ├── contabilidad/ControlContabilidad.php
│   │   ├── enfermeria/ControlEnfermeria.php
│   │   ├── extra/ControlExtra.php
│   │   ├── hoja_vida/ControlHojaVida.php
│   │   ├── inventario/ControlInventario.php  (4139 líneas, el más grande)
│   │   ├── matricula/ControlMatricula.php
│   │   ├── padres/ControlPadres.php
│   │   ├── perfil/ControlPerfil.php
│   │   ├── permisos/ControlPermisos.php
│   │   ├── prefactura/ControlPrefactura.php
│   │   ├── proveedor/ControlProveedor.php
│   │   ├── recursos/ControlRecursos.php
│   │   ├── renovacion/ControlRenovacion.php
│   │   ├── reportes/ControlReportes.php
│   │   ├── salon/ControlSalon.php
│   │   ├── solicitud/ControlSolicitud.php
│   │   ├── usuarios/ControlUsuarios.php
│   │   └── zonas/ControlZonas.php
│   │
│   ├── modelo/                        # MODELOS
│   │   ├── conexion.php               # Conexión PDO principal (sami_hebreo)
│   │   ├── conexion_bio.php           # Conexión PDO bioseguridad
│   │   ├── conexion_extra.php         # Conexión PDO extra
│   │   ├── configMail.php             # Configuración SMTP
│   │   ├── EnlacesModelo.php          # Mapeador URL → vista
│   │   ├── IngresoModel.php           # Queries de login
│   │   ├── correo/ModeloCorreos.php   # Envío de correos (PHPMailer)
│   │   ├── [24 subdirectorios con Modelo*.php por módulo]
│   │
│   └── lib/                           # Librerías PHP (vendored manualmente)
│       ├── PHPMailer/                 # Email SMTP
│       ├── tcpdf/                     # Generación PDF (6.3.2)
│       ├── fpdf/                      # Generación PDF alternativo
│       ├── bardcode/                  # Generación códigos de barras
│       ├── phpqrcode/                 # Generación QR
│       ├── Qrlector/                  # Lectura de QR
│       └── PhpSpreadsheet/            # Lectura/escritura Excel/CSV
│
├── vistas/                            # VISTAS
│   ├── template.php                   # Cargador principal del template
│   ├── cabeza.php                     # HTML head, CSS, body opener
│   ├── navegacion.php                 # Sidebar + topbar + auth guard
│   ├── pie.php                        # Footer (Copyright 2018)
│   ├── script_and_final.php           # JS comunes al final
│   ├── modulos/                       # ~120+ archivos PHP de vistas (43 dirs)
│   │   ├── login.php
│   │   ├── inicio.php
│   │   ├── 403.php, 404.php
│   │   ├── [43 subdirectorios de módulos]
│   └── ajax/                          # 54+ endpoints AJAX (21 dirs)
│       ├── [21 subdirectorios]
│
└── public/                            # Assets estáticos
    ├── css/                           # 10 archivos CSS
    ├── js/                            # 37 archivos JS (custom + módulo)
    ├── scss/                          # 15 archivos SCSS
    ├── img/                           # Logo, fondo, iconos
    └── vendor/                        # jQuery, Bootstrap, FA, Chart.js, DataTables
```

---

## 4. CONFIGURACIÓN

### `confi/Config.php`

| Constante | Valor |
|---|---|
| `BASE_URL` | `http://localhost/sami_hebreo/` |
| `PUBLIC_PATH` | `{BASE_URL}public/` |
| `VISTA_PATH` | `{ROOT}/vistas/` |
| `CONTROL_PATH` | `{ROOT}/app/controlador/` |
| `MODELO_PATH` | `{ROOT}/app/modelo/` |
| `LIB_PATH` | `{ROOT}/app/lib/` |
| `PUBLIC_PATH_ARCH` | `{ROOT}/public/` |

### Bases de datos (3 conexiones)

| Archivo | BD | Host | User | Pass | Propósito |
|---|---|---|---|---|---|
| `conexion.php` | `sami_hebreo` | localhost | root | (vacío) | Principal |
| `conexion_bio.php` | `bioseguridad` | localhost | root | `controlsoft123` | Bioseguridad |
| `conexion_extra.php` | `extra` | localhost | root | `controlsoft123` | Actividades extra |

### Correo SMTP (`configMail.php`)

| Campo | Valor |
|---|---|
| SMTP | `smtp.gmail.com` |
| Puerto | `465` |
| Encriptación | SSL |
| Usuario | `inventario@royalschool.edu.co` |
| Password | (Gmail App Password en texto plano) |
| Nombre remitente | `CHU Administrador S.A.M.I` |

### PHP Config

| Setting | Valor |
|---|---|
| `max_execution_time` | 300s |
| `memory_limit` | 1024M |
| `upload_max_filesize` | 1024M |
| `post_max_size` | 1024M |
| `session.gc_maxlifetime` | 84600s (23.5h) |

---

## 5. SISTEMA DE ROUTING

### Mecanismo

- Apache `.htaccess` reescribe todo a `index.php?url=$1`
- `EnlacesControl::EnlacesPaginas()` lee `$_GET['url']`
- `EnlacesModelo::DevolverVistaAdmin()` mapea a `vistas/modulos/{$url}.php`
- Si el archivo no existe → `404.php`
- **No hay named routes, route params, middleware, ni route groups**

### URLs principales por módulo

| URL | Vista | Método HTTP |
|---|---|---|
| `(vacío/default)` | `login.php` | GET, POST |
| `login` | `login.php` | GET, POST |
| `salir` | `salir.php` | GET (logout) |
| `inicio` | `inicio.php` | GET, POST |
| `restablecer_pass` | `restablecer_pass.php` | GET, POST |
| `ayuda` | `ayuda.php` | GET |
| **Usuarios** | | |
| `usuarios/index` | Listado de usuarios | GET, POST |
| `usuarios/agregarUsuario` | Agregar usuario | GET, POST |
| `consultausuarios/index` | Consulta usuarios | GET, POST |
| **Inventario** | | |
| `inventario/index` | Listado inventario | GET, POST |
| `inventario/listado` | Listado inventario vista | GET |
| `inventario/agregarInvetario` | Agregar inventario (modal) | GET, POST |
| `inventario/confirmar` | Confirmación inventario | GET, POST |
| `inventario/panelControl` | Panel de control | GET |
| `inventario/panelControl2` | Panel de control 2 | GET |
| `inventario/reasignar` | Reasignar áreas | GET, POST |
| `inventario/descontinuados` | Descontinuados | GET |
| `inventario/trabajoCasa` | Trabajo en casa | GET |
| **Áreas** | | |
| `areas/index` | Listado áreas | GET, POST |
| `areas/agregarArea` | Agregar área | GET, POST |
| `areas/reasignar` | Reasignar | GET |
| **Categorías** | | |
| `categorias/index` | CRUD categorías | GET, POST |
| **Reportes** | | |
| `reportes/index` | Listado reportes | GET, POST |
| `reportes/visto` | Visto bueno | GET |
| `reportes/excelInventario` | Exportar Excel | GET |
| **Mantenimientos** | | |
| `mantenimientos/index` | Listado mantenimientos | GET, POST |
| `mantenimientos/programarMant` | Programar mantenimiento | GET, POST |
| `mantenimientos/historial_mantenimiento` | Historial | GET |
| `mantenimientos/areas` | Por áreas | GET |
| **Listado** | | |
| `listado/index` | Listado general | GET |
| `listado/historial` | Historial | GET |
| `listado/mantenimientos` | Mantenimientos | GET |
| `listado/panelControl/reportar` | Reportar | GET |
| `listado/panelControl/liberar` | Liberar | GET |
| `listado/panelControl/descontinuar` | Descontinuar | GET |
| **Zonas** | | |
| `zonas/index` | CRUD zonas | GET, POST |
| `zonas/agregarZona` | Agregar zona | GET, POST |
| `zonas/agregarArea` | Agregar área zona | GET, POST |
| `zonas/reportes` | Reportes zonas | GET |
| **Salón** | | |
| `salon/index` | CRUD salones | GET, POST |
| `salon/agregarSalon` | Agregar salón | GET, POST |
| `salon/apartar` | Reservar | GET, POST |
| `salon/pendientes` | Pendientes | GET |
| `salon/reservas` | Mis reservas | GET |
| `salon/diario` | Programación diaria | GET |
| `salon/disponibilidad` | Disponibilidad | GET |
| **Biblioteca** | | |
| `biblioteca/index` | Hub biblioteca | GET |
| `biblioteca/libros/index` | CRUD libros | GET |
| `biblioteca/libros/agregar_libro` | Agregar libro | GET, POST |
| `biblioteca/libros/informacion` | Info libro | GET |
| `biblioteca/prestamos/index` | Préstamos | GET |
| `biblioteca/prestamos/grupo` | Préstamo grupo | GET |
| `biblioteca/devolucion/index` | Devoluciones | GET |
| `biblioteca/paquete/index` | Paquetes | GET |
| `biblioteca/paquete/agregar_paquete` | Agregar paquete | GET, POST |
| `biblioteca/paquete/prestamo` | Préstamo paquete | GET |
| `biblioteca/paquete/devolucion` | Devolución paquete | GET |
| `biblioteca/usuarios/index` | Usuarios biblioteca | GET |
| `biblioteca/reportes/index` | Reportes biblioteca | GET |
| **Recursos Humanos** | | |
| `recursos/index` | Hub RH | GET |
| `recursos/solicitar` | Solicitar certificado | GET |
| `recursos/solicitados` | Solicitados | GET |
| `recursos/certificados` | Certificados | GET |
| `recursos/renovacion_recursos` | Renovación master | GET |
| `recursos/Listado_Asistencia` | Asistencia | GET |
| `recursos/tramites/index` | Trámites | GET |
| `recursos/tramites/listado` | Listado trámites | GET |
| `recursos/permisos/index` | Permisos/Licencias | GET |
| `recursos/news/index` | Mensajes/Noticias | GET |
| **Enfermería** | | |
| `enfermeria/index` | Hub enfermería | GET |
| `enfermeria/listado` | Listado | GET |
| `enfermeria/atencion` | Atención médica | GET, POST |
| `enfermeria/procedimiento` | Procedimientos | GET |
| `enfermeria/historial` | Historial clínico | GET |
| **Solicitud/Compras** | | |
| `solicitud/index` | Crear solicitud | GET, POST |
| `solicitud/listado` | Listado general | GET, POST |
| `solicitud/listado_user` | Mis solicitudes | GET |
| `solicitud/pedido` | Realizar pedido | GET, POST |
| `solicitud/editar` | Editar solicitud | GET |
| `solicitud/detalles` | Detalles | GET |
| `solicitud/cotizacion` | Cotización | GET |
| `solicitud/confirmar` | Confirmar | GET |
| `solicitud/verificar` | Verificar entrega | GET |
| `solicitud/revision` | Revisión | GET |
| `cotizacion/index` | Cotizaciones | GET |
| `compras/index` | Hub compras | GET |
| **Proveedor** | | |
| `proveedor/index` | Listado proveedores | GET |
| `proveedor/registro` | Registrar proveedor | GET, POST |
| `proveedor/evaluacion` | Evaluación | GET |
| `proveedor/documentos` | Documentos | GET |
| **Contabilidad** | | |
| `contabilidad/index` | Vouchers de pago | GET |
| `contabilidad/historial` | Historial | GET |
| **Otros módulos** | | |
| `admisiones/index` | Admisiones | GET |
| `citaciones/index` | Citaciones | GET, POST |
| `asistencia/index` | Asistencia | GET |
| `renovacion/index` | Renovaciones | GET |
| `carnet/index` | Carnets | GET |
| `extra/index` | Actividades extra | GET |
| `prom/index` | Prom night | GET |
| `eventos/index` | Eventos | GET |
| `hoja_vida/index` | Hojas de vida | GET |
| `material/index` | Material didáctico | GET |
| `perfil/index` | Perfil usuario | GET |
| `permisos/index` | Permisos del sistema | GET |
| `confirmar/index` | Confirmaciones | GET |
| **Impresión/PDF** | | |
| `imprimir/codigo` | Código de barras | GET |
| `imprimir/codigos` | Múltiples códigos | GET |
| `imprimir/cartaEntrega` | Carta de entrega | GET |
| `imprimir/hoja_vida` | Hoja de vida PDF | GET |
| `imprimir/formato_clinico` | Formato clínico | GET |
| `imprimir/reporte` | Reporte PDF | GET |
| `imprimir/solicitudInicial` | Solicitud inicial | GET |
| `imprimir/biblioteca/*` | Varios PDFs biblioteca | GET |
| `imprimir/zonas/*` | PDFs zonas | GET |
| `imprimir/prom/*` | PDFs prom | GET |

---

## 6. ENDPOINTS AJAX (54+ archivos)

Los archivos AJAX NO pasan por el router. Se acceden directamente vía su URL.

**Patrón de llamada JavaScript:**
```javascript
$.ajax({
    url: "vistas/ajax/modulo/accion.php",
    method: "POST",
    data: { campo: valor },
    dataType: "json",
    success: function(response) { ... }
});
```

### Listado completo de endpoints AJAX

| Endpoint | Controlador/Método | Propósito |
|---|---|---|
| **usuarios/** | | |
| `validarUsuario.php` | `ControlUsuarios::verificarUsuarioControl()` | Verificar si usuario existe |
| `validarFirma.php` | `ControlUsuarios::validarFirmaControl()` | Verificar firma digital |
| `validarDocumento.php` | `ControlUsuarios::verificarDocumentoControl()` | Validar documento |
| `inactivarUsuario.php` | `ControlUsuarios::inactivarUsuarioControl()` | Inactivar usuario |
| `activarUsuario.php` | `ControlUsuarios::activarUsuarioControl()` | Activar usuario |
| **solicitud/** | | |
| `agregarArticulo.php` | `ControlSolicitud::agregarProductoControl()` | Agregar producto a solicitud |
| `removerArticulo.php` | `ControlSolicitud::removerProductoControl()` | Remover producto |
| **salon/** | | |
| `validar.php` | `ControlSalon::mostrarHorasDisponiblesControl()` | Horas disponibles |
| `rechazar.php` | `ControlSalon::rechazarReservaControl()` | Rechazar reserva |
| `aprobar.php` | `ControlSalon::aprobarReservaControl()` | Aprobar reserva |
| `opcionesSalon.php` | `ControlSalon::mostrarDatosSalonIdControl()` | Datos del salón |
| `contarPortatil.php` | `ControlSalon::contarPortatilDisponibleControl()` | Portátiles disponibles |
| **reportes/** | | |
| `vistoBueno.php` | `ControlReporte::vistoBuenoReporteControl()` | Aprobar reporte |
| `solucion.php` | `ControlReporte::solucionarReporteControl()` | Marcar como solucionado |
| **enfermeria/** | | |
| `informacionUsuario.php` | `ControlEnfermeria::mostrarDatosUsuariosControl()` | Info usuario |
| `graficasEnfermeria.php` | `ControlEnfermeria::MostrarGraficaEnfermeriaControl()` | Datos gráficas |
| **asistencia/** | | |
| `validarToken.php` | `ControlAsistencia::validarTokenControl()` | Validar token asistencia |
| `ValidarCedula.php` | `ControlAsistencia::validarDocumentoControl()` | Validar cédula |
| **cronograma/** | | |
| `fechas.php` | `ControlInventario` + `ControlRenovacion` | Eventos calendario |
| **admisiones/** | | |
| `habilitar.php` | `ControlAdmisiones::habilitarFormatoControl()` | Habilitar formato |
| **areas/** | | |
| `usuarioResponsable.php` | `ControlAreas::usuarioResponsableControl()` | Usuario responsable |
| `activarArea.php` | `ControlAreas::activarAreaControl()` | Activar área |
| `inactivarArea.php` | `ControlAreas::inactivarAreaControl()` | Inactivar área |
| **permisos/** | | |
| `activarPermiso.php` | `ControlPermisos::activarPermisoControl()` | Activar permiso |
| `inactivarPermiso.php` | `ControlPermisos::inactivarPermisoControl()` | Inactivar permiso |
| **graficas/** | | |
| `cantidades.php` | `ControlInventario::cantidadesSolucionadasControl()` | Datos gráfica inventario |
| `cantidadesGeneral.php` | `ControlInventario::cantidadesGeneralSolucionadasControl()` | Datos gráfica general |
| `cantidadesZona.php` | `ControlZonas::cantidadesSolucionadosZonasControl()` | Datos gráfica zonas |
| `cantidadesMantZonas.php` | `ControlZonas::cantidadesMantenimientosSolucionadosZonasControl()` | Mant. zonas |
| **hoja_vida/** | | |
| `codigo.php` | (inline barcode) | Generar código barras PNG |
| `asignarComponenteHardware.php` | `ControlHojaVida::asignarComponenteHardwareControl()` | Asignar componente |
| **biblioteca/** | | |
| `cargarSubcategorias.php` | `ControlBiblioteca::cargarSubcategoriasControl()` | Cargar subcategorías |
| `cargarResultadoLibro.php` | `ControlBiblioteca::cargarInformacionEjemplarControl()` | Info ejemplar |
| `cargarResultadoLibroPrestado.php` | `ControlBiblioteca::prestarCodigoLibroControl()` | Prestar libro |
| `cargarEjemplaresLibro.php` | `ControlBiblioteca::mostrarEjemplaresControl()` | Ejemplares del libro |
| `cargarDevolucionLibro.php` | `ControlBiblioteca::devolucionLibroControl()` | Devolver libro |
| `cargarResultadoPaquete.php` | `ControlBiblioteca::moatrarDatosCodigoPaqueteControl()` | Info paquete |
| `cargarResultadoPaqueteDevolucion.php` | `ControlBiblioteca::devolucionPaqueteControl()` | Devolver paquete |
| `cargarResultadoUsuario.php` | `ControlPerfil::mostrarInformacionPerfilControl()` | Info usuario |
| **inventario/** | | |
| `agregarInventario.php` | `ControlInventario::agregarInventarioPendienteControl()` | Agregar pendiente |
| `inventarioTemp.php` | `ControlInventario::guardarInventarioTempControl()` | Guardar temporal |
| `confirmarInventario.php` | `ControlInventario::confirmarInventarioControl()` | Confirmar inventario |
| `noConfirmarInventario.php` | `ControlInventario::noConfirmarInventarioControl()` | Cancelar confirmación |
| **proveedor/** | | |
| `eliminarContacto.php` | `ControlProveedor::eliminarContactoControl()` | Eliminar contacto |
| `eliminarBanco.php` | `ControlProveedor::eliminarBancoControl()` | Eliminar banco |
| `calcularCalificacion.php` | (inline) | Calcular calificación |
| **recursos/** | | |
| `mostrarFormularioTipoTramite.php` | (inline HTML) | Formulario tipo trámite |
| `mostrarFormularioTipoPermiso.php` | (inline HTML) | Formulario tipo permiso |
| `eliminarDocumento.php` | `ControlRenovacion::eliminarDocumentoControl()` | Eliminar documento |
| **mantenimientos/** | | |
| `MostrarInventarioMantenimiento.ajax.php` | `ControlInventario::obtenerInventarioPorIdCategoriaControl()` | Inventario por categoría |
| **padres/** | | |
| `guardar_hermano.php` | `ControlPadres::guardarHermanoControl()` | Guardar hermano |
| **prom/** | | |
| `cupo.php` | `ControlUsuarios::disminuirCupoControl()` | Disminuir cupo |
| **prefactura/** | | |
| `calcular.php` | (inline) | Calcular totales con IVA |

---

## 7. CONTROLADORES DETALLADOS

### Patrón general de controladores

```php
class ControlNombre {
    private static $instancia;

    public static function singleton_controlNombre() {
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function metodoControl($datos) {
        // Valida $_SERVER['REQUEST_METHOD'] == 'POST'
        // Valida isset($_POST['campo'])
        // Llama a ModeloNombre::metodoModel($datos)
        // Retorna respuesta con ohSnap() o redirección
    }
}
```

### Controladores principales y sus funcionalidades

| Controlador | Archivo | Líneas | Métodos | Funcionalidad |
|---|---|---|---|---|
| `ControlInventario` | `inventario/ControlInventario.php` | 4139 | 50+ | CRUD inventario, confirmaciones, reasignaciones, descontinuados, trabajo casa, gráficas, emails |
| `ControlBiblioteca` | `biblioteca/ControlBiblioteca.php` | ~2000 | 38 | CRUD libros, ejemplares, préstamos, devoluciones, paquetes, reportes |
| `ControlSolicitud` | `solicitud/ControlSolicitud.php` | ~1500 | 30+ | Solicitud de compra, pedidos, cotizaciones, verificación, emails (8 triggers) |
| `ControlRecursos` | `recursos/ControlRecursos.php` | 1060 | 25+ | RH: certificados, trámites, permisos, asistencia, mensajes, emails (7 triggers) |
| `ControlSalon` | `salon/ControlSalon.php` | ~800 | 20+ | CRUD salones, reservas, aprobación/rechazo, disponibilidad, emails (4 triggers) |
| `ControlProveedor` | `proveedor/ControlProveedor.php` | ~800 | 20+ | CRUD proveedores, contactos, bancos, evaluación, documentos, email (1 trigger) |
| `ControlEnfermeria` | `enfermeria/ControlEnfermeria.php` | ~600 | 15+ | Procedimientos, atención médica, historial, gráficas, emails a padres (2 triggers) |
| `ControlUsuarios` | `usuarios/ControlUsuarios.php` | ~600 | 15+ | CRUD usuarios, firma digital, activar/desactivar, cupo prom |
| `ControlZonas` | `zonas/ControlZonas.php` | ~500 | 15+ | CRUD zonas, reportes, mantenimiento, gráficas |
| `ControlMatricula` | `matricula/ControlMatricula.php` | ~500 | 10+ | Matrícula, grados, niveles, emails (3 triggers) |
| `ControlReportes` | `reportes/ControlReportes.php` | ~400 | 10+ | Reportes de inventario, visto bueno, solución, email (1 trigger) |
| `ControlCitaciones` | `citaciones/ControlCitaciones.php` | ~300 | 5+ | Generar citaciones, email (1 trigger) |
| `ControlAsistencia` | `asistencia/ControlAsistencia.php` | ~300 | 5+ | Tokens, validación cédula |
| `ControlPermisos` | `permisos/ControlPermisos.php` | ~400 | 10+ | CRUD permisos, año escolar, finalización de año (email masivo) |
| `ControlSession` | `ControlSession.php` | ~120 | 3 | Login, logout, reset admin |
| `ControlPerfil` | `perfil/ControlPerfil.php` | ~200 | 5+ | Ver/editar perfil |
| `ControlHojaVida` | `hoja_vida/ControlHojaVida.php` | ~300 | 5+ | Hardware/software lifecycle |
| `ControlAdmisiones` | `admisiones/ControlAdmisiones.php` | ~200 | 5+ | Formatos de admisión |
| `ControlContabilidad` | `contabilidad/ControlContabilidad.php` | ~200 | 5+ | Vouchers de pago |
| `ControlCategorias` | `categorias/ControlCategorias.php` | ~150 | 3+ | CRUD categorías |
| `ControlAreas` | `areas/ControlAreas.php` | ~200 | 5+ | CRUD áreas, responsable |
| `ControlRenovacion` | `renovacion/ControlRenovacion.php` | ~300 | 5+ | Renovaciones, documentos |
| `ControlPadres` | `padres/ControlPadres.php` | ~100 | 3+ | Hermanos, email (1 trigger) |
| `ControlExtra` | `extra/ControlExtra.php` | ~100 | 3+ | Actividades extra |
| `ControlPrefactura` | `prefactura/ControlPrefactura.php` | ~200 | 3+ | Cálculos IVA, pre-factura |

### Envío de correos por controlador

| Controlador | Cantidad emails | Contexto |
|---|---|---|
| `ControlInventario` | 11 | Operaciones de inventario |
| `ControlSolicitud` | 8 | Solicitud, aprobación, rechazo, pedido, cancelación |
| `ControlRecursos` | 7 | RH: certificados, trámites, permisos |
| `ControlSalon` | 4 | Reservar, aprobar, rechazar |
| `ControlMatricula` | 3 | Matrícula |
| `ControlEnfermeria` | 2 | Atención médica → padres |
| `ControlPermisos` | 1+ | Finalización año escolar (masivo) |
| `ControlCitaciones` | 1 | Generar citación |
| `ControlReportes` | 1 | Reportes |
| `ControlProveedor` | 1 | Proveedor |
| `ControlBiblioteca` | 1 | Biblioteca |
| `ControlPadres` | 1 | Padres |

### Patrón de envío de correo

```php
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';

$datos_correo = array(
    "asunto" => "Asunto del correo",
    "correo"  => array("destinatario@email.com"),
    "user"    => "Nombre Remitente",
    "mensaje" => "<h1>HTML del correo</h1><p>contenido...</p>",
    "archivo" => array("url_archivo1.pdf")  // opcional, se obtienen vía cURL
);

Correo::enviarCorreoModel($datos_correo);
```

---

## 8. MODELOS Y BASE DE DATOS

### Patrón de modelos

```php
class ModeloNombre {
    public static function metodoModel($datos) {
        $conexion = conexion::singleton_conexion();
        $sql = "SELECT/INSERT/UPDATE/DELETE ...";
        $consulta = $conexion->preparar($sql);
        $consulta->execute($parametros);
        // retorna resultados
    }
}
```

### Conexión a BD (Singleton PDO)

```php
class conexion {
    private static $instancia;
    private static $conexion;

    public static function singleton_conexion() {
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function __construct() {
        self::$conexion = new PDO(
            "mysql:host=localhost;dbname=sami_hebreo;charset=utf8",
            "root", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
        );
    }
}
```

### Base de datos principal (inferida de queries SQL)

#### Tablas del sistema

| Tabla | Propósito |
|---|---|
| `usuarios` | Usuarios del sistema |
| `cron_opciones` | Opciones de permisos |
| `cron_permisos` | Permisos activos por perfil |
| `anio_escolar` | Años escolares |
| `nivel` | Niveles educativos |
| `curso` | Cursos |
| `inventario` | Artículos de inventario |
| `inventario_temp` | Inventario temporal (confirmación) |
| `inventario_confirmacion` | Confirmaciones de inventario |
| `categoria` | Categorías de inventario |
| `areas` | Áreas/ubicaciones |
| `reportes` | Reportes de inventario |
| `reportes_historial` | Historial de reportes |
| `mantenimientos` | Mantenimientos programados |
| `mantenimientos_historial` | Historial mantenimientos |
| `salones` | Salones disponibles |
| `salon_reservas` | Reservaciones de salón |
| `biblioteca_libros` | Libros |
| `biblioteca_ejemplares` | Ejemplares de libros |
| `biblioteca_prestamos` | Préstamos de libros |
| `biblioteca_paquetes` | Paquetes de libros |
| `biblioteca_paquete_contenido` | Contenido de paquetes |
| `biblioteca_paquete_prestamo` | Préstamos de paquetes |
| `biblioteca_usuario_grupo` | Grupos de usuarios biblioteca |
| `solicitudes` | Solicitudes de compra |
| `solicitud_productos` | Productos en solicitud |
| `cotizacion` | Cotizaciones |
| `solicitud_verificacion` | Verificación de solicitud |
| `proveedores` | Proveedores |
| `proveedor_contactos` | Contactos de proveedor |
| `proveador_banco` | Datos bancarios proveedor |
| `proveedor_evaluacion` | Evaluación de proveedor |
| `proveedor_documentos` | Documentos de proveedor |
| `zonas` | Zonas comunes |
| `areas_zona` | Áreas de zonas |
| `reportes_zonas` | Reportes de zonas |
| `admisiones` | Admisiones estudiantiles |
| `citaciones` | Citaciones estudiantiles |
| `matricula` | Matrícula |
| `enfermeria_procedimientos` | Procedimientos enfermería |
| `enfermeria_atencion` | Atención médica |
| `enfermeria_historial` | Historial clínico |
| `enfermeria_categoria` | Categorías enfermería |
| `hoja_vida` | Hojas de vida de activos |
| `hoja_vida_componentes` | Componentes hardware |
| `hoja_vida_software` | Software instalado |
| `recursos_certificados` | Certificados RH |
| `recursos_tramites` | Trámites RH |
| `recursos_permisos` | Permisos/licencias |
| `recursos_noticias` | Noticias/mensajes |
| `prom_codigos` | Códigos prom night |
| `firmas` | Firmas digitales usuarios |
| `contabilidad` | Vouchers de pago |
| `extra` | Actividades extra |
| `renovacion` | Renovaciones |
| `padres_hermanos` | Hermanos (familia) |
| `evento` | Eventos |
| `material_didactico` | Material didáctico |
| `pedidos` | Pedidos de compra |
| `solicitudes_inicial` | Snapshot solicitud inicial |
| `solicitud_productos_inicial` | Snapshot productos inicial |
| `solicitud_verificacion_inicial` | Snapshot verificación inicial |

### Columnas importantes de `usuarios`

`id`, `documento`, `nombre`, `apellido`, `telefono`, `correo`, `usuario`, `password`, `perfil` (rol_id), `activo`, `foto`, `firma`, `fecha_editado`, `fecha_inactivo`, `fecha_activo`, `foto_carnet`, `nivel`, `curso`

### Perfiles/Roles conocidos

| ID | Rol |
|---|---|
| 1 | Administrador |
| 6 | Padres |
| 11 | Asistente de nivel |
| 16 | Estudiante PROM |
| 17 | (Otro) |
| 22 | Coordinador |

---

## 9. SISTEMA DE AUTENTICACIÓN Y SESIONES

### Login (`ControlSession::ingresaruser()`)

1. Recibe `$_POST['user']` y `$_POST['pass']`
2. Consulta tabla `usuarios` por nombre de usuario
3. Verifica que `activo = 1`
4. Verifica contraseña con `Hash::verificar()` (bcrypt `crypt()`)
5. Si es correcto:
   - `Session::iniciar()` → `session_start()`
   - Guarda en `$_SESSION`: `id`, `documento`, `nombre_admin`, `apellido`, `rol`, `empresa`, `super_empresa`
   - Redirige a `inicio`
6. Si falla, redirige a `login?er=` con código de error:
   - `1` = Usuario/contraseña incorrectos
   - `2` = Debe iniciar sesión (acceso no autenticado)
   - `3` = Usuario no encontrado
   - `4` = Usuario inactivo

### Password Hashing (`hash.php`)

```php
class Hash {
    public static function hashpass($pass) {
        return crypt($pass, '$2y$10$' . ...);  // bcrypt
    }
    public static function verificar($pass, $hash) {
        return crypt($pass, $hash) === $hash;
    }
}
```

### Reset Admin (backdoor)

`ControlSession.php` contiene un token hardcodeado que recrea el usuario admin con contraseña específica. Se activa vía URL con parámetro base64.

### Guard de sesión (equivalente a middleware)

Cada vista al inicio incluye `navegacion.php` que hace:

```php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:login?er=' . $error);
    exit();
}
```

### Logout (`salir.php`)

```php
$salir = new Session;
$salir->iniciar();
$salir->outsession();  // session_unset() + session_destroy()
header('Location:login');
```

---

## 10. SISTEMA DE PERMISOS

### Tablas involucradas

- `cron_opciones` — Opciones disponibles (vinculadas a módulos)
- `cron_permisos` — Permisos activos (opción + perfil + activo)
- `cron_modulos` — Módulos del sistema

### Funcionamiento

```php
// ControlPermisos::permisosUsuarioControl($id_opcion, $perfil)
// Consulta: SELECT * FROM cron_permisos WHERE id_opcion = X AND id_perfil = Y AND activo = 1
// Retorna: fila si existe, false si no
```

### Uso en navegación (sidebar)

```php
$permisos = $instancia_permiso->permisosUsuarioControl(16, $perfil_log);
if ($permisos) {
    // Mostrar enlace en sidebar
}
```

### IDs de permisos identificados

`1, 2, 3, 4, 7, 9, 10, 11, 12, 14, 16, 17, 18, 19, 20, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 76, 80, 83, 93`

### Mapeo permiso → función (parcial)

| ID | Función |
|---|---|
| 2 | Usuarios |
| 3 | Inventario |
| 4 | Áreas |
| 7 | Reportes |
| 9 | Re-asignar área |
| 10 | Mantenimientos |
| 11 | Categorías |
| 12 | Listado |
| 16 | Listado inventario |
| 17 | Trabajo en casa |
| 18 | Material didáctico |
| 20 | Biblioteca |
| 22 | Admisiones |
| 23 | Gestión humana |
| 29 | Visto bueno |
| 33 | Zonas comunes |
| 34 | Reportes zonas |
| 35 | Historial zonas |
| 41 | Reservar salón |
| 42 | Programación diaria |
| 43 | Reservas pendientes |
| 47 | Proceso de compras |
| 53 | Configuración |
| 64 | Enfermería |

---

## 11. FRONTEND

### Framework CSS: Bootstrap 4 (SB Admin 2)

- Tema: SB Admin 2 (dashboard administrativo)
- Custom CSS: `main.css` con clases personalizadas (`.text-hebreo`, `.btn-hebreo`, `.bg-image`, `.border-left-brown`, `.border-left-green`)
- Google Font: Nunito (200-900)
- Iconos: FontAwesome Free 5.9.0

### Template structure (4 partes)

```
cabeza.php          → <!DOCTYPE html>, <head>, CSS, <body>, loader, #wrapper
navegacion.php      → Auth check, sidebar, topbar, abre container-fluid
[VISTA DEL MÓDULO]  → Contenido de la página
script_and_final.php → jQuery, Bootstrap, JS comunes, Chart.js, ohsnap
pie.php             → Footer (Copyright 2018) — NO se incluye actualmente
```

### JavaScript global (cargado en todas las páginas)

| Archivo | Función |
|---|---|
| `main.js` | Tooltips, popovers, clockpicker, validación inputs (.numeros/.letras), filtro tablas (.filtro), AJAX firma, loader |
| `ohsnap.js` | Sistema de notificaciones toast: `ohSnap("msg", {color, duration})` |
| `clockPicker.js` | Inicialización clock picker |
| `fileinput.js` | Mejora de inputs de archivo |
| `select.js` | Inicialización Select2 |
| `calendar.js` | Date picker |
| `qrlector.js` | Lector de QR |

### JavaScript por módulo (en `public/js/`)

| Archivo | Módulo | Llama AJAX |
|---|---|---|
| `usuario/funcionesUsuario.js` | Usuarios | validarUsuario, validarDocumento, inactivar/activar |
| `salon/funcionesSalon.js` | Salón | opcionesSalon, validar, contarPortatil, aprobar, rechazar |
| `inventario/funcionesInventario.js` | Inventario | confirmar, noConfirmar, agregar, inventarioTemp |
| `solicitud/funcionesSolicitud.js` | Solicitud | agregarArticulo, removerArticulo, calcular |
| `reportes/funcionesReporte.js` | Reportes | vistoBueno, solucion |
| `proveedor/funcionesProveedor.js` | Proveedor | eliminarBanco, calcularCalificacion, eliminarContacto |
| `recursos/funcionesRecursos.js` | RH | eliminarDocumento, forms tipoPermiso/tipoTramite |
| `mantenimientos/funcionesMantenimientos.js` | Mantenimientos | MostrarInventarioMantenimiento |
| `hoja_vida/funcionesHojaVida.js` | Hoja vida | codigo (barcode), asignarComponente |
| `prefactura/funcionesPrefactura.js` | Prefactura | calcular |
| `biblioteca/funcionesBiblioteca.js` | Biblioteca | Varios |
| `biblioteca/funcionesPrestamo.js` | Préstamos | cargarResultadoLibro, cargarEjemplares |
| `biblioteca/funcionesDevolucion.js` | Devoluciones | cargarDevolucionLibro, cargarResultadoLibroPrestado |
| `biblioteca/funcionesPaquete.js` | Paquetes | cargarResultadoPaquete, cargarUsuario, cargarSubcategorias |
| `asistencia/funcionesLector.js` | Asistencia | ValidarCedula, validarToken |
| `admisiones/funcionesAdmisiones.js` | Admisiones | habilitar |
| `enfermeria/funcionesGraficas.js` | Enfermería | informacionUsuario, graficasEnfermeria |
| `graficas/funciones.js` | Gráficas | cantidades, cantidadesGeneral, cantidadesZona |
| `permisos/funcionesPermisos.js` | Permisos | activarPermiso, inactivarPermiso |
| `areas/funcionesArea.js` | Áreas | usuarioResponsable, activar/inactivar |
| `prom/funcionesLector.js` | Prom | cupo |
| `cronograma/funcionesCronograma.js` | Cronograma | fechas |

### Librerías frontend (vendored en `public/vendor/`)

| Librería | Versión | Uso |
|---|---|---|
| jQuery | (múltiples variants) | DOM, AJAX |
| Bootstrap | 4.x (via SB Admin 2) | UI framework |
| FontAwesome | 5.9.0 | Iconos |
| jQuery Easing | (bundled) | Animaciones |
| Chart.js | 2.8.0 (CDN) | Gráficas dashboards |
| DataTables | (bundled) | Tablas interactivas |
| SB Admin 2 | (bundled) | Template admin |
| Bootstrap Select | (bundled) | Selects mejorados |
| Bootstrap Clock Picker | (bundled) | Selector de hora |
| Bootstrap File Input | (bundled) | Upload de archivos |

### Notificaciones toast (ohSnap)

```javascript
ohSnap("Mensaje de éxito", {color: "green", duration: "2000"});
ohSnap("Mensaje de error", {color: "red", duration: "3000"});
ohSnap("Advertencia", {color: "yellow", duration: "2000"});
```

Contenedor: `<div id="ohsnap"></div>` en `cabeza.php`

### Modales Bootstrap (patrón principal de CRUD)

Los modales se generan dinámicamente por cada fila de tabla o se definen estáticos:

| Módulo | Modal IDs | Propósito |
|---|---|---|
| Usuarios | `#editar_usuario{id}`, `#agregar_usuario` | Editar/agregar |
| Inventario | `#agregar_inventario` | Agregar |
| Reportes | `#sol_reporte{id}`, `#desc_inv{id}` | Solucionar/descontinuar |
| Zonas | `#area{id}`, `#dano{id}`, `#mant{id}`, `#agregar_zona`, `#agregar_area` | CRUD |
| Mantenimientos | `#mant_inv{id}`, `#copia{id}`, `#mant_pro{id}`, `#programar_mant` | Mantener/copiar/programar |
| Citaciones | `#citar{id}` | Generar citación |
| Solicitud | `#anular{id}` | Anular solicitud |
| Inicio | `#subir_firma` | Subir firma digital |

---

## 12. MÓDULOS FUNCIONALES COMPLETOS

### 1. Usuarios y Autenticación
- CRUD de usuarios con perfiles (roles)
- Login/logout con sesiones
- Activar/desactivar usuarios
- Subir firma digital
- Generación de carnets

### 2. Inventario
- Alta/baja/modificación de artículos
- Asignación a áreas y usuarios
- Confirmación de inventario (temp → confirmado)
- Reasignación entre áreas
- Descontinuación de artículos
- Trabajo en casa
- Código de barras y QR
- Panel de control con gráficas

### 3. Mantenimientos
- Programación preventiva
- Historial de mantenimientos
- Mantenimiento por áreas
- Notificaciones de próximo mantenimiento

### 4. Reportes
- Reportes de daño/inventario
- Visto bueno (aprobación)
- Solución de reportes
- Descontinuación por reporte
- Exportación a Excel

### 5. Zonas Comunes
- CRUD de zonas y áreas
- Reportes de zona (daño)
- Mantenimiento de zonas
- Historial de zonas
- Gráficas de zonas

### 6. Biblioteca
- CRUD de libros y ejemplares
- Préstamos individuales y por grupo
- Devoluciones
- Paquetes de libros (préstamo/devolución)
- Reportes de libros prestados
- Código de barras por ejemplar
- Paz y salvo

### 7. Salón (Reservaciones)
- CRUD de salones
- Reservar con fecha/hora/portátil/sonido
- Aprobación/rechazo de reservas
- Disponibilidad horaria
- Programación diaria
- Conteo de portátiles disponibles

### 8. Solicitudes de Compra
- Crear solicitud con productos
- Flujo: solicitud → aprobación → pedido → cotización → verificación → recepción
- Cálculo de IVA
- Anulación de solicitudes
- Correos en cada etapa

### 9. Proveedores
- Registro con datos bancarios y contactos
- Evaluación de proveedores
- Documentos de proveedor
- Calificación automática

### 10. Recursos Humanos
- Certificados laborales
- Renovación de contratos
- Trámites y permisos
- Asistencia
- Mensajes/noticias internas
- Hojas de vida

### 11. Enfermería
- Procedimientos médicos
- Atención a usuarios
- Historial clínico
- Envío automático a padres
- Gráficas de enfermería

### 12. Admisiones
- Formatos de admisión
- Habilitar/deshabilitar formatos

### 13. Matrícula
- Registro de matrícula
- Niveles y cursos
- Envío de correos

### 14. Citaciones
- Generación de citaciones estudiantiles
- Impresión de formato de citación
- Correo a padres

### 15. Asistencia
- Validación por cédula
- Validación por token
- Lector QR

### 16. Contabilidad
- Vouchers de pago
- Historial contable

### 17. Renovaciones
- Control de renovaciones de contratos/documentos
- Documentos de respaldo

### 18. Prom Night
- Códigos de acceso
- Control de cupo
- Lector QR
- Límite de asistentes

### 19. Eventos
- Gestión de eventos institucionales

### 20. Material Didáctico
- CRUD de material didáctico

### 21. Confirmaciones
- Confirmación de inventario por usuarios
- Confirmación de padres

### 22. Hoja de Vida de Activos
- Ciclo de vida de hardware y software
- Asignación de componentes
- Generación de código de barras

### 23. Permisos del Sistema
- CRUD de permisos por perfil
- Gestión de módulos y opciones

### 24. Impresión/PDF
- Cartas de entrega
- Hojas de vida
- Formatos clínicos
- Reportes de inventario
- Códigos de barras
- Reportes de zonas
- Listados de préstamo biblioteca
- Códigos de prom

---

## 13. NOTIFICACIONES Y CORREOS

### Sistema de notificación interna

- **ohSnap.js** — Toast notifications en el navegador
- Se usan en controllers con `echo "<script>ohSnap('mensaje', {color: 'verde'})</script>"`
- Cada operación CRUD exitosa muestra un toast

### Envío de correos (PHPMailer vía SMTP)

**Clase:** `app/modelo/correo/ModeloCorreos.php`
**Extiende:** `PHPMailer`
**Método estático:** `Correo::enviarCorreoModel($datos)`

**Parámetros del array `$datos`:**
- `asunto` — Asunto del correo
- `correo` — Array de destinatarios
- `user` — Nombre del remitente
- `mensaje` — Cuerpo HTML
- `archivo` — Array de URLs de archivos adjuntos (se obtienen vía cURL)

**~50 llamadas en 14 controladores**

### Redirecciones post-operación

Las vistas usan `window.location.replace()` para redirigir después de un POST exitoso:
```javascript
window.location.replace("solicitud/listado");
window.location.replace("inventario/index");
window.location.replace("inicio");
```

---

## 14. SUBIDA DE ARCHIVOS

### Patrón general

```php
if (isset($_FILES['archivo'])) {
    if ($_FILES['archivo']['error'] == 0) {
        $directorio = PUBLIC_PATH_ARCH . 'upload/';
        // Crear subdirectorio por módulo si no existe
        if (!file_exists($directorio . 'modulo/')) {
            mkdir($directorio . 'modulo/', 0755, true);
        }
        $nombre_archivo = uniqid() . '_' . $_FILES['archivo']['name'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], $directorio . 'modulo/' . $nombre_archivo);
    }
}
```

### Directorio de uploads: `public/upload/`

### Formularios de upload

- Firma digital (`inicio.php` → `guardarFirmaUsuarioControl()`)
- Imágenes de inventario
- Cotizaciones (archivos PDF)
- Documentos de proveedor
- Documentos de renovación RH
- Imágenes de reportes
- Archivos de biblioteca

---

## 15. SEGURIDAD - PROBLEMAS CONOCIDOS

| Problema | Ubicación | Severidad |
|---|---|---|
| SQL Injection en login | `IngresoModel.php:11` — concatenación de SQL | CRÍTICO |
| Password BD en texto plano | `conexion_bio.php`, `conexion_extra.php` | ALTO |
| SMTP password en texto plano | `configMail.php` | ALTO |
| Backdoor de admin reset | `ControlSession.php` (token hardcodeado) | CRÍTICO |
| `allow_url_include = On` | `.user.ini` / `php.ini` | ALTO |
| Sin CSRF protection | Todos los formularios | ALTO |
| Sin rate limiting | Login, formularios | MEDIO |
| Sin Content Security Policy | Templates HTML | MEDIO |
| BASE_URL hardcodeada | `confi/Config.php` | BAJO |
| Sin validación en algunos AJAX | `vistas/ajax/` | MEDIO |
| Archivos duplicados sin cleanup | `ControlRecursos_old.php`, `ModeloRecursos_old.php` | BAJO |

---

## 16. DEPLOYMENT

- **Desarrollo:** XAMPP en Windows
- **Producción:** cPanel (basado en `.htaccess` y `.user.ini` generados por cPanel)
- **FTP:** Usado para deploy (`.ftpquota` presente, ~5906 bloques / ~588 MB)
- **Git:** 5 commits, origin en GitHub
- **No hay CI/CD pipeline**
- **No hay tests automatizados**

---

## 17. GUÍA PARA CAMBIOS COMUNES

### Agregar un nuevo módulo

1. Crear controlador: `app/controlador/nombre/ControlNombre.php`
2. Crear modelo: `app/modelo/nombre/ModeloNombre.php`
3. Crear vista: `vistas/modulos/nombre/index.php`
4. Crear endpoints AJAX (si aplica): `vistas/ajax/nombre/`
5. Agregar JS: `public/js/nombre/funcionesNombre.js`
6. Agregar enlace en `vistas/navegacion.php` (con permiso correspondiente)
7. Agregar permiso en tabla `cron_opciones` y `cron_permisos`

### Agregar una nueva vista

1. Crear archivo PHP en `vistas/modulos/ruta/nombre.php`
2. El archivo se accede vía URL: `localhost/sami_hebreo/ruta/nombre`
3. Incluir al inicio:
```php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) { /* redirigir a login */ }
```
4. Al final, incluir controller con `require_once` y procesar POST

### Agregar un nuevo endpoint AJAX

1. Crear archivo PHP en `vistas/ajax/modulo/accion.php`
2. Incluir controlador y modelo con `require_once`
3. Crear función JS en `public/js/modulo/funciones.js`
4. Llamar con `$.ajax({ url: "vistas/ajax/modulo/accion.php", ... })`

### Agregar envío de correo

```php
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
Correo::enviarCorreoModel(array(
    "asunto" => "Asunto",
    "correo" => array("email@dominio.com"),
    "user" => "Nombre Remitente",
    "mensaje" => "<h1>HTML</h1>",
    "archivo" => array()  // URLs de archivos adjuntos opcionales
));
```

### Agregar notificación toast

En controlador (respuesta AJAX):
```php
echo '<script>ohSnap("Mensaje", {color: "green", duration: "2000"});</script>';
```

En JavaScript:
```javascript
ohSnap("Mensaje", {color: "red", duration: "3000"});
```

### Agregar modal CRUD

```html
<div class="modal fade" id="mi_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Título</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="POST" action="">
          <!-- campos -->
          <button type="submit" name="submit_campo" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
```

Abrir con: `$('#mi_modal').modal('show');`

---

## 18. ARCHIVOS LEGACY/DUPLICADOS

| Archivo | Estado |
|---|---|
| `ControlRecursos_old.php` | Deprecated |
| `ModeloRecursos_old.php` | Deprecated |
| `ControlSalon_old.php` | Deprecated |
| `ModelReportes.php` | Duplicado de `ModeloReportes.php` |
| `vistas/modulos/enfermeria/lsitadoUsuario.php` | Typo de `listadoUsuario.php` |
| `vistas/modulos/imprimir/fomato_clinico.php` | Typo de `formato_clinico.php` |

---

## 19. CÓMO CONTINUAR DESARROLLANDO

### Antes de hacer cambios

1. Entender qué módulo se va a modificar
2. Identificar el controlador y modelo correspondientes
3. Revisar las dependencias (requiere otros modelos/controladores)
4. Verificar si hay endpoints AJAX involucrados
5. Revisar el JS del módulo en `public/js/`

### Patrón de código a seguir

- Controladores: método estático `ControlNombre::metodoControl()`
- Modelos: método estático `ModeloNombre::metodoModel()`
- Singleton para conexiones BD y controladores principales
- `require_once` manual para incluir archivos
- `$_SERVER['REQUEST_METHOD'] == 'POST'` para validar POST
- `isset($_POST['campo'])` para detectar submissions
- `ohSnap()` para notificaciones
- `window.location.replace()` para redirecciones desde JS

### Base de datos

No hay migraciones ni seeders. Los cambios de BD se hacen manualmente con SQL.
Las tablas se infieren de las queries en los modelos (ver sección 8).
