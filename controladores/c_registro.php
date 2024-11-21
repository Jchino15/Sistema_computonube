<?php
include('../conexion/conexion.php');

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dui = $_POST['dui'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $pais_nacionalidad = $_POST['pais_nacionalidad'];
    $contraseña = $_POST['contraseña']; // Contraseña sin cifrar

    // Verificar si todos los campos requeridos están completos
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion) || empty($correo) || empty($contraseña) || empty($fecha_nacimiento) || empty($genero) || empty($pais_nacionalidad)) {
        $error = "Todos los campos son obligatorios";
        header("Location: ../vistas/crearcuenta.php?error=" . urlencode($error));
        exit();
    } else {
        // Verificar si el correo electrónico ya está registrado
        if (!$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?")) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El correo electrónico ya está registrado.";
            header("Location: ../vistas/crearcuenta.php?error=" . urlencode($error));
            exit();
        } else {
            // Insertar el nuevo usuario en la base de datos
            if (!$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, dui, telefono, direccion, email, fecha_nacimiento, genero, pais_nacionalidad, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                die("Error en la preparación de la consulta de inserción: " . $conn->error);
            }
            $stmt->bind_param("ssssssssss", $nombre, $apellido, $dui, $telefono, $direccion, $correo, $fecha_nacimiento, $genero, $pais_nacionalidad, $contraseña);
            
            if ($stmt->execute()) {
                // Redirigir al formulario de inicio de sesión con el correo y la contraseña en la URL
                header("Location: ../vistas/login.php?correo=" . urlencode($correo) . "&contraseña=" . urlencode($contraseña));
                exit();
            } else {
                echo "Error en la ejecución: " . $stmt->error;
            }
        }
    }
}
?>
