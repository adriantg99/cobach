<table>
    <tr>
        <td>PLANTEL:</td>
        <td><strong>{{ $grupo->plantel->nombre }}</strong></td>
    </tr>
    <tr>
        <td>GRUPO:</td>
        <td><strong>{{ $grupo->descripcion }}</strong></td>
    </tr>
    <tr>
        <td>TURNO:</td>
        <td><strong>{{ $grupo->turno->nombre }}</strong></td>
    </tr>
    <tr>
        <td>CICLO ESCOLAR:</td>
        <td><strong>{{ $grupo->ciclo->nombre }}</strong></td>
    </tr>
    <tr>
        <td>PARCIAL:</td>
        <td><strong>{{ $parci }}</strong></td>
    </tr>
    <tr></tr>
    @foreach ($cursos as $curso)
        @if (empty($curso->obtener_docente->nombre))
            @continue
        @endif
        <tr>
            <td><strong>{{ $curso->asignatura->clave }} - {{ $curso->asignatura->nombre }} -
                    {{ $curso->obtener_docente->expediente }} {{ $curso->obtener_docente->nombre }}
                    {{ $curso->obtener_docente->apellido1 }} {{ $curso->obtener_docente->apellido2 }}</strong></td>
        </tr>
    @endforeach
    <tr></tr>
    <thead>
        <tr>
            <th>EXPEDIENTE</th>
            <th>NOMBRE</th>
            <th>APELLIDOS</th>
            <th>GENERO</th>
            @foreach ($cursos as $curso)
                <th><strong>{{ $curso->asignatura->nombre }}</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i <= $cant_alumn; $i++)
            @php
                $cont = 0;
            @endphp
            <tr>
                @foreach ($cursos as $curso)
                    @php
                        $asign = $array_calificaciones[$curso->id][0];
                    @endphp
                    @if (isset($asign[$i]))
                        {{-- Código normal --}}
                    @else
                        @continue
                    @endif
                    @if ($cont == 0)
                        <td>{{ $asign[$i]->noexpediente }}</td>
                        <td>{{ $asign[$i]->nombre }}</td>
                        <td>{{ $asign[$i]->apellidos }}</td>
                        <td>
                            @php
                                $alum = App\Models\Adminalumnos\AlumnoModel::find($asign[$i]->alumno_id);
                            @endphp
                            {{ $alum->sexo }}
                        </td>
                        <td>{{ $asign[$i]->calificacion }}</td>
                    @else
                        <td>{{ $asign[$i]->calificacion }}</td>
                    @endif

                    @php
                        $cont++;
                    @endphp
                @endforeach
            </tr>
        @endfor
    </tbody>
</table>
