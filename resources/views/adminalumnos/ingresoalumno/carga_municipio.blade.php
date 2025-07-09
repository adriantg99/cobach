<div class="form-group col-lg-12 col-12" id="carga_municipio">
    <label class="form-label">Municipio:*</label>
    <select class="form-control" 
            id="municipio_id"
            name="municipio_id"
            onchange="select_municipio_id_ajax($('#municipio_id').val(), $('#entidad_id').val() );return false;">
        <option value='00'>SD</option>
        @foreach($municipios as $municipio)
            <option value="{{$municipio->cve_mun}}">{{$municipio->cve_mun}} - {{$municipio->nom_mun}}</option>
        @endforeach
    </select>
    @error('municipio_id')<label class="alert-danger">{{ $message }}</label>@enderror
</div>       