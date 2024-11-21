<?php
$host = '127.0.0.1';      
$port = '3307'; // Especifica aquí el puerto si es diferente
$dbname = 'cnr';
$username = 'administrador';       
$password = 'proyectocnr545';            

// Crear la conexión
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
} else {

}

?>