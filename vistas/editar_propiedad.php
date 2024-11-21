<?php
include('../conexion/conexion.php');

// Verificar si se pasó el propiedad_id por la URL
if (isset($_GET['id'])) {
    $propiedad_id = $_GET['id'];
} else {
    // Si no se pasó el propiedad_id, redirigir o mostrar un mensaje de error
    header("Location: tablas_propiedades.php?mensaje=" . urlencode("No se especificó el ID de la propiedad."));
    exit();
}
// Consulta para obtener los datos de la propiedad seleccionada
$query = "SELECT p.direccion, p.area_m2, p.tipo, p.valor, p.estado, m.nombre_municipio, pp.fecha_adquisicion, pp.porcentaje_propiedad
FROM propiedades p
INNER JOIN persona_propiedades pp ON p.propiedad_id = pp.propiedad_id
INNER JOIN municipios m ON m.municipio_id = p.municipio_id
WHERE p.propiedad_id = '$propiedad_id'";
$resultadopropiedad =mysqli_query($conn, $query);
$resultadopro= mysqli_fetch_assoc($resultadopropiedad );

?>

<?php
session_start();

// Verificar que el usuario esté logueado y que el ID esté en la sesión
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    // Ahora puedes utilizar $usuario_id en tus consultas o lógica
} else {
    // Redirigir a la página de inicio de sesión si no está logueado
    header("Location: ../vistas/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Propiedad</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../img/casa.jpg');
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
        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            width: 550px;
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
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #0D33B3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
        }
        .btn {
            background-color: #0D33B3;
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

<div class="header">
<img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>


<div class="content">
    <div class="form-container">
        <h2>Formulario de Propiedad</h2>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($_GET['mensaje']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../controladores/c_editar_propiedad.php" method="post">
        <input type="hidden" name="propiedad_id" value="<?php echo htmlspecialchars($propiedad_id); ?>">

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required value="<?php echo htmlspecialchars($resultadopro['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="area_m2">Área (m²)</label>
                <input type="number" id="area_m2" name="area" required value="<?php echo htmlspecialchars($resultadopro['area_m2']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="Residencial" <?php echo ($resultadopro['tipo'] == 'Residencial') ? 'selected' : ''; ?>>Residencial</option>
                    <option value="Comercial" <?php echo ($resultadopro['tipo'] == 'Comercial') ? 'selected' : ''; ?>>Comercial</option>
                    <option value="Industrial" <?php echo ($resultadopro['tipo'] == 'Industrial') ? 'selected' : ''; ?>>Industrial</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="valor">Valor $</label>
                <input type="text" id="valor" name="valor" required value="<?php echo htmlspecialchars($resultadopro['valor']); ?>" placeholder="$ ____________">
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" required>
                    <option value="Resido" <?php echo ($resultadopro == 'Resido') ? 'selected' : ''; ?>>Resido</option>
                    <option value="Vendido" <?php echo ($resultadopro == 'Vendido') ? 'selected' : ''; ?>>Vendido</option>
                    <option value="Alquiler" <?php echo ($resultadopro == 'Alquiler') ? 'selected' : ''; ?>>Alquiler</option>
                </select>
            </div>

            <div class="form-group">
                <label for="porcentaje_propiedad">Porcentaje de la propiedad (1-100)</label>
                <input type="number" id="porcentaje_propiedad" name="porcentaje_propiedad" required value="<?php echo htmlspecialchars($resultadopro['porcentaje_propiedad']); ?>" placeholder="__________ %">
            </div>

            <div class="form-group">
                <label for="fecha_adquisicion">Fecha de adquisición</label>
                <input type="date" id="fecha_adquisicion" name="fecha_adquisicion" required value="<?php echo htmlspecialchars($resultadopro['fecha_adquisicion']); ?>" required>
            </div>

            <div class="form-group">
                <label for="municipio">Municipio</label>
                <input type="text" id="nombre_municipio" name="nombre_municipio" required value="<?php echo htmlspecialchars($resultadopro['nombre_municipio']); ?>" placeholder="Ingrese el municipio">
            </div>

            <button type="submit" class="submit-btn">Actualizar</button>
</form>

    </div>
</div>

<div class="footer">
    <p>Registros<br>
    5a Avenida Sur y 22 Calle Oriente, San Salvador, El Salvador C.A.</p>
    <p>Teléfono: 259X-5XXX</p>
    <img src="../img/logoRegistros2.png" width="100px" height="100px" alt="Escudo Gobierno de El Salvador">
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2D/9Kk7rfHEkYElk9PbjWI8JFSZZhxTd8fDFr5x09xM+mhhLDg7G5eiV0yJ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>       
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>