require('./bootstrap');

import { createApp } from'vue'

//import prueba from './components/prueba.vue'
import formcicloesc from './components/catalogos/form_ciclo_esc.vue'
import formalumno from './components/adminalumnos/form_alumno.vue'
const app = createApp({})

//app.component('prueba', prueba);
app.component('formcicloesc', formcicloesc);

app.component('formalumno', formalumno);


app.mount('#app');

//window.Alpine = Alpine;

//Alpine.start();

