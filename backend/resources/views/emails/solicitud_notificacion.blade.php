<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notificación de Solicitud</title>
</head>
<body>
    <h2>{{ $mensaje }}</h2>
    <p><strong>Título:</strong> {{ $solicitud->title }}</p>
    <p><strong>Descripción:</strong> {{ $solicitud->description }}</p>
    <p><strong>Estado:</strong> {{ $solicitud->status }}</p>
</body>
</html>
