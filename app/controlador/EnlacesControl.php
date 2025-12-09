<?php
class EnlacesControl {
  public function CargarPlantilla() {
    include_once VISTA_PATH.'template.php';
  }
  public function EnlacesPaginas(){
        //NUEVO
    if(isset($_GET['url'])){
      $enlace= filter_input(INPUT_GET,'url',FILTER_SANITIZE_URL);        
    } else {
     $enlace='login'; 
   }
        //LLAMAMOS AL MODELO PARA QUE DEVUELVA LA VISTA
   $repuestaVista= EnlacesModelo::DevolverVistaAdmin($enlace);
   include_once $repuestaVista; 
 }
}
?>