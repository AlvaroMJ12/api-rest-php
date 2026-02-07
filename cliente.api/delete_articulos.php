<?php
// delete_articulo.php

// ID del artículo a eliminar
$id_a_borrar = 1; 

$url = "http://127.0.0.1/DAWES/API_REST/apirestauth/articulo.php?id=" . $id_a_borrar;

$ch = curl_init();

// Configuración para DELETE
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Método DELETE
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzAzODI2MDksImRhdGEiOnsiaWQiOiIxIiwibm9tYnJlcyI6IlVzdWFyaW8gRFdFUyJ9fQ.45E-IJyvFItxwsO6bVZnr7EomhxbixBhD5_4_KAQgZY'
));

$respuesta = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error: ' . curl_error($ch);
} else {
    echo "Respuesta del servidor (DELETE):\n";
    echo $respuesta;
}

curl_close($ch);
?>