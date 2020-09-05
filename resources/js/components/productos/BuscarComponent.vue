<template>
	<div class="input-group mb-3">
		<input 
            type="text" 
            v-on:keyup="searchProducto()" 
            class="form-control" 
            v-model="buscar" 
            id="buscar" 
            placeholder="Buscar producto"
        />
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	export default {
	 	data() {
	 		return {
	 			buscar: null,
	 		}
	 	},
        methods: {
            //Envia los datos al controlador para buscar un registro.
        	searchProducto: function(){
        		var dato = $('#buscar').val();
    			axios
                    .get('http://beta.test/productos',{ params:{ buscar: dato, } })
                    //Evento para hacer la actualizacion de registros al buscar.
                    .then(response => { EventBus.$emit('producto-search',response.data); })
                    .catch(error => { console.log(error); })
                ;
        	}
        }
    }
</script>