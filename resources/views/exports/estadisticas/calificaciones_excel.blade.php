{{-- ANA MOLINA 18/02/2024 --}}
<table>
	<thead>
		<tr>
            <td>CICLO ESCOLAR</td>
            <td>ESCUELA</td>
            <td>MATRICULA</td>
            <td>ALUMNO</td>
            <td>APELLIDO PATERNO</td>
            <td>APELLIDO MATERNO</td>
            <td>EDAD</td>
            <td>GENERO</td>
            <td>CLAVE EMPLEADO PROFESOR</td>
            <td>CURP PROFESOR</td>
            <td>PROFESOR</td>
            <td>PROFESOR APELLIDO PATERNO</td>
            <td>PROFESOR APELLIDO MATERNO</td>
            <td>SEMESTRE ALUMNO</td>
            <td>CLAVE DE ASIGNATURA</td>
            <td>SEMESTRE DE ASIGNATURA</td>
            <td>ASIGNATURA</td>
            <td>CALIFICACION FINAL EN BOLETA</td>
            <td>APROBADA</td>
            <td>REPROBADA</td>
		</tr>
	</thead>

        @if (!empty($calificaciones))
        <tbody>
           @foreach($calificaciones as $cal)
            <tr>
                <td>{{$cal->ciclo}} </td>
                <td>{{$cal->plantel}} </td>
                <td> {{$cal->noexpediente}}  </td>
                <td>{{$cal->nombre}} </td>
                <td>{{$cal->apellidopaterno}} </td>
                <td>{{$cal->apellidomaterno}} </td>
                <td>{{$cal->edad}} </td>
                <td>{{$cal->genero}} </td>
                <td>{{$cal->noempleado}} </td>
                <td>{{$cal->curp}} </td>
                <td>{{$cal->profesor}} </td>
                <td>{{$cal->apellidopat}}</td>
                <td>{{$cal->apellidomat}} </td>
                <td>{{$cal->grado}} </td>
                <td>{{$cal->clave}} </td>
                <td>{{$cal->periodo}} </td>
                <td>{{$cal->asignatura}} </td>
                <td>{{$cal->calificacion}} </td>
                <td>{{$cal->aprobada}} </td>
                <td>{{$cal->reprobada}} </td>
         </tr>
          @endforeach
        </tbody>
        @endif
</table>
