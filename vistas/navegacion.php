<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
  $er    = '2';
  $error = base64_encode($er);
  $salir = new Session;
  $salir->iniciar();
  $salir->outsession();
  header('Location:login?er=' . $error);
  exit();
}
include_once VISTA_PATH . 'cabeza.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'permisos' . DS . 'ControlPermisos.php';

$instancia_perfil  = ControlPerfil::singleton_perfil();
$instancia_permiso = ControlPermisos::singleton_permisos();

$id_log           = $_SESSION['id'];
$id_super_empresa = $_SESSION['super_empresa'];
$perfil_log       = $_SESSION['rol'];

$datos_usuario       = $instancia_perfil->mostrarDatosPerfilControl($id_log);
$datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($id_super_empresa, 'logo');
$anio_escolar        = $instancia_permiso->mostrarAnioActivoControl();
$anio_lectivo        = $instancia_permiso->mostrarAniosLectivoControl();

$foto_perfil = ($datos_usuario['imagen'] != '') ? 'upload/' . $datos_usuario['imagen'] : 'img/user.svg';

$nivel         = $datos_usuario['id_nivel'];
$nombre_sesion = $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'];
$nom_nivel     = $datos_usuario['nom_nivel'];
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-white sidebar sidebar-dark accordion toggled" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=BASE_URL?>inicio">
    <div class="sidebar-brand-icon">
      <!-- <i class="fas fa-user text-hebreo"></i> -->
      <img src="<?=PUBLIC_PATH?>img/logo.png" alt="" width="60">
    </div>
    <div class="sidebar-brand-text ml-2 text-hebreo mt-3">
    </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="<?=BASE_URL?>inicio">
      <i class="fas fa-home text-hebreo"></i>
      <span class="text-muted">Inicio</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider bg-gray">

    <?php
    $permisos = $instancia_permiso->permisosUsuarioControl(16, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>inventario/listado" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-shapes text-hebreo"></i>
          <span class="text-muted">Listado inventario</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(7, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>reportes/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-tools text-hebreo"></i>
          <span class="text-muted">Reportes</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>usuarios/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-users text-hebreo"></i>
          <span class="text-muted">Usuarios</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(29, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>reportes/visto" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-check-circle text-hebreo"></i>
          <span class="text-muted">Visto bueno</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>inventario/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-barcode text-hebreo"></i>
          <span class="text-muted">Inventario</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(4, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>areas/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-map-marker-alt text-hebreo"></i>
          <span class="text-muted">Areas</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(9, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>areas/reasignar" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-sync-alt text-hebreo"></i>
          <span class="text-muted">Re-asignar area</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(10, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>mantenimientos/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-tools text-hebreo"></i>
          <span class="text-muted">Mantenimientos</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(33, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>zonas/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-map-marked-alt text-hebreo"></i>
          <span class="text-muted">Zonas Comunes</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(35, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>historial/historialZona" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-history text-hebreo"></i>
          <span class="text-muted">Historial Zonas</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(34, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>zonas/reportes" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-tools text-hebreo"></i>
          <span class="text-muted">Reportes Zonas</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(11, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>categorias/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-list-ul text-hebreo"></i>
          <span class="text-muted">Categorias</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>listado/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-list-ol text-hebreo"></i>
          <span class="text-muted">Listado</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(17, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>inventario/trabajoCasa" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-briefcase text-hebreo"></i>
          <span class="text-muted">Trabajo en casa</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(18, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>material/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-cubes text-hebreo"></i>
          <span class="text-muted">Material didactico</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(22, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>admisiones/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-notes-medical text-hebreo"></i>
          <span class="text-muted">Admisiones</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(41, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>salon/apartar" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-calendar-alt text-hebreo"></i>
          <span class="text-muted">Reservar salon</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(43, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>salon/pendientes" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="far fa-calendar-alt text-hebreo"></i>
          <span class="text-muted">Reservas pendientes</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(20, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>biblioteca/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-book-open text-hebreo"></i>
          <span class="text-muted">Biblioteca</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(42, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>salon/diario" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-clock text-hebreo"></i>
          <span class="text-muted">Programacion diaria</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(23, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>recursos/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-id-card-alt text-hebreo"></i>
          <span class="text-muted">Gesti&oacute;n humana</span>
        </a>
      </li>
    <?php }
    $permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
    if ($permisos) {
      ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=BASE_URL?>compras/index" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-shopping-cart text-hebreo"></i>
          <span class="text-muted">Proceso de compras</span>
        </a>
      </li>
    <?php }
    ?>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block bg-gray">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>
  <!-- End of Sidebar -->

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-none">


        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
          <i class="fa fa-bars text-hebreo"></i>
        </button>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

          <div class="topbar-divider d-none d-sm-block">
          </div>

          <!-- Nav Item - User Information -->
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600"><?=$_SESSION['nombre_admin'] . ' ' . $_SESSION['apellido']?></span>
              <div class="circular--perfil">
                <img src="<?=PUBLIC_PATH . $foto_perfil?>">
              </div>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="<?=BASE_URL?>perfil/index">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Perfil
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?=BASE_URL?>salir">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Cerrar sesion
              </a>
            </div>
          </li>

        </ul>

      </nav>
      <!-- End of Topbar -->

      <!-- Begin Page Content -->
      <div class="container-fluid">

        <?php
        include_once VISTA_PATH . 'script_and_final.php';
      ?>