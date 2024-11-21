<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #f5f7fb;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 0 !important;
        }
        .login-container {
            width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .slider-image {
            max-height: 400px; 
            object-fit: cover; 
            width: 100%; 
        }
        .carousel {
            margin-bottom: 0;
        }
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
            font-size: 14px;
            width: 100%;
        }
        .footer img {
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #4d9ffb;
            border: none;
            margin-top: 15px;
        }
        .btn-secondary {
            background-color: #333;
            border: none;
            margin-top: 15px;
        }
        .texto {
            font-size: 125%;
        }
        .crear-cuenta {
            color: green;
            font-size: 100%;
            margin-top: 15px;
            margin-left: 300px;
        }
        .card-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
        }
        .card {
            flex-grow: 1;
            flex-basis: 0;
            margin: 0 10px;
        }
        .icon-button-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            background-color: #2f3640;
            padding: 20px 0;
        }
        .icon-button {
            text-decoration: none;
            color: #ffffff;
            text-align: center;
            font-size: 16px;
        }
        .icon-button:hover {
            color: #e1e1e1;
        }
        .icon-button .icon img {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>
