<?php
// put_articulo.php

// ID del artículo que vas a editar
$id_a_modificar = 1; 

// URL con el parámetro ID
$url = "http://127.0.0.1/DAWES/API_REST/apirestauth/articulo.php?id=" . $id_a_modificar;

// Datos nuevos (Modificamos cantidad o nombre)
$datosNuevos = [
    "art_nombre" => "Auriculares (Oferta)",
    "art_cantidad" => 40
];

$ch = curl_init();

// Configuración para PUT
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Método PUT manual
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosNuevos));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzAzODI2MDksImRhdGEiOnsiaWQiOiIxIiwibm9tYnJlcyI6IlVzdWFyaW8gRFdFUyJ9fQ.45E-IJyvFItxwsO6bVZnr7EomhxbixBhD5_4_KAQgZY'
));

$respuesta = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error: ' . curl_error($ch);
} else {
    echo "Respuesta del servidor (PUT):\n";
    echo $respuesta;
}

curl_close($ch);
?>