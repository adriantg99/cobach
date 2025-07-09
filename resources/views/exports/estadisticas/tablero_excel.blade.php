{{-- ANA MOLINA 2/09/2024 --}}
<table>
    <tr><td>Tablero de indicadores</td></tr>
    <tr><td>Ciclo escolar</td><td>{{$ciclonom}}</td></tr>
    @if ($plantelnom!='')
        <tr><td>Plantel</td><td>{{$plantelnom}}</td></tr>
    @endif
    @if ($periodonom!='')
    <tr><td>Semestre</td>{{$periodonom}}<td></td></tr>
    @endif
    @if ($turnonom!='')
    <tr><td>Turno</td><td>{{$turnonom}}</td></tr>
    @endif
    @if ($gruponom!='')
    <tr><td>Grupo</td><td>{{$gruponom}}</td></tr>
    @endif
    @if ($cursonom!='')
    <tr><td>Curso</td><td>{{$cursonom}}</td></tr>
    @endif
    @if ($docentenom!='')
    <tr><td>Docente</td><td>{{$docentenom}}</td></tr>
    @endif
</table>
<table>
    <tr style="color:white;background-color:grey">
        @php
         $cols= explode(",", $vars);
        @endphp
        @foreach ($cols as $var)
        <th rowspan=2  width="30">{{$var}}</th>
        @endforeach
        <th rowspan=2  width="20">Grupos</th>
        <th rowspan=2  width="20">Docentes</th>
        <th rowspan=2  width="20">Alumnos</th>
        @if ($chk1)
        <th colspan=5
            style="text-align: center;  border-left: gray solid; border-left-style: grove;">
            Parcial 1</th>
        @endif
        @if ($chk2)
        <th colspan=5
            style="text-align: center;  border-left: gray solid; border-left-style: grove;">
            Parcial 2</th>
        @endif
        @if ($chk3)
        <th colspan=5
            style="text-align: center;  border-left: gray solid; border-left-style: grove;">
            Parcial 3</th>
        @endif
        @if ($chkr)
      <th colspan=5
            style="text-align: center;  border-left: gray solid; border-left-style: grove;">
            Regularización</th>
        @endif
        @if ($chkf)
        <th colspan=5
            style="text-align: center;  border-left: gray solid; border-left-style: grove;">
            Final</th>
        @endif
    </tr>
    <tr>
        @if ($chk1)
        <th   width="15" style="border-left: gray solid; border-left-style: grove; ">Promedio
        </th>
        <th width="10">Aprobado</th>
        <th  width="15">% Aprobado</th>
        <th  width="10">Reprobado</th>
        <th  width="15">% Reprobado</th>
        @endif
        @if ($chk2)
        <th   width="15" style="border-left: gray solid; border-left-style: grove;">Promedio
        </th>
        <th  width="10">Aprobado</th>
        <th  width="15">% Aprobado</th>
        <th  width="10">Reprobado</th>
        <th  width="15">% Reprobado</th>
        @endif
        @if ($chk3)
        <th   width="15" style="border-left: gray solid; border-left-style: grove;">Promedio
        </th>
        <th  width="10">Aprobado</th>
        <th  width="15">% Aprobado</th>
        <th  width="10">Reprobado</th>
        <th  width="15">% Reprobado</th>
        @endif
        @if ($chkr)
        <th   width="15" style="border-left: gray solid; border-left-style: grove;">Promedio
        </th>
        <th  width="10">Aprobado</th>
        <th  width="15">% Aprobado</th>
        <th  width="10">Reprobado</th>
        <th  width="15">% Reprobado</th>
        @endif
        @if ($chkf)
        <th   width="15" style="border-left: gray solid; border-left-style: grove;">Promedio
        </th>
        <th  width="10">Aprobado</th>
        <th  width="15">% Aprobado</th>
        <th  width="10">Reprobado</th>
        <th  width="15">% Reprobado</th>
        @endif
    </tr>
    @if (isset($dashb) )
    @foreach ($dashb as $da)
    <tr>
        @foreach ($cols as $var)
        <td >
            @php
            $valor='';
            if($var=='plantel' && isset($da->plantel))
            $valor= $da->plantel;
            else
            if($var=='grupo' && isset($da->grupo))
            $valor= $da->grupo;
            else
            if($var=='grado' && isset($da->periodo))
            $valor= $da->periodo;
            else
            if($var=='turno' && isset($da->turno))
            $valor= $da->turno;
            else
            if($var=='curso' && isset($da->curso))
            $valor= $da->curso;
            else
            if($var=='docente' && isset($da->docente))
            $valor= $da->docente;
            else
            if($var=='alumno' && isset($da->alumno))
            $valor= $da->alumno;
            else
            if($var=='noexpediente' && isset($da->noexpediente))
            $valor= $da->noexpediente;
            @endphp
            {{$valor}}
        </td>
        @endforeach
        <td >@if (isset($da->grupos)) {{$da->grupos}} @endif</td>
        <td >@if (isset($da->docentes)) {{$da->docentes}} @endif</td>
        <td >@if (isset($da->alumnos)) {{$da->alumnos}} @endif </td>
        @if ($chk1)
        <td  style="border-left: gray solid; border-left-style: grove;">@if (isset($da->p1)) {{$da->p1}} @endif </td>
        <td >@if (isset($da->aprobado1)) {{$da->aprobado1}} @endif </td>
        <td >@if (isset($da->pap1)) {{$da->pap1}} @endif </td>
        <td >@if (isset($da->reprobado1)) {{$da->reprobado1}} @endif </td>
        <td >@if (isset($da->prep1)) {{$da->prep1}} @endif </td>
        @endif
        @if ($chk2)
        <td  style="border-left: gray solid; border-left-style: grove;">@if  (isset($da->p2)) {{$da->p2}} @endif </td>
        <td >@if (isset($da->aprobado2)) {{$da->aprobado2}} @endif </td>
        <td >@if (isset($da->pap2)) {{$da->pap2}} @endif </td>
        <td >@if (isset($da->reprobado2)) {{$da->reprobado2}} @endif </td>
        <td >@if (isset($da->prep2)) {{$da->prep2}} @endif </td>
        @endif
        @if ($chk3)
        <td  style="border-left: gray solid; border-left-style: grove;">@if (isset($da->p3)) {{$da->p3}} @endif </td>
        <td >@if (isset($da->aprobado3)) {{$da->aprobado3}} @endif </td>
        <td >@if (isset($da->pap3)) {{$da->pap3}} @endif </td>
        <td >@if (isset($da->reprobado3)){{$da->reprobado3}} @endif </td>
        <td >@if (isset($da->prep3)) {{$da->prep3}} @endif </td>
        @endif
        @if ($chkr)
        <td  style="border-left: gray solid; border-left-style: grove;">@if (isset($da->r)) {{$da->r}} @endif </td>
        <td >@if (isset($da->aprobador)) {{$da->aprobador}} @endif </td>
        <td >@if (isset($da->papr)) {{$da->papr}} @endif </td>
        <td >@if (isset($da->reprobador)) {{$da->reprobador}} @endif </td>
        <td >@if (isset($da->prepr)) {{$da->prepr}} @endif </td>
        @endif
        @if ($chkf)
        <td  style="border-left: gray solid; border-left-style: grove;">@if (isset($da->final)) {{$da->final}} @endif </td>
        <td >@if (isset($da->aprobado)) {{$da->aprobado}} @endif </td>
        <td >@if (isset($da->pap)) {{$da->pap}} @endif </td>
        <td >@if (isset($da->reprobado)) {{$da->reprobado}} @endif </td>
        <td >@if (isset($da->prep)) {{$da->prep}} @endif </td>
        @endif
    </tr>
    @endforeach
    @endif
</table>
