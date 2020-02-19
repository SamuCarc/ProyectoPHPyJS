<?php
// OBTENEMOS LOS DATOS DEL GET.
$action = isset($_POST['action']) ? $_POST['action'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : '';
$path='../adjuntos/'.$month.'/*.*';
$fileList  = glob($path);

// En caso de que queramos obtener los datos de la tabla
?>
<div class="container" id="table_files">
<?php
if ($action=='get_data') {
    // Si encontramos archivos en el mes indicado
    if (!empty($fileList)) {
        ?>
            <p id='count_nom' class='font-weight-bold'><?php echo count($fileList) ?> Nómina/s</p>
            <div class='row title_reg'>
                <div class='col-5'><p>Fichero</p></div>
                <div class='col-3'><p>Mes</p></div>
                <div class='col-4'><p>LINKS</p></div>
            </div>
            <div class='body_reg mb-5'>
                <?php

                // Completamos la tabla con los archivos de este mes.
                $i=0;
                foreach($fileList as $full_path){
                    $f=array();
                    $f=explode('/',$full_path);
                    $filename = $f[3];
                    ?>
                    <div class="row">
                        <div class='col-5'><p><?php echo $filename ?></p></div>
                        <div class='col-3'><p><?php echo $month ?></p></div>
                        <div class='col-4 d-flex desc justify-content-center' >
                            <form class="m-1" id="form-download<?php echo $i ?>" action="/popup/ajax/descargar.php" method="post">
                                <input type="hidden" name="action" value="download">
                                <input type="hidden" name="month" value="<?php echo $month ?>">
                                <input type="hidden" name="fichero" value="<?php echo $filename ?>">
                                <input type="submit" id='bt-download<?php echo $i ?>' class="button-pink-slim m-1" value="DESCARGAR">
                            </form>
                            <form class="m-1" id="form-download<?php echo $i ?>" action="/popup/ajax/descargar.php" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="month" value="<?php echo $month ?>">
                                <input type="hidden" name="fichero" value="<?php echo $filename ?>">
                                <input type="submit" id='bt-delete<?php echo $i ?>' class="button-del-sm m-1" value="ELIMINAR">
                            </form>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
        <?php
        // Si no encontramos ningún archivo en el mes indicado.
    } else {

        ?>
        <h4 class='text-center font-weight-bold mt-5'>No se encuentra ningún archivo en la fecha seleccionada</h4><br>
        <?php
    }
    return false;
}
?>
</div>
<script type="text/javascript">

    function get_table_data () {
        $("#table_files").html('');
        $.ajax({
            url: '/popup/ajax/ver_archivos.php',
            type: 'post',
            data: 'action=get_data&month=<?php echo $month ?>',
            dataType: 'html',
        }).done(function(data){
            $("#table_files").html(data);
        }).fail(function(xhr, error){
            alert(error);
        });
    }

    $(document).ready(function() {
        // Cargamos los datos de la tabla.
        get_table_data();
        //////////////   DESCARGAR //////////////////
        // Cada vez que pulsamos en descargar un archivo pedirá antes una confirmación.
        $('input[id^="bt-download"]').click(function(e){
            e.preventDefault();
            var fichero = $(this).siblings('input[name="fichero"]').val();
            var confirm = window.confirm("¿Está seguro que desea descargar el archivo '"+fichero+"' del mes de <?php echo $month ?>?");
            if (confirm) {
                $(this).parent().submit();
            }
        })


        //////////////   ELIMINAR //////////////////
        // Cuando queramos eliminar un archivo pedirá una confirmación antes, enviará una alerta cuando se realice la acción
        // y eliminará la fila con los datos del archivo borrado.
        $('input[id^="bt-delete"]').click(function(e){
            e.preventDefault();
            var fichero = $(this).siblings('input[name="fichero"]').val();
            var confirm = window.confirm("¿Está seguro que desea eliminar este archivo '"+fichero+"' del mes de <?php echo $month ?>? Una vez realizada esta acción no podrá ser revertida.");
            if (confirm) {
                // Realizamos la acción de eliminar por ajax para no redireccionar la página.
                var form = $(this).parent();
                var data_form = form.serialize();
                formData = data_form;
                $.ajax({
                    url: '/popup/ajax/descargar.php',
                    type: 'post',
                    data: formData,
                    dataType: 'html',
                }).done(function(data){
                    if (data='succ') {
                        // ENVIAMOS UNA ALERTA Y ELIMINAMOS LA FILA CON LOS DATOS DEL ARCHIVO QUE YA HA SIDO BORRADO
                        alert("Archivo eliminado correctamente: '"+fichero+"'");
                        get_table_data();
                    } else {
                        alert(data);
                    }
                }).fail(function(xhr, error){
                    alert(error);
                });
            }
        })
    })
</script>
