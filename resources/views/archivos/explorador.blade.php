<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Explorer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #2c3e50;
        }

        .directory,
        .file {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            background-color: #ecf0f1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .directory a,
        .file a {
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
        }

        .directory a:hover,
        .file a:hover {
            text-decoration: underline;
        }

        .file {
            background-color: #bdc3c7;
        }

        .directory i,
        .file i {
            margin-right: 10px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @extends('layouts.dashboard-layout')
    @php
        use App\Models\Certificados\CertificadoModel;
    @endphp
    @section('content')
        <div class="container">
            <h1>Explorador de certificados emitidos</h1>

            @if (session('error'))
                <div class="error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('file-explorer') }}" method="GET">
                <input type="text" name="search" value="{{ old('search', $search) }}"
                    placeholder="Buscar archivos o carpetas...">
                <button type="submit">Buscar</button>
            </form>

            <h2>Carpetas por generación de emisión</h2>
            @foreach ($directories as $directory)
                <div class="directory">
                    <i class="fas fa-folder"></i>

                    <a
                        href="{{ route('file-explorer.folder', ['folder' => urlencode($folder . '/' . basename($directory))]) }}">
                        {{ basename($directory) }}
                    </a>
                </div>
            @endforeach

            <h2>Archivos</h2>
            @foreach ($processedFiles as $file)
            <div class="file" title="{{ $file['path'] }}">
                <i class="fas fa-file-pdf"></i>
        
                <!-- Mostrar nombre del archivo -->
                {{ $file['name'] }}
                
                <!-- Mostrar número extraído, si existe -->
                @if ($file['validacion'])
                    <span style="font-weight: bold; color: green;">Estatus: {{ $file['validacion'] }}</span>
                @endif
        
                <a
                    href="{{ route('file-explorer.download', ['file' => urlencode(str_replace(public_path(), '', $file['path']))]) }}">
                    Descargar
                </a>
            </div>
        @endforeach
        </div>
    @endsection
</body>

</html>
