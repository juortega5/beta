<template>
	<div class="input-group mb-3">
		<input type="text" v-on:keyup="searchTercero()" class="form-control" v-model="buscar" id="buscar" placeholder="Buscar tercero">
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
        	searchTercero: function(e){
        		var dato = $('#buscar').val()
        		let me = this;
    			axios.get('http://beta.test/terceros',{
        			params:{
                        buscar: dato,
                    }
    			}).then(function(response){
                     //Evento para hacer la actualizacion de registros al buscar.
                    EventBus.$emit('tercero-search',response.data)
    			}).catch(function(error){
    				console.log(error)
    			});
        	}
        }
    }
</script>