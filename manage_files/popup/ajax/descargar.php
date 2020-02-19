<?php

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$month = isset($_POST['month']) ? trim($_POST['month']) : '';
$fichero = isset($_POST['fichero']) ? trim($_POST['fichero']) : '';

$path = '../adjuntos/'.$month.'/';
$filePath = $path.$fichero;

if(!empty($fichero) && file_exists($filePath)){
    // Si la acción indicada es 'DOWNLOAD' descargamos...
    if ($action=='download') {
        // Definimos las cabeceras
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fichero");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        // Leemos el archivo
        readfile($filePath);
    }
    // Si la acción indicada es 'DELETE' eliminamos...
    else if ($action=='delete') {
        unlink($filePath);
        // Enviamos 'succ' al ajax una vez eliminemos correctamente el archivo.
        echo 'succ';
        return true;
    }
} else {
    echo 'Este archivo no existe o no se encuentra guardado en el servidor."'.$filePath.'"';
}
?>
