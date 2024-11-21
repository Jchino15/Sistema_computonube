<?php

require '../conexion/conexion.php';
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login si no está logueado
    header("Location: ../vistas/login.php?error=" . urlencode("Debes iniciar sesión."));
    exit;
}

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    // Redirigir al inicio si no es administrador
    header("Location: ../vistas/index.php?error=" . urlencode("No tienes permisos para acceder a esta página."));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../img/fondo_casa.png');
            background-size: contain;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .header {
            background-color: #2f3640;
            padding: 15px;
            color: #ffffff;
            text-align: center;
        }
        .navbar-custom {
            background-color: #3C4557;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            width: auto;
            text-align: center;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #ff8c00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .logout-text {
            color: #ffffff;
            text-decoration: none;
            position: absolute;
    right: 15px;
    top:10px;
    font-size: 16px;
        }
        .logout-text:hover {
            color: #cccccc;
        }
    </style>
</head>
<body>

<div class="header">
   <img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Centro de la navbar -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="admin_index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_propiedades_tabla.php">Propiedades</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_transacciones.php">Transacciones</a></li>
                <li class="nav-item"><a class="nav-link" href="citas_admin.php">Citas</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_contacto.php">Contáctenos</a></li>
            </ul>
            <!-- Alineación derecha para "Cerrar sesión" -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link logout-text" href="../controladores/c_cerrar_sesion.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="content p-3">
    <div class="form-container" style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
        <h2 style="text-align: center; font-size: 1.5em; margin-bottom: 1em;">Gestión de Citas</h2>

        <!-- Formulario para agregar una nueva cita -->
        <form action="../controladores/c_citas_admin.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="disponible">Disponible</label>
                <select id="disponible" name="disponible" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <button type="submit" class="submit-btn" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 1em;">Agregar Cita</button>
        </form>


    </div>
</div>

<div class="content">
<h2 class="mt-4 text-center">Citas Disponibles</h2>
    <div class="form-container">

        
    <?php
    // Capturar la fecha actual usando DateTime
    $fecha_actual = new DateTime();  // Captura la fecha y hora actual
    $fecha_actual = $fecha_actual->format('Y-m-d'); // Formatear solo la fecha (Y-m-d)

    // Mostrar la fecha actual para depuración
    echo "Fecha actual: " . $fecha_actual; // Solo para ver si la fecha está correcta

    // Consulta para obtener las citas con fecha mayor o igual a la fecha actual y disponibles
    $query = "
        SELECT id, fecha, hora, disponible 
        FROM fechas_disponibles 
        WHERE DATE(fecha) >= '$fecha_actual' AND disponible = 1
        ORDER BY fecha ASC"; // Solo citas disponibles y futuras

    // Ejecutar la consulta
    $result = mysqli_query($conn, $query);
    ?>
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($_GET['mensaje']); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

    <table class="table table-bordered mt-4">
    <thead>
        <tr>
            
            <th>Fecha</th>
            <th>Hora</th>
            <th>Disponible</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        

        if ($result && mysqli_num_rows($result) > 0) {
            while ($cita = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>{$cita['fecha']}</td>";
                echo "<td>{$cita['hora']}</td>";
                echo "<td>" . ($cita['disponible'] ? 'Sí' : 'No') . "</td>";
                echo "<td>";
                // Botón de editar con redirección y llenado automático del formulario
                echo "<button type='button' class='btn btn-primary' onclick=\"window.location.href='citas_edit.php?id={$cita['id']}&fecha={$cita['fecha']}&hora={$cita['hora']}&disponible={$cita['disponible']}';\">Editar</button>";
                
                echo "<button type='button' class='btn btn-danger eliminar-cita' data-id='{$cita['id']}'>Eliminar</button>";


                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay citas disponibles.</td></tr>";
        }
        
        ?>
    </tbody>
</table>


    </div>
</div>t
<script>
    document.querySelectorAll('.eliminar-cita').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        if (confirm('¿Está seguro de eliminar esta cita?')) {
            fetch('../controladores/c_citas_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&id=${id}`,
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Mostrar mensaje de respuesta
                    location.reload(); // Recargar la tabla actualizada
                })
                .catch(error => console.error('Error:', error));
        }
    });
});

</script>
<!-- Agregar Bootstrap JS al final del archivo -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
