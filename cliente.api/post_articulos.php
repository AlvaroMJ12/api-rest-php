<?php
// post_articulo.php


$url = "http://127.0.0.1/DAWES/API_REST/apirestauth/apirestauth/articulo.php";


$datos = [
    "art_nombre" => "Auriculares Bluetooth", // Cambia esto para no repetir nombres
    "art_categoria" => 1, // Asegúrate de que esta categoría exista (ej: 1 o 2)
    "art_cantidad" => 50
];

//  Inicializamos
$ch = curl_init();

// 4. Configuración para POST
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true); // Importante: Método POST
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos)); // Enviamos JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//  Cabeceras
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzAzODI2MDksImRhdGEiOnsiaWQiOiIxIiwibm9tYnJlcyI6IlVzdWFyaW8gRFdFUyJ9fQ.45E-IJyvFItxwsO6bVZnr7EomhxbixBhD5_4_KAQgZY'
));

// Ejecutar y mostrar respuesta
$respuesta = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error: ' . curl_error($ch);
} else {
    echo "Respuesta del servidor (POST):\n";
    echo $respuesta;
}

curl_close($ch);
?>