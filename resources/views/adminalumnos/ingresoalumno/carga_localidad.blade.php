<div class="form-group col-lg-12 col-12" id="carga_localidad">
    <label class="form-label">Localidad:*</label>
    <select class="form-control" 
            id="localidad_id"
            name="localidad_id">
        <option value='00'>SD</option>
       @foreach($localidades as $localidad)
        <option value="{{$localidad->cve_loc}}">{{$localidad->cve_loc}} - {{$localidad->nom_loc}}</option>
       @endforeach
    </select>
    @error('localidad_id')<label class="alert-danger">{{ $message }}</label>@enderror
</div>    