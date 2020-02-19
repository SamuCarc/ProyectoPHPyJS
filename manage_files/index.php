<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='utf-8'>
    <meta name='description' content='Pagina principal'>
    <meta name='author' content='César Samuel Carcasés Gómez'>
    <title>PÁGINA DE POPUP</title>
    <!-- Font Awesome -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css'>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href='/popup/libs/mdbootstrap/css/bootstrap.min.css' rel='stylesheet'>
    <!-- Material Design Bootstrap -->
    <link href='/popup/libs/mdbootstrap/css/mdb.min.css' rel='stylesheet'>
    <!-- Your custom styles (optional) -->
    <link href='/popup/libs/mdbootstrap/css/style.css' rel='stylesheet'>
    <!-- Custom styles for this template -->
    <link href='/popup/css/index.css' rel='stylesheet'>
</head>
<body>
    <?php
    // Obtenemos la fecha del mes actual para ponerla por defecto
    $month = date("m");
    ?>
    <div class="text-center d-flex justify-content-center my-4">
        <div class="card w-75">
            <div class="card-header">
                <h4 class="font-weight-bold text-white">GESTIÓN DE ARCHIVOS</h4>
            </div>
            <form class="" id='upload-files-form' action="/popup/pages/subir.php" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="md-form offset-4 col-4">
                            <label class="label-select">Elige un mes*</label>
                            <!--  OBTENEMOS EL MES POR DEFECTO -->
                            <select id="month" name="month" class="select-css">
                                <option value='enero' <?php if($month == '1')  echo 'selected';?>>Enero</option>
                                <option value='febrero' <?php if($month == '2')  echo 'selected';?>>Febrero</option>
                                <option value='marzo' <?php if($month == '3')  echo 'selected';?>>Marzo</option>
                                <option value='abril' <?php if($month == '4')  echo 'selected';?>>Abril</option>
                                <option value='mayo' <?php if($month == '5')  echo 'selected';?>>Mayo</option>
                                <option value='junio' <?php if($month == '6')  echo 'selected';?>>Junio</option>
                                <option value='julio' <?php if($month == '7')  echo 'selected';?>>Julio</option>
                                <option value='agosto' <?php if($month == '8')  echo 'selected';?>>Agosto</option>
                                <option value='septiembre' <?php if($month == '9')  echo 'selected';?>>Septiembre</option>
                                <option value='octubre' <?php if($month == '10') echo 'selected'; ?>>Octubre</option>
                                <option value='noviembre' <?php if($month == '11') echo 'selected'; ?>>Noviembre</option>
                                <option value='diciembre' <?php if($month == '12') echo 'selected'; ?>>Diciembre</option>
                            </select>
                        </div>
                        <div class="col-6 offset-3 my-5 text-center file-upload-wrapper float-left" data-text="">
                            <input name="file-upload-field[]" type="file" id="file-upload-field" value="" multiple accept=""></input>
                        </div>

                        <div class="col-12 mt-5 text-center">
                            <a href='' style="width: 320px;" id="get-files" class='btn button-href mt-5'>Ver archivos mes de <span></span></a>
                        </div>
                        <div class="col-12 mb-5 text-center">
                            <a href='' style="width: 320px;" id="upload-files" class='btn button-grey'>Subir archivo/s mes de <span></span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- DIV VACIO EN EL QUE SE INTRODUCIRÁN TODOS LOS DATOS DE LOS ARCHIVOS -->
            <div id="search-files" title="GESTIÓN DE ARCHIVOS"></div>
        </body>
        <script type="text/javascript" src="/popup/libs/mdbootstrap/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="/popup/libs/mdbootstrap/js/jquery-ui.min.js"></script>

        <script type="text/javascript" src="/popup/libs/mdbootstrap/js/popper.min.js"></script>
        <script type="text/javascript" src="/popup/libs/mdbootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/popup/libs/mdbootstrap/js/mdb.min.js"></script>
        <script type="text/javascript" src="/popup/js/popup.js"></script>
        <script type="text/javascript">
        // FUNCIÓN PARA TEXTO NOMBRE DE DESCARGA DE ARCHIVOS
        document.addEventListener("DOMContentLoaded", init, false);
        function init() {
            $('#file-upload-field').on('change', function(e){
                if(!e.target.files) return;

                $('#selectedFiles .tab-adj').html('');
                $(".file-upload-wrapper").attr("data-text","");

                var files = e.target.files;
                $(".file-upload-wrapper").attr("data-text",files.length+" archivos seleccionados").addClass('active');
            });
        }
        // FIN FUNCIÓN PARA TEXTO NOMBRE DE DESCARGA DE ARCHIVOS
        $(document).ready(function (e) {
            // Cada vez que hagamos un cambio en el select actualizar frase del BOTÓN
            var m = $('#month').val();
            $('#get-files span').text(m);
            $('#upload-files span').text(m);
            $('#month').change(function () {
                var m2 = $(this).val();
                $('#get-files span').text(m2);
                $('#upload-files span').text(m2);
            })

            $("#get-files").click(function(e){
                e.preventDefault();
                // OBTEMMOS EL MES QUE INDICAMOS EN EL SELECT.
                var month = $('#month').val();

                popup('/popup/ajax/ver_archivos.php', 'month='+month, 'search-files');
            })

            $('#upload-files').click(function(e) {
                e.preventDefault();
                if ($('#file-upload-field').get(0).files.length === 0) {
                    alert('Indique los archivos que desea subir');
                    return false;
                }
                var month = $('#month').val();
                var confirm = window.confirm("¿Está seguro que desea subir archivos al mes de "+month+"?");
                if (confirm) {
                    $('#upload-files-form').submit();
                }
            })


        })

        </script>
