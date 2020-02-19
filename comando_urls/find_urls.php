<?php
/**
Archivo para obtener todas las URLs dentro de una URL
*/
echo "\n";
//Obtenemos los argumentos si existen.
$arg1 = (isset($_SERVER['argv'][1])) ? trim($_SERVER['argv'][1]) : '';// URL - [STRING] - Indica la URL a partir de la cuál se empezará a buscar.
$arg2 = (isset($_SERVER['argv'][2])) ? trim($_SERVER['argv'][2]) : '';// Número de recursividad - [INT]
$arg3 = (isset($_SERVER['argv'][3])) ? trim($_SERVER['argv'][3]) : '';// Parámetro de prueba - Si está a 1 entonces el comando aceptará cualquier tipo de archivo o URL

// En el primer argumento: accedemos al manual con '-h'
// o indicamos una url donde empezaremos la búsqueda.

if ($arg1 == '-h' || $arg1 == '--help') {
	/////////////// MANUAL  ///////////////////////
	// En caso de acceder a la ayuda..
	echo "Imprime en pantalla todas las URLs detectadas dentro de la URL indicada\n";
	echo "php -f find_urls.php [URL] [número de recursividad] [Tercer parámetro de prueba -> '1' ]\n\n";
	exit;

}

elseif ($arg1=='') {
	//////////////// PARÁMETRO VACÍO ///////////////
	// Si no se indica ningún parámetro.
	echo "El primer parámetro es obligatorio\n";
	echo "Pruebe «php -f find_urls.php -h» o «php -f find_urls.php --help» para más información\n\n";
	exit;
}


else {
	// Comprobamos que la url enviada tiene un formato válido.
	if ((filter_var($arg1, FILTER_VALIDATE_URL)
	&& preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|](\.)[a-z]{2}/i",$arg1)) || $arg3 == '1') {
		// Comprobamos si la url existe.
		$file_headers = @get_headers($arg1);
		if((!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') && $arg3 != '1') {
		  // 404 no encontrada. No existe.
		  echo "«".$arg1."» Esta url no existe\n";
		  echo "Pruebe «php -f find_urls.php -h» o «php -f find_urls.php --help» para más información\n\n";
		  exit;

		} else {
			//////////// URL VÁLIDA y EXISTE //////////////
			// Comprobamos que el segundo argumento en caso de que exista sea un número mayor a cero..
			if ($arg2!='' && intval($arg2) <= 0) {
				// Segundo parámetro mayor a cero...
				echo "«".$arg2."» Solo se aceptan números mayores que cero.\n";
				echo "Pruebe «php -f find_urls.php -h» o «php -f find_urls.php --help» para más información\n\n";
				exit;
			}

			// Obtenemos confirmación de que queremos ejecutar este programa.
			echo "Seguro que quiere ejecutar este programa?  Escribe 'si' para continuar: ";
			$handle = fopen ("php://stdin","r");
			$line = fgets($handle);
			if(trim($line) != 'si'){
				// En caso de que no tengamos confirmación no se ejecuta y no se obtienen las URLs..
				echo "Proceso cancelado!\n\n";
				exit;
			}

			echo "\n";
			echo "Obteniendo urls...\n";
			// Obtenemos el contenido de la página..
			$urlContent = file_get_contents($arg1);

			$dom = new DOMDocument();
			@$dom->loadHTML($urlContent);
			$xpath = new DOMXPath($dom);
			$hrefs = $xpath->evaluate("/html/body//a");

			for($i = 0; $i < $hrefs->length; $i++){
				$href = $hrefs->item($i);
				$url = $href->getAttribute('href');
				$url = filter_var($url, FILTER_SANITIZE_URL);
				$file_headers2 = @get_headers($url);
				// validamos urls
				if((!filter_var($url, FILTER_VALIDATE_URL) === false && $file_headers2 && $file_headers2[0] != 'HTTP/1.1 404 Not Found') || $arg3 == '1'){
					echo "".$url."\n";

					// Obtenemos las urls recursivas.
					$n = 1;$url2=$url;
					if ($arg2 > 1) {
						$urlsRecursividad = getUrls($url2,$n,$arg2,$arg3);
					}
					$n++;
					echo "\n";
				} else {
					echo "".$url." (URL NO VÁLIDA/NO EXISTE)\n";
				}
			}
			echo "\n";
		}

	} else {
		// Url no tiene un formato válido...
		echo "«".$arg1."» no es una url válida\n";
		echo "Pruebe «php -f find_urls.php -h» o «php -f find_urls.php --help» para más información\n\n";
		exit;
	}
}

// Función para obtener todas las URLs recursivas.
// $url [string] - URL dentro de la cual obtendremos el resto de URLs.
// $n [int] - Indica en que punto de la recursividad nos encontramos.
// $recursividad [int] - Indica la profundidad de la recursividad.
function getUrls ($url,$n,$recursividad,$prueba) {
	$urlContent = file_get_contents($url);

	$dom = new DOMDocument();
	@$dom->loadHTML($urlContent);
	$xpath = new DOMXPath($dom);
	$hrefs = $xpath->evaluate("/html/body//a");

	for($m = 0; $m < $hrefs->length; $m++){
		$n2 = intval($n);
		$href = $hrefs->item($m);
		$url_2 = $href->getAttribute('href');
		$url_2 = filter_var($url_2, FILTER_SANITIZE_URL);
		$file_headers2 = @get_headers($url_2);
		// validamos urls
		if((!filter_var($url_2, FILTER_VALIDATE_URL) === false && $file_headers2 && $file_headers2[0] != 'HTTP/1.1 404 Not Found') || $prueba == '1'){
			echo str_repeat(" -- ",$n)."".$url_2."\n";
			$n2++;
			if ($n2 < $recursividad) {
				getUrls($url_2,$n2,$recursividad,$prueba);
			}
		} else {
			echo str_repeat(" -- ",$n)."".$url." (URL NO VÁLIDA/NO EXISTE)\n";
		}
	}
}

?>
