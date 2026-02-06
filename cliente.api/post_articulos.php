<?php
// post_articulo.php

// Datos del nuevo artículo (Lo que vas a insertar)
$nuevoArticulo = [
    "art_nombre" => "Monitor 24 pulgadas",
    "art_categoria" => 1, // Asegúrate de que este ID de categoría exista
    "art_cantidad" => 50
];

// Configuración
$url = "http://localhost:3000/DAWES/API%20REST/apirestauth/apirestauth/articulo.php";
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzAzODA2NTksImRhdGEiOnsiaWQiOiIxIiwibm9tYnJlcyI6IlVzdWFyaW8gRFdFUyJ9fQ.5nKEUDcmAPEhyy2MkB5q7G_62rF4EIliyDBbvHSmHQw"; // Tu token actual

$ch = curl_init();

// Opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true); // Indicamos que es POST
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nuevoArticulo)); // Convertimos array a JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Cabeceras (Importante: Content-Type + Authorization)
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
));

// Ejecutar
$respuesta = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error en cURL: ' . curl_error($ch);
} else {
    echo "Respuesta de la API:\n";
    echo $respuesta;
}

curl_close($ch);
?>