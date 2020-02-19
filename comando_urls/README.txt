Archivo para obtener todas las URLs dentro de una determinada URL.

Para ejecutarlo:
1. Ir a la consola de Linux y acceder al directorio donde se encuentra el archivo "find_urls.php".
3. Ejecutar el comando ----   
	php -f find_urls.php URL(obligatorio) parámetro2(opcional) parámetro3(opcional) 



/// Para acceder a la ayuda ejecutar ///
"php -f find_urls.php -h"    ó  "php -f find_urls.php --help"

/// Parámetros///
- El primer parámetro consistirá en una URL absoluta del tipo "https://google.es" por ejemplo
- El segundo parámetro es opcional y indicará el nivel de recursividad a la hora de buscar las URLs.
- El tercer parámetro también es opcional y si es "1" se podrá indicar en el primer parámetro cualquier tipo de dirrección, ya sea URL o un archivo cualquiera del ordenador.

EJEMPLO --> php -f find_urls.php https://www.google.es 1



--------------------- IMPORTANTE ------------------------------
Debido a que una URL cualquiera puede albergar cientos de URLs, se puede probar este archivo ejecutando el siguiente comando en la consola.

 " php -f find_urls.php archivos_prueba/primer_url.html  3  1 "

El directorio "archivos_prueba" contiene varios documentos de tipo HTML que cada uno de estos contiene una o dos direcciones de prueba. Cambiando el segundo parámetro se puede probar la recursividad.
