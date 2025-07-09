<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">SUBIR IMAGENES A ALUMNOS</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xl-11 text-nowrap">
                    <div>
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                    
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    
                        <form wire:submit.prevent="import">
                            <div class="form-group">
                                <label for="file">Archivo de Excel</label>
                                <input type="file" id="file" wire:model="file" class="form-control" accept=".xlsx, .xls, .csv">
                                @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div wire:loading>

            </div>

        </div>
        <!--<input class="form-control" placeholder="0" name="cantidad_grupos_extra" type="text" id="cantidad_grupos_extra" wire:model="cantidad_grupos_extra">-->

        <div class="mb-4"></div> <!-- Elemento de separación con margen inferior -->
        {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    </div>


</div>
