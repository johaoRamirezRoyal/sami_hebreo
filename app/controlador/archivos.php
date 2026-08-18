<?php
date_default_timezone_set('America/Bogota');

class ControlArchivos
{
    private static $instancia;

    public static function singleton_archivos()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    function compressImage($source, $destination, $quality)
    {
        // Obtenemos la información de la imagen
        $imgInfo = getimagesize($source);
        $mime    = $imgInfo['mime'];
        // Creamos una imagen
        switch ($mime) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($source);
                break;
            default:
                $image = @imagecreatefromjpeg($source);
        }
        // Guardamos la imagen
        imagejpeg($image, $destination, $quality);
        // Devolvemos la imagen comprimida
        return $destination;
    }

    function guardarArchivo($archivo, $nombre_directorio = "")
    {
        // Validar errores de subida
        if (!isset($archivo['error']) || $archivo['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Tamaño máximo (5MB)
        $max_size = 100 * 1024 * 1024;
        if ($archivo['size'] > $max_size) {
            return false;
        }

        $nom_arch = $archivo['name'];
        $ext_arch = strtolower(pathinfo($nom_arch, PATHINFO_EXTENSION));
        $ext_arch = ($ext_arch === 'jpg') ? 'jpeg' : $ext_arch;

        // Extensiones permitidas
        $ext_permitidas = ['jpeg', 'png', 'pdf', 'docx'];
        if (!in_array($ext_arch, $ext_permitidas)) {
            return false;
        }

        // Validar MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);

        $mime_permitidos = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($mime, $mime_permitidos)) {
            return false;
        }

        // Generar nombre seguro
        $nombre_archivo = strtolower(bin2hex(random_bytes(16))) . '.' . $ext_arch;
        $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS . trim($nombre_directorio, DS) . DS;

        // Crear carpeta si no existe
        if (!file_exists($carp_destino)) {
            mkdir($carp_destino, 0755, true);
        }

        $ruta_img = $carp_destino . $nombre_archivo;

        // Procesar imagen o mover archivo
        if (in_array($ext_arch, ['png', 'jpeg'])) {
            if (!$this->compressImage($archivo['tmp_name'], $ruta_img, 50)) {
                return false;
            }
        } else {
            if (!move_uploaded_file($archivo['tmp_name'], $ruta_img)) {
                return false;
            }
        }

        return $nombre_archivo;
    }

    function eliminarArchivo($archivo, $dir = "")
    {
        $ruta = PUBLIC_PATH_ARCH . 'upload' . DS . $dir . DS . $archivo;

        // Verifica si el archivo existe antes de intentar eliminarlo
        if (file_exists($ruta)) {
            if (unlink($ruta)) {
                return array(
                    'error' => '',
                    'estado' => true
                ); // El archivo se eliminó correctamente
            } else {
                return array(
                    'error' => 'Error al intentar eliminar el archivo',
                    'estado' => false
                ); // Hubo un error al intentar eliminar el archivo
            }
        } else {
            return array(
                'error' => 'Archivo no encontrado',
                'estado' => false
            ); // El archivo no existe
        }
    }

    function transformarImagenAWebp() {}
}
