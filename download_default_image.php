<?php
$url = 'https://via.placeholder.com/400x300/cccccc/666666/?text=Auto+Predeterminado';
$img = file_get_contents($url);
file_put_contents(__DIR__ . '/assets/img/default-car.jpg', $img);
echo "Imagen descargada correctamente";