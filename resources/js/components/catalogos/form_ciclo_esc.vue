<template>
	<section class="bg-light app-filters">
		<h3>{{ titulo }}</h3>
		<div v-if="errors">
        <div v-for="(v, k) in errors" :key="k">
          <p class="text-danger" v-for="error in v" :key="error">
              {{ error }}
          </p>
        </div>
    	</div>
		<div class="row g-3">

	        <div class="col-12 col-sm-8">
	          <label for="nombre" class="form-label">Nombre:</label>
	          <input class="form-control" 
	            placeholder="Nombre del ciclo escolar" 
	            name="nombre" 
	            type="text" 
	            v-model="ciclo_esc.nombre" 
	            >

	        </div>
	        <div class="col-12 col-sm-8">
	          <label for="abreviatura" class="form-label">Abreviatura:</label>
	          <input class="form-control" 
	            placeholder="Abreviatura del ciclo escolar" 
	            name="abreviatura" 
	            type="text" 
	            v-model="ciclo_esc.abreviatura" 
	            >

	            
	        </div>

	        <div class="col-12 col-sm-8">
	          <label for="per_inicio" class="form-label">Inicio del periodo:</label>
	          <input class="form-control" 
	            
	            name="per_inicio" 
	            type="date" 
	            v-model="ciclo_esc.per_inicio" 
	            >

	            
	        </div>

	        <div class="col-12 col-sm-8">
	          <label for="per_final" class="form-label">Final del periodo:</label>
	          <input class="form-control" 
	            
	            name="per_final" 
	            type="date" 
	            v-model="ciclo_esc.per_final" 
	            >

	            
	        </div>

					<div class="col-12 col-sm-8"><br><br>
                  
                    <input class="checkbox" type="checkbox" id="activo" name="activo" v-model="ciclo_esc.activo">
                    <label class="form-label"><strong>ACTIVO</strong></label>
                  
          </div>

	    </div>
	        <div class="row g-3 mt-3">
		        <div class="col-sm-8">
		        	<button class="btn btn-primary" v-on:click="Guardar">Guardar</button>
		        </div>
	   		</div>
	</section>
</template>

<script>
import { ref } from 'vue'
import { reactive } from 'vue';
import get from 'axios';

export default {

	props: ['ciclo_esc_id'], //Datos pasados al componente

	setup(props){

		const errors = ref('');
		const ciclo_esc_new = ref([]);

		const ciclo_esc = reactive({
			nombre: '',
			abreviatura: '',
			per_inicio: '',
			per_final: '',
			activo: '',
		});

		const titulo = ref('');

		console.log("Ciclo_esc_id: "+props.ciclo_esc_id);

		if(props.ciclo_esc_id === undefined)
		{
			//No se pasaron datos al componente
			titulo.value = 'Guardar nuevo ciclo escolar';
		}
		else
		{
			//Se paso el id del registro a editar al componente
			titulo.value = 'Editar ciclo escolar Id: '+props.ciclo_esc_id;

			get('/api/ciclo_esc/get/'+props.ciclo_esc_id)
				.then(({data}) => {
					//alert (data);
					ciclo_esc.nombre= data.data.nombre;
					ciclo_esc.abreviatura = data.data.abreviatura;
					ciclo_esc.per_inicio = data.data.per_inicio.substring(0,10);
					ciclo_esc.per_final = data.data.per_final.substring(0,10);
					ciclo_esc.activo = data.data.activo;
					console.log("activo leid1: "+data.data.activo+" data.data");
				})
			console.log("Datos de registro id: "+props.ciclo_esc_id+" cargados");
			console.log("activo leido: "+ciclo_esc.activo+" -ciclo_esc-");
		}

		//funcion para el boton fuardar
		async function Guardar(){
			if(props.ciclo_esc_id === undefined)
			{
				//CREAR No se pasaron datos al componente
				console.log("Recibida en funcion Guardar CicloEsc: ("+ciclo_esc.nombre+")");
				errors.value = '';

				try{
					let response = await axios.post('/api/ciclo_esc/store', ciclo_esc);
					ciclo_esc_new.value = response.data.data;
				}
				catch(e){
					if(e.response.status === 422){
					errors.value = e.response.data.errors
					}
				}

				if(errors.value == '')
				{
					console.log("Registro agregado id:" + ciclo_esc_new.value.id);
					location.href = '/catalogos/ciclosesc/agregar/success/' + ciclo_esc_new.value.id;
				}
			}
			else
			{
				//EDITAR Se paso el id del registro a editar al componente
				console.log("Recibida en funcion Editar CicloEsc: ("+ciclo_esc.nombre+")");
				errors.value = '';

				try{
					let response = await axios.post('/api/ciclo_esc/'+props.ciclo_esc_id+'/editar', ciclo_esc);
					ciclo_esc_new.value = response.data.data;
				}
				catch(e){
					if(e.response.status === 422){
					errors.value = e.response.data.errors
					}
				}

				if(errors.value == '')
				{
					console.log("Registro editado id:" + ciclo_esc_new.value.id);
					location.href = '/catalogos/ciclosesc/agregar/success/' + ciclo_esc_new.value.id;
				}
			}
		}

		return {
			ciclo_esc,
			titulo,
			Guardar,
			errors,
			ciclo_esc_new,
		}
	},
}
</script>
