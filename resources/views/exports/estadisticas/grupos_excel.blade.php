{{-- ANA MOLINA 07/12/2023 --}}

<table>
	<thead>
		<tr>
            <td>CICLO</td>
            <td>PLANTEL</td>
            <td>TURNO</td>
            <td>SERIE</td>
            <td>CAPACITACION</td>
            <td>GRADO</td>
            <td>SEMESTRE</td>
            <td>GRUPO</td>
            <td>GRUPO DE FORMACION</td>
            <td>GRUPO SERIE</td>
            <td>GRUPO CAPACITACION</td>
		</tr>
	</thead>

        @if (!empty($grupos))
        <tbody>
           @foreach($grupos as $gr)
            <tr>
                <td>{{$gr->ciclo}} </td>
                <td>{{$gr->plantel}} </td>
                <td>  </td>
                <td>  </td>
                <td> </td>
                <td>  </td>
                <td>  </td>
                <td>{{$gr->grupo}} </td>
                <td></td>
              <td> </td>
              <td>  </td>
         </tr>
          @endforeach
        </tbody>
        @endif
</table>
