<?php
// get_articulos.php

// 1. Definimos los parámetros de búsqueda
$parametros = [

];

// 2. Construimos la Query String
$queryString = http_build_query($parametros);

// 3. URL CORREGIDA: Cambiamos el espacio " " por "%20"
// Si tu carpeta real tiene espacio, en la URL pon %20
$url = "http://127.0.0.1/DAWES/API_REST/apirestauth/articulo.php?" . $queryString;

// 4. Inicializamos cURL
$ch = curl_init();

// 5. Configuración
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

// 6. Token (Asegúrate de que este token sea reciente, si caducó te dará 403)
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzAzODI2MDksImRhdGEiOnsiaWQiOiIxIiwibm9tYnJlcyI6IlVzdWFyaW8gRFdFUyJ9fQ.45E-IJyvFItxwsO6bVZnr7EomhxbixBhD5_4_KAQgZY',
    'Content-Type: application/json' // Es buena práctica añadir esto también
));

// 7. Ejecutamos
$respuesta = curl_exec($ch);

// 8. DIAGNÓSTICO DE ERRORES (Importante para saber si falla)
if(curl_errno($ch)){
    echo 'Error en cURL: ' . curl_error($ch);
} else {
    // Si todo ha ido bien, mostramos la respuesta
    // (Si está vacía, es que no existe el artículo con ID 1)
    echo $respuesta;
}

// 9. Cerramos
curl_close($ch);
?>