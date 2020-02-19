<!-- Bootstrap core CSS -->
<link href='/popup/libs/mdbootstrap/css/bootstrap.min.css' rel='stylesheet'>
<!-- Material Design Bootstrap -->
<link href='/popup/libs/mdbootstrap/css/mdb.min.css' rel='stylesheet'>
<!-- Your custom styles (optional) -->
<link href='/popup/libs/mdbootstrap/css/style.css' rel='stylesheet'>
<!-- Custom styles for this template -->
<link href='/popup/css/index.css' rel='stylesheet'>

<?php
//Se comprueba que no venga vacio por culpa de que se intente subir un archivo muy pesado
if (!isset($_FILES['file-upload-field']) || $_FILES['file-upload-field']['name'][0]==''){
    echo '<p class="mx-4"><b class="text-danger"> - Indique los arhivos que desea subir.</b></p>';
    die();
}

$month = isset($_POST['month']) ? trim($_POST['month']) : '';
$adjunto_sel = $_FILES['file-upload-field'];

$size_sel = 0;
$ficheros_sel = array();
if ($adjunto_sel['name'][0] !='') {
    foreach ($adjunto_sel as $key => $adj_sel) {
        if ($key == 'name' || $key == 'tmp_name' || $key == 'type' || $key == 'error') {
            foreach ($adj_sel as $k => $value) {
                if ($key=='error' && $value=='1') {
                    echo '<p class="mx-4"><b class="text-danger">El archivo '.$adjunto_sel['name'][$k].' tiene un error.</b></p>';
                    die();
                }
                $ficheros_sel[$k][$key]=$value;

            }
        }
        if ($key == 'size') {
            foreach ($adj_sel as $k => $value) {
                $size_sel += $value;
            }
        }
    }
}
if ($size_sel > 200000000){
    echo '<p class="mx-4"><b class="text-danger"> - Error al subir los archivos. Los adjuntos no pueden superar los 20GB.</b></p>';
    die();
}


$path = '../adjuntos/'.$month.'/';
$m=0;
$i=0;
foreach ($ficheros_sel as $attachment_sel) {
    // Indicamos el nuevo nombre del archivo que queremos subir
    $fichero = $attachment_sel['name'];
    $full_path = $path.$fichero;
    $archivo_ok = move_uploaded_file($attachment_sel['tmp_name'], $full_path);

    if (!$archivo_ok) {
        echo '<p class="mx-4"><b class="text-danger"> - No se ha podido subir el siguiente archivo: '.$attachment_sel['name'].'</b></p>';
    } else {
        $m++;
    }
    $i++;
}
echo '<h4 class="mx-4 mb-5"><b class="text-success"> Archivos subidos correctamente: '.$m.' de '.$i.'.</b></h4>';
?>
