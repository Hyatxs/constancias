<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            font-family: 'Open Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .u-sheet {
            width: 277mm;
            height: 190mm;
            padding: 10mm;
            box-sizing: border-box;
            position: relative;
        }

        .u-image-1 {
            position: absolute;
            left: 10mm;
            top: 20mm;
            width: 50mm;
            height: auto;
        }

        .u-line-vertical {
            position: absolute;
            left: 70mm;
            top: 10mm;
            height: 170mm;
            border-left: 4px solid #000;
        }

        .u-header {
            position: absolute;
            top: 10mm;
            left: 80mm;
            width: 180mm;
            text-align: center;
        }

        .u-header h1 {
            font-size: 20px;
            margin: 0;
        }

        .u-header p {
            font-size: 14px;
            margin: 0;
        }

        .u-title {
            position: absolute;
            top: 50mm;
            left: 80mm;
            width: 180mm;
            text-align: center;
        }

        .u-title h2 {
            font-size: 36px;
            margin: 0;
        }

        .u-content {
            position: absolute;
            top: 80mm;
            left: 80mm;
            width: 180mm;
            text-align: center;
        }

        .u-content p {
            font-size: 18px;
            margin: 0;
        }

        .u-content p span {
            font-weight: bold;
        }

        .u-footer {
            position: absolute;
            bottom: 10mm;
            right: 10mm;
            text-align: center;
        }

        .u-footer img {
            width: 50mm;
            height: auto;
        }

        .u-footer p {
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="u-sheet">
        <img class="u-image-1" src="images/constancia/logo1.PNG" alt="Logo">
        <div class="u-line-vertical"></div>
        <div class="u-header">
            <h1>La Benemérita Universidad Autónoma de Puebla</h1>
            <p>A través de la Escuela de Formación Docente y Desarrollo Académico</p>
        </div>
        <div class="u-title">
            <h2>Constancia</h2>
        </div>
        <div class="u-content">
            <p>A: <span>{{$nombre}} {{$apellido_paterno}} {{$apellido_materno}}</span></p>
            <p>Por haber acreditado el curso: <span>{{ $nombre_evento }}</span></p>
            <p>Que se llevó a cabo del <span>{{ $fecha_inicio }}</span> al <span>{{ $fecha_fin }}</span></p>
            <p>Con una duración de <span>{{ $duracion_horas }} horas</span>, modalidad <span>{{ $modalidad }}</span></p>
        </div>
        <div class="u-footer">
            <img src="images/constancia/2.PNG" alt="Firma">
            <p>Nombre de la directora</p>
        </div>
    </div>
</body>
</html>