<?php
include('../conexion/conexion.php'); ?>
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

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="tablas_propiedades.php">Propiedades</a></li>
                <li class="nav-item"><a class="nav-link" href="transacciones.php">Transacciones</a></li>
                <li class="nav-item"><a class="nav-link" href="Citas.php">Citas</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_contacto.php">Contáctenos</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content">
    <div class="form-container">
        <h2>Formulario de Propiedad</h2>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($_GET['mensaje']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../controladores/c_propiedades.php" method="post">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required placeholder="Ingrese la dirección">
            </div>
            <div class="form-group">
                <label for="area_m2">Área (m²)</label>
                <input type="number" id="area_m2" name="area" required placeholder="Ej. 150">
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="Residencial">Residencial</option>
                    <option value="Comercial">Comercial</option>
                    <option value="Industrial">Industrial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="valor_estado">Valor $</label>
                <input type="text" id="valor" name="valor" required placeholder="$ ____________">
            </div>
            <script>
                const valorInput = document.getElementById("valor");

                valorInput.addEventListener("input", function() {
                    // Asegurarse de que el valor comience con "$"
                    if (!this.value.startsWith("$")) {
                        this.value = "$" + this.value.replace(/\$/g, ''); // Elimina cualquier otro signo $ duplicado
                    }

                    // Permitir solo números positivos con hasta dos decimales después de "$"
                    const valueWithoutDollar = this.value.replace(/\$/g, '');
                    const regex = /^\d+(\.\d{0,2})?$/;

                    // Si no cumple con el formato permitido, eliminamos el último carácter ingresado
                    if (!regex.test(valueWithoutDollar)) {
                        this.value = "$" + valueWithoutDollar.slice(0, -1);
                    }
                });

                valorInput.addEventListener("blur", function() {
                    // Remueve el símbolo "$" si el campo está vacío al perder el foco
                    if (this.value === '$') {
                        this.value = '';
                    }
                });
            </script>



            <div class="form-group">
                <label for="tipo">Estado</label>
                <select name="estado" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="Resido">Resido</option>
                    <option value="Vendido">Vendido</option>
                    <option value="Alquiler">Alquiler</option>
                </select>
            </div>

            <div class="form-group">
                <label for="porcentaje_propiedad">Porcentaje de la propiedad (1-100)</label>
                <input type="text" id="porcentaje_propiedad" name="porcentaje_propiedad" required placeholder="__________ %">
            </div>

            <script>
                const porcentajeInput = document.getElementById("porcentaje_propiedad");

                porcentajeInput.addEventListener("input", function() {
                    // Elimina cualquier símbolo % existente
                    let value = this.value.replace(/%/g, '');

                    // Si el campo está vacío, no hacer nada
                    if (value === '') {
                        this.value = '';
                        return;
                    }

                    // Validar que el valor sea un número y esté dentro del rango 1-100
                    let numericValue = parseFloat(value);

                    if (isNaN(numericValue)) {
                        numericValue = ''; // Si no es un número, se deja vacío
                    } else if (numericValue < 1) {
                        numericValue = 1;
                    } else if (numericValue > 100) {
                        numericValue = 100;
                    }

                    // Asignar el valor actualizado con el símbolo de porcentaje
                    this.value = numericValue + '%';
                });

                porcentajeInput.addEventListener("blur", function() {
                    // Elimina el símbolo % si el campo está vacío al perder el foco
                    if (this.value === '%') {
                        this.value = '';
                    }
                });
            </script>

            <div class="form-group">
                <label for="fecha_adquisicion">Fecha de adquisición</label>
                <input type="date" id="fecha_adquisicion" name="fecha_construccion" required>
            </div>

            <div class="form-group">
                <label for="municipio">Municipio</label>
                <input type="text" id="municipio" name="municipio" required placeholder="Ingrese el municipio">
            </div>
            <a href="tablas_propiedades.php" class="btn btn-regresar">Tabla propiedades</a>
            <button type="submit" class="submit-btn">Enviar</button>
            
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