{{-- ANA MOLINA 30/11/2023 --}}
<table>
    <thead>
        <tr>
            <td>Fecha</td>
            <td>{{$fecha}}</td>
        </tr>
    </thead>
</table>
<table>
	<thead>
		<tr>
            <td>CICLO ESCOLAR</td>
            <td>PLANTEL</td>
            <td>GRADO</td>
            <td>SEMESTRE</td>
            <td>TURNO</td>
            <td>NO. EXPEDIENTE O MATRICULA</td>
            <td>ALUMNO</td>
            <td>APELLIDO PATERNO</td>
            <td>APELLIDO MATERNO</td>
            <td>CURP</td>
            <td>SERIE</td>
            <td>CAPACITACION</td>
            <td>GENERO</td>
            <td>EDAD</td>
            <td>GRUPO</td>
            <td>DISCAPACIDAD</td>
            <td>ETNIA</td>
            <td>NACIONALIDAD</td>
            <td>SECUNDARIA DONDE CURSO</td>
            <td>TIPO DE SECUNDARIA</td>
            <td>LUGAR DE NACIMIENTO</td>
            <td>NUEVO INGRESO</td>
            <td>REPETIDORES</td>
            <td>ALTA INTERSEMESTRAL</td>
            <td>ALTA INTERCICLO</td>
            <td>ES NACIDO EN EL EXTRANJERO</td>
            <td>TIENE DISCAPACIDAD</td>
            <td>HABLA LENGUA INDIGENA</td>
            <td>REGULARIZADO</td>
            <td>IRREGULAR</td>
            <td>VIENEN DE OTRO INSTITUCION DE MEDIA SUPERIOR</td>
            <td>EXTRANJERO UNO DE SUS PADRES ES MEXICANO</td>
            <td>EXTRANJERO CON ESTUDIOS EN OTRO PAIS</td>
		</tr>
	</thead>

        @if (!empty($matricula))
        <tbody>
           @foreach($matricula as $mat)
            <tr>
                <td>{{$mat->ciclo}} </td>
                <td>{{$mat->plantel}} </td>
                <td> {{$mat->grado}}  </td>
                <td>{{$mat->periodo}} </td>
                <td>{{$mat->turno}} </td>
                <td>{{$mat->noexpediente}} </td>
                <td>{{$mat->nombre}} </td>
                <td>{{$mat->apellidopaterno}} </td>
                <td>{{$mat->apellidomaterno}}</td>
                <td>{{$mat->curp}} </td>
                <td>{{$mat->serie}} </td>
                <td>{{$mat->capacitacion}} </td>
                <td>{{$mat->genero}} </td>
                <td>{{$mat->edad}} </td>
                <td>{{$mat->grupo}} </td>
                <td>{{$mat->discapacidad}} </td>
                <td>{{$mat->etnia}} </td>
                <td>{{$mat->nacionalidad}} </td>
                <td>{{$mat->secundaria}} </td>
                <td>{{$mat->tiposec}} </td>
                <td>{{$mat->lugarnacimiento}} </td>
                <td>{{$mat->nuevoingreso}} </td>
                <td>{{$mat->repetidor}} </td>
                <td>{{$mat->altaintersem}} </td>
                <td>{{$mat->altaincerciclo}} </td>
                <td>{{$mat->nacidoext}} </td>
                <td>{{$mat->tienediscap}} </td>
                <td>{{$mat->lengua_indigena}} </td>
                <td>{{$mat->regular}} </td>
                <td>{{$mat->irregular}} </td>
                <td>{{$mat->otrainst}} </td>
                <td>{{$mat->extranjero_padre_mexicano}} </td>
                <td>{{$mat->extranjero_grado_Ems}} </td>
         </tr>
          @endforeach
        </tbody>
        @endif
</table>
