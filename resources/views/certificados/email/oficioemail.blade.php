<!DOCTYPE html>
<html>
<head>
    <title>Bachilleresdesonora.edu.mx</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>

    <p>{{ $mailData['solicitante'] }}</p>

    <p>Por éste medio, le hacemos llegar la respuesta a la solicitud de validación de certificados de estudio.
    </p>

    <p>Atentamente</p>

    <p>{{ $mailData['body'] }}</p>
</body>
</html>
