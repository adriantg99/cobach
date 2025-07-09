<!DOCTYPE html>
<html>

<head>
    <title>Bachilleresdesonora.edu.mx</title>
</head>

<body>
    <div>
        <h1>{{ $mailData['title'] }}</h1>

        <p>Estimado {{ $mailData['alumno'] }}, nos es grato infórmarte que ya se encuentra listo tu certificado
            @if ($mailData['certificado_tipo'] == 1)
                @if ($mailData['certificado_estatus'] == 'T')
                    total
                @else
                    parcial
                @endif
            @else
                duplicado
            @endif
            de estudios del plantel {{ $mailData['plantel'] }}, Adjunto a este correo.
        </p>

        <p>Es importante que como portador del certificado eres totalmente responsable del mismo.</p>
        <p>Descarga tu certificado</p>
        {{-- 
        @if ($mailData['certificado_estatus'] == 'T' && $mailData['certificado_tipo'] == 1)
            
        
        <p>En este link descarga tu diploma de capacitación: 
            
            @php
            $liga_encriptada = base64_encode('/alumnos_egresados/diplomacap/'.$mailData['alumno_id']);
            //$liga_encriptada = aHR0cDovL3NjZS1jb2JhY2gudGVzdC9hbHVtbm9zX2VncmVzYWRvcy9kaXBsb21hY2FwLzIyODA1OA==

    //URL quedaria:
        
        @endphp
        <a href="https://sice.cobachsonora.edu.mx/liga_externa/{{ $liga_encriptada }}">Diploma capacitación</a></p>
        @endif
         --}}
        <p>{{ $mailData['body'] }}</p>
        <p>
        <h3>ATENTAMENTE</h3>
        </p>
        <p><strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong></p>
        <p>“Este es un mensaje automático, favor de no responder este correo”</p>

    </div>
</body>

</html>
