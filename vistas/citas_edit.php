<?php
require '../conexion/conexion.php';

session_start();

// Verificar si el usuario está autenticado y tiene un rol válido
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    // Si no está autenticado o el rol no es válido (ni usuario ni admin), redirigir al login
    header("Location: ../vistas/login.php");
    exit;
}

// Obtener los valores pasados por la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';
$disponible = isset($_GET['disponible']) ? $_GET['disponible'] : '';

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
    </style>
</head>
<body>
<div class="content">
    <div class="form-container" style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
        <h2 style="text-align: center; font-size: 1.5em; margin-bottom: 1em;">Gestión de Citas</h2>

        <!-- Formulario para editar una cita -->
        <form action="../controladores/c_citas_admin.php" method="post">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" value="<?php echo $hora; ?>" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
            </div>
            <div class="form-group">
                <label for="disponible">Disponible</label>
                <select id="disponible" name="disponible" required style="width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;">
                    <option value="1" <?php echo ($disponible == '1') ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo ($disponible == '0') ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            <button type="submit" class="submit-btn" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 1em;">Actualizar</button>
        </form>
    </div>
</div>
</body>
</html>
