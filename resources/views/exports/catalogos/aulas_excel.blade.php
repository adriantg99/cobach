<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Plantel_ID</th>
			<th>Plantel_Abrev</th>
			<th>Nombre</th>
			<th>Tipo Aula</th>
			<th>Condición Aula</th>
			<th>Activa</th>
			<th>Descripción</th>
			<th>F_Creación</th>
			<th>F_Edición</th>
		</tr>
	</thead>
	<tbody>
		@foreach($aulas as $aula)
			<tr>
				<td>{{$aula->id}}</td>
				<td>{{$aula->plantel_id}}</td>
				<td>{{$aula->plantel->abreviatura}}</td>
				<td>{{$aula->nombre}}</td>
				<td>{{$aula->tipo_aula->descripcion}}</td>
				<td>{{$aula->condicion_aula->descripcion}}</td>
				<td>{{$aula->aula_activa}}</td>
				<td>{{$aula->descripcion}}</td>
				<td>{{$aula->created_at}}</td>
				<td>{{$aula->updated_at}}</td>
			</tr>
		@endforeach
	</tbody>
</table>