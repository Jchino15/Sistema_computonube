<?php
 include 'header.php';

require '../conexion/conexion.php';
?>
<head>
    <!-- Enlace a Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
        /* Estilos generales */
        

        /* Encabezado */
        .header {
            background-color: #2f3640;
            padding: 15px;
            color: #ffffff;
            text-align: center;
            width: 100%;
        }
        .header img {
            margin-bottom: 10px;
        }
        .navbar-custom {
            background-color: #3C4557;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
            padding: 15px 20px;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #e1e1e1;
        }
        /* Contenedor principal */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 650px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Estilos del mensaje de error */
        .error-message {
            color: red;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Estilos del formulario */
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group label {
            width: 100%;
            font-weight: bold;
        }

        .form-group .field {
            width: 48%;
            display: flex;
            flex-direction: column;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .form-group .radio-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }
       
        /* Pie de página */
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
            font-size: 14px;
            width: 100%;
            margin-top: auto;
        }
        .footer img {
            margin-top: 10px;
        }
</style>
</head>
    

<body>

<div class="header">
<img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>



<div class="content">
    <div class="login-container">
        <h2>Formulario de citas de Usuario</h2>
        <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($_GET['mensaje']); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <form action="../controladores/c_citas_save.php" method="POST">
            <div class="form-group">
                <div class="field">
                    <label>Nombres: </label>
                    <input type="text" name="nombres" placeholder="" class="form-control">
                </div>
                
                <div class="field">
                    <label>Apellidos: </label>
                    <input type="text" name="apellidos" placeholder="" class="form-control">
                </div>

                <!-- Campo de selección de fecha de cita -->
                <div class="form-group mb-3">
                    <label for="id">Fecha de cita:</label>
                    <select class="form-control" name="id" required>
                        <option value="" disabled selected>Seleccione una fecha y hora</option>
                        <?php
                        // Consulta para obtener las fechas y horas disponibles
                        $query = "SELECT id, fecha, hora FROM fechas_disponibles WHERE disponible = 1 AND CONCAT(fecha, ' ', hora) >= NOW()";
 // Si tienes una columna 'disponible'
                        $resultado = mysqli_query($conn, $query);

                        // Genera las opciones del select
                        while ($fecha = mysqli_fetch_assoc($resultado)) {
                            // Formatear la fecha para mostrarla en un formato legible
                            $fecha_formateada = date("d/m/Y", strtotime($fecha['fecha']));
                            $hora_formateada = date("H:i", strtotime($fecha['hora'])); // Formato de hora en 24h

                            echo "<option value='" . htmlspecialchars($fecha['id']) . "'>" . htmlspecialchars($fecha_formateada . " " . $hora_formateada) . "</option>";
                        }
                        ?>
                    </select>

                </div>

            </div>
            <button type="submit" class="btn btn-primary">Agendar</button>
        </form>
    </div>
</div>


<div class="footer">
    <p>Registros<br>
    5a Avenida Sur y 22 Calle Oriente, San Salvador, El Salvador C.A.</p>
    <p>Teléfono: 259X-5XXX</p>
    <img src="../img/logoRegistros2.png" width="100px" height="100px" alt="Escudo Gobierno de El Salvador">
</div>
<!-- Agregar Bootstrap JS al final del archivo -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


</body>
</html>
