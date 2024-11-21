<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>Formulario de Inscripción de Usuario Simple</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

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

        /* Botón de Crear Usuario */
        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-group button {
            background-color: #2da8d8;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 3px;
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
        <h2>Formulario de Inscripción de Usuario Simple</h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($_GET['error']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form action="../controladores/c_registro.php" method="post">
            <div class="form-group">
                <div class="field">
                    <label>Nombre: </label>
                    <input type="text" name="nombre" placeholder="">
                </div>
                
                <div class="field">
                    <label>Apellido: </label>
                    <input type="text" name="apellido" placeholder="">
                </div>

                <div class="field">
                    <label>Género: </label>
                    <div class="radio-group">
                        <input type="radio" name="genero" id="masculino" value="Masculino">
                        <label for="masculino">Masculino</label>
                        <input type="radio" name="genero" id="femenino" value="Femenino">
                        <label for="femenino">Femenino</label>
                    </div>
                </div>

                <div class="field">
                    <label>Fecha de nacimiento: </label>
                    <input type="date" name="fecha_nacimiento">
                </div>

                <div class="field">
                    <label>País de nacionalidad:</label>
                    <select name="pais_nacionalidad">
                        <option value="El Salvador">El Salvador</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Estados Unidos">Estados Unidos</option>
                        <option value="Panamá">Panamá</option>
                    </select>
                </div>

                <div class="field">
                    <label>DUI:</label>
                    <input type="text" name="dui" placeholder="">
                </div>

                <div class="field">
                    <label>Correo electrónico: </label>
                    <input type="email" name="email" placeholder="">
                </div>

                <div class="field">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" placeholder="(503)">
                </div>

                <div class="field">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" placeholder="">
                </div>

                <div class="field">
                    <label>Contraseña:</label>
                    <input type="password" name="contraseña" placeholder="">
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit">Crear Usuario</button>
            </div>
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
