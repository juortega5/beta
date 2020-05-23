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
        mounted() {
        },
        methods: {
        	searchTercero: function(e){
        		var dato = $('#buscar').val()
        		let me = this;
    			axios.get('http://beta.test/terceros',{
        			params:{
                        buscar: dato,
                    }
    			}).then(function(response){
                    EventBus.$emit('tercero-search',response.data)
    			}).catch(function(error){
    				console.log(error)
    			});
        	}
        }
    }
</script>