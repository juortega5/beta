<template>
	<div class="input-group mb-3">
		<input type="text" v-on:keyup="searchProducto()" class="form-control" v-model="buscar" id="buscar" placeholder="Buscar producto">
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
        	searchProducto: function(e){
        		var dato = $('#buscar').val()
        		let me = this;
    			axios.get('http://beta.test/productos',{
        			params:{
                        buscar: dato,
                    }
    			}).then(function(response){
                    EventBus.$emit('producto-search',response.data)
    			}).catch(function(error){
    				console.log(error)
    			});
        	}
        }
    }
</script>