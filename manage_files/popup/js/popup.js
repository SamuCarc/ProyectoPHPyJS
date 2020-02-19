//<!--FUNCIÓN DE POPUP -->
/**
 * Creamos una ventana modal obteniendo los datos de una página ajax.
 *
 * @param string $url_popup ejemplo de formato "/ajax/datos.php". Indica la url de la cual obtendremos los datos de la ventana.
 * @param string $data_popup ejemplo de formato "action=ObtUsuarios&nombre=Samuel". Datos que obtendremos en el ajax.
 * @param string $div_popup id del div en el que insertarán los datos del popup, el titulo del div indicará el nombre de
 *                la ventana modal y es obligatorio (Si es muy largo se cortará el titulo y se añadirá al final '...').
 *                Ejemplo --> <div id="buscar-usuario" title="Usuario Samuel"></div>
 * @return boolean
 */
function popup (url_popup, data_popup, div_popup) {

	$.ajax({
		url: url_popup,
        type: 'POST',
		data: data_popup,
        dataType: 'html',
		beforeSend: function(){
			// Mientras se carga la ventana se abrirá con el titulo y cargando.
			$('.popup-overlay').remove();
			$('#'+div_popup).html('');
			$('#'+div_popup).removeClass('content-popup_primary');

			// Añadimos contenido en el div vacío del popup.
			$('#'+div_popup).after('<div class="popup-overlay"></div>');
			$('#'+div_popup).addClass('content-popup_primary');
			var title = '';
			var full_title = $('#'+div_popup).attr('title');
			if (full_title.length > 47) {
				title = full_title.slice(0, 44) + '...';
			} else {
				title = full_title;
			}
			// Mostramos el popup de usuarios.
			$('#'+div_popup).show();


			$('#'+div_popup).html('<div class="title_popup"><div class="title" title="'+full_title+'"><h3>'+title+'</h3></div><div class="close" title="Cerrar ventana de dialogo"><i class="fa fa-window-close fa-lg fa-fw"></i></div></div><div class="content-popup"></div>');
			$('.content-popup').html('<img src="/popup/images/load.gif" style="width: 100px;margin:0 auto;display: flex;padding-top: 50px;">');
		}
	}).done(function(data){

        $('.content-popup').html(data);

        // Si se cierra borramos todo el conido del div principal y eliminamos el overlay.
        $('.close').on('click', function(){
			$('.popup-overlay').remove();
            $('#'+div_popup).html('');
            $('#'+div_popup).removeClass('content-popup_primary');
		});

        $('.popup-overlay').on('click', function(){
			$('.popup-overlay').remove();
            $('#'+div_popup).html('');
            $('#'+div_popup).removeClass('content-popup_primary');
		});

	}).fail(function(xhr, error){
		$('#'+div_popup).html("");
        $('#'+div_popup).append('<br><b style="font-size: 16px; color: #c12121"> ¡Error! Problema para visualizar esta pantalla.<br> Si el problema persiste porfavor contacte con su administrador. </b>');
	});
}
